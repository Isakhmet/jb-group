<?php

namespace App\Helpers\Telegram;

use Illuminate\Support\Facades\Cache;

class Bot {

    /**
     * Обработчик устанавливается из REQUEST
     *
     * @var        string
     */
    public $handler = '';

    /**
     * Подключенный мемкеш
     *
     * @var        Resource
     */
    private $memcache = null;

    /**
     * Сам обработчик выбранный из $handler
     *
     * @var        object
     */
    private $router = null;

    /**
     * Имя пользователя с которым ведётся диалог (используется для статистики)
     *
     * @var       string
     */
    private $last_name = null;

    /**
     * Айди чата с которым ведётся диалог
     *
     * @var     string|int
     */
    private $chatId = null;

    /**
     * Команда присланная от обработчика
     *
     * @var     string|int
     */
    private $command = '';

    /**
     * Конфиг бота
     *
     * @var        array
     */
    private $config = [
        'handler' => '',
        'handlers' => []
    ];

    public $repository;

    public function __construct($config)
    {
        $this->repository = new BotRepository();
        $this->config = array_replace_recursive($this->config, $config);
        $this->handler = strtolower($this->config['handler']);

        if (!isset($this->config['handlers'][$this->handler])) {
            throw new \Exception("Unknown Bot config handler", 1);
        }

        $opts = $this->config['handlers'][$this->handler];
        $class = $opts['handler'];
        $this->router = new $class($opts);
    }

    /**
     * Устанавливает и получает имя пользователя для учёта статистики
     *
     * @param      string  $name   The name
     *
     * @return     string  ( description_of_the_return_value )
     */
    public function lastName($name = null)
    {
        if (is_null($name)) {
            return $this->last_name;
        }
        return $this->last_name = $name;
    }

    /**
     * Кеширование результата выполненого в callBack функции
     *
     * @param string $key - ключ который передаётся в callBack функцию
     *
     * @param string $callBack - функция в которой выполняется какое-то действие
     *
     * @param int $cache_time - время жизни кеша
     */
    public function cached ($key, $callBack, $cache_time = 3600)
    {
        if (!($result = $this->getCache('data-cache'.$key))) {
            $result = $callBack($key);
            $this->setCache('data-cache'.$key, $result, $cache_time);
        }
        return $result;
    }

    public function setCache($key, $data, $time = 3600)
    {
        Cache::put('bot_'.$key, $data, $time);
    }

    public function getCache($key)
    {
        return Cache::get('bot_'.$key);
    }

    /**
     * Установка комманд в кеш
     *
     * @param      string  $key    The key
     * @param      string|array  $data   The data
     * @param      int      $cache_time   Cache time
     *
     * @return     bool  ( description_of_the_return_value )
     */
    public function setCommandCache($chatId, $name, $value, $cache_time = 3600)
    {
        $this->setCache(md5($chatId.$name), $value, $cache_time);
    }

    /**
     * Получение команды из кеша
     *
     * @param string
     *
     * @return string
     */
    public function getCommandCache($chatId, $name)
    {
        return $this->setCache(md5($chatId.$name));
    }

    /**
     * Получение команды из текста (не обязательная команда)
     *
     * @param      string  $text   The text
     *
     * @return     string  The bottom text message.
     */
    public function getBotTextMessage($text)
    {
        preg_match('/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/', $text, $matches);
        if (isset($matches[3])) {
            return $matches[3];
        }
        return $matches[1];
    }

    /**
     * @param      $commandRaw
     * @param null $chatId
     *
     * @return array
     */
    public function parseForInlineCommand($commandRaw, $chatId = null)
    {
        // example: tires_car:1:2:3:34
        $checkCount = explode(':', $commandRaw);
        $countCases = count($checkCount);

        if (end($checkCount) == 'back') {
            unset($checkCount[$countCases-1]);
            if (count($checkCount) >= 2) {
                unset($checkCount[count($checkCount)-1]);
                $countCases -= 2;
            } else {
                $countCases = 1;
            }
            $commandRaw = str_replace(':back', '', implode(':', $checkCount));
        }else if ($chatId) {
            $this->setCache('backLink'.$chatId.$countCases, $checkCount[$countCases-1]);
        }

        return [$commandRaw, $checkCount, $countCases];
    }

    public function getCallBackCommand($commandRaw)
    {
        if (mb_strlen($commandRaw) >= 64) {
            $exp = explode(':', $commandRaw);
            $arr = range(0, count($exp)-3);
            $commandRaw = $exp[0].':'.implode(':', $arr).':'.end($exp);
        }

        return $commandRaw;
    }

    /**
     * Генерация умных кнопок выстраивает блоки
     *
     * @param array $result
     *
     * @param int $max_inline
     *
     * @param int $max_word
     */
    public function generateButtons($result, $commandRaw, $max_inline = 3, $max_word = 17)
    {
        $buttons = [];

        for ($i = 0; $i < count($result); $i += 3) {
            $strlen_text = 0;
            for ($j = 0; $j < $max_inline && isset($result[$i + $j]) && $strlen_text < $max_word; $j++) {
                $buttons[$i][$j] = ['text' => $result[$i + $j], 'callback_data' => $this->getCallBackCommand($commandRaw.':'.$result[$i + $j])];
                $strlen_text += mb_strlen($result[$i + $j]);
            }
        }

        return array_values($buttons);
    }

    /**
     * Обработка запросов на Обработчики для Telegram, vk, whatsapp
     *
     * @param      string      $method  The method
     * @param      array       $args    The arguments
     *
     * @throws     \Exception  (description)
     *
     * @return     mixed      ( description_of_the_return_value )
     */
    public function __call($method, $args)
    {

        /**
         * Дополнительные функции чтобы не создавать однообразный код
         *
         * @method isWhatsapp @return boolean
         *
         * @method isTelegram @return boolean
         *
         * @method isVk @return boolean
         *
         * @method isInstagram @return boolean
         *
         * @method isFb @return boolean
         *
         */
        if (in_array($method, ['isWhatsapp', 'isTelegram', 'isVk', 'isInstagram', 'isFb'])) {
            return (strtolower(str_replace('is','', $method)) == $this->handler);
        }

        if (method_exists($this->router, $method)) {
            return call_user_func_array(array($this->router, $method), $args);
        }

        throw new \Exception("Неизвестный метод {$method}", 1);
    }
}
