<?php

namespace App\Helpers\Telegram;

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
    private $chat_id = null;

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
        'stats_iblock_id' => 0,
        'votes_iblock_id' => 0,
        'handler' => '',
        'logger' => false,
        'handlers' => []
    ];

    public function __construct($config){

        $this->config = array_replace_recursive($this->config, $config);
        $this->handler = strtolower($this->config['handler']);

        if (!isset($this->config['handlers'][$this->handler])) {
            throw new \Exception("Unknown Bot config handler", 1);
        }

        $opts = $this->config['handlers'][$this->handler];
        $class = $opts['handler'];
        $this->router = new $class($opts, $this);
    }

    /**
     * Устанавливает и получает имя пользователя для учёта статистики
     *
     * @param      string  $name   The name
     *
     * @return     string  ( description_of_the_return_value )
     */
    public function lastName($name = null){
        if (is_null($name)) {
            return $this->last_name;
        }
        return $this->last_name = $name;
    }

    /**
     * Установка значения в мемкеш
     *
     * @param      string  $key    The key
     * @param      string|array  $data   The data
     *
     * @return     bool  ( description_of_the_return_value )
     */
    public function cacheSet($key, $data, $time = 3600){
        //return $this->memcache->set('bot_'.$key, $data, false, $time);
    }

    /**
     * Добавление данных в кеш если такого кеша ещё нет он создаётся
     *
     * @param      string  $key    The key
     * @param      string|array  $data   The data
     * @param      int      $cache_time   Cache time
     *
     * @return     bool  ( description_of_the_return_value )
     */
    public function cacheAppend($key, $data, $time = 3600) {
        if (!($result = $this->cacheGet($key))) {
            return $this->cacheSet($key, $data);
        }
        return $this->cacheSet($key, $result.$data);
    }

    /**
     * Получение данных из кеша
     *
     * @param      string  $key    The key
     *
     * @return     string  ( description_of_the_return_value )
     */
    public function cacheGet($key){dd($key);
        return $this->memcache->get('bot_'.$key);
    }

    /**
     * Удаление данных из кеша
     *
     * @param      string  $key    The key
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function cacheDelete($key){
        return $this->memcache->delete('bot_last_command'.$key);
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
    public function cached ($key, $callBack, $cache_time = 3600) {
        if (!($result = $this->cacheGet('data-cache'.$key))) {
            $result = $callBack($key);
            $this->cacheSet('data-cache'.$key, $result, $cache_time);
        }
        return $result;
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
    public function setCommandCache($chat_id, $name, $value, $cache_time = 3600){
        $this->cacheSet(md5($chat_id.$name), $value, $cache_time);
    }

    /**
     * Получение команды из кеша
     *
     * @param string
     *
     * @return string
     */
    public function getCommandCache($chat_id, $name){
        return $this->cacheGet(md5($chat_id.$name));
    }

    public function addQueueForVote($chat_id){
        //return file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/App/Logs/queue/'.str_replace('@','(a)', $chat_id).'_'.$this->handler, '');
    }

    /**
     * Получение команды из текста (не обязательная команда)
     *
     * @param      string  $text   The text
     *
     * @return     string  The bottom text message.
     */
    public function getBotTextMessage($text){
        preg_match('/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/', $text, $matches);
        if (isset($matches[3])) {
            return $matches[3];
        }
        return $matches[1];
    }

    /**
     * Парсинг команды назад
     *
     * @param string
     *
     * @return array
     */
    public function parseForInlineCommand($command_raw, $chat_id = null){

        // example: tires_car:1:2:3:34
        $check_count = explode(':', $command_raw);
        $count_cases = count($check_count);
        if (end($check_count) == 'back') {
            unset($check_count[$count_cases-1]);
            if (count($check_count) >= 2) {
                unset($check_count[count($check_count)-1]);
                $count_cases -= 2;
            } else {
                $count_cases = 1;
            }
            $command_raw = str_replace(':back', '', implode(':', $check_count));
        } else if ($chat_id) {
            $this->cacheSet('backLink'.$chat_id.$count_cases, $check_count[$count_cases-1]);
        }
        return [$command_raw, $check_count, $count_cases];
    }

    public function lang($method) {
        if (!$this->lang) {
            if (!($lang_id = $this->cacheGet('lang_id'))) {
                $lang_id = 'ru';
            }
            $this->lang = new \App\Classes\Lang($lang_id);
        }
        return $this->lang->get($method);
    }

    public function setLang($chat_id, $lang_id) {
        $file = __DIR__ . '/Langs/Lang_'.$lang_id.'.php';
        if (file_exists($file)) {
            $this->cacheSet('lang_id'.$chat_id, $lang_id);
        }
    }

    public function getCallBackCommand($command_raw){
        if (mb_strlen($command_raw) >= 64) {
            $exp = explode(':', $command_raw);
            $arr = range(0, count($exp)-3);
            $command_raw = $exp[0].':'.implode(':', $arr).':'.end($exp);
        }
        return $command_raw;
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
    public function generateButtons($result, $command_raw, $max_inline = 3, $max_word = 17){
        $keys = array_keys($result);
        $values = array_values($result);
        $buttons = [];
        for ($i = 0; $i < count($keys); $i += 3) {
            $strlen_text = 0;
            for ($j = 0; $j < $max_inline && isset($values[$i + $j]) && $strlen_text < $max_word; $j++) {
                $buttons[$i][$j] = ['text' => $values[$i + $j], 'callback_data' => $this->getCallBackCommand($command_raw.':'.$keys[$i + $j])];
                $strlen_text += mb_strlen($values[$i + $j]);
            }
        }
        return array_values($buttons);
    }

    public function setVote($chat_id, $props) {

        \CModule::IncludeModule('iblock');

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "IBLOCK_ID"      => $this->config['votes_iblock_id'],
            "PROPERTY_VALUES"=> $props,
            "NAME"           => $this->lastName(),
            "ACTIVE"         => "Y",            // активен
        );

        if (!($PRODUCT_ID = $el->Add($arLoadProductArray))) {
            $this->setLog('insert vote error', $el->LAST_ERROR);
        }
    }

    /**
     * Установка статистики
     *
     * @param      int  $type     The type
     * @param      int  $id       The new value
     * @param      string  $comment  The comment
     */
    public function setStats($type, $chat_id, $comment = '') {
        \CModule::IncludeModule('iblock');
        $this->setLog('name', $this->lastName());
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "IBLOCK_ID"      => $this->config['stats_iblock_id'],
            "NAME"           => $this->lastName(),
            "ACTIVE"         => "Y",            // активен
            "PROPERTY_VALUES"=> [
                'EVENT' => [$type],
                'MESSENGER' => Array( (int)$this->config['handlers'][$this->handler]['list_id']),
                'CHAT_ID' => $chat_id
            ],
        );

        if (!($PRODUCT_ID = $el->Add($arLoadProductArray))) {
            $this->setLog('insert stats error', $el->LAST_ERROR);
        }
    }

    /**
     * Установка логов
     */
    public function setLog(){
        if (!$this->config['logger']) {
            return false;
        }
        //return file_put_contents(__DIR__ .'/Logs/file_log_'.$this->handler.'.txt', '['.date('d.m.Y H:i:s').'] '.print_r(func_get_args(), true), FILE_APPEND);
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
    public function __call($method, $args){

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
            $this->setLog($method);
            return call_user_func_array(array($this->router, $method), $args);
        }
        throw new \Exception("Неизвестный метод {$method}", 1);
    }
}
