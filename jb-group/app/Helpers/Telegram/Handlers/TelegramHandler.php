<?php


namespace App\Helpers\Telegram\Handlers;


class TelegramHandler {

    /**
     * Объект обработчик
     *
     * @var        Object
     */
    private $bot = null;

    /**
     * Токен для соединения с телеграмом
     *
     * @var        string
     */
    private $token = '';

    public function __construct($opts)
    {
        $this->token = $opts['token'];
        $this->bot = new \TelegramBot\Api\Client($opts['token']);
    }

    /**
     * Отправка сообщения пользователю
     *
     * @param      string|int   $chatId              Айди чата
     * @param      string       $message              Текст сообщения
     * @param      string       $parseMode            Нужен ли парсинг сообщения для телеграм это HTML если в сообщении хранится код
     * @param      bool         $disablePreview       Нет необходимости в ней
     * @param      int          $replyToMessageId     Айди сообщения на который делается ответ
     * @param      null         $replyMarkup          Отправляемые команды
     * @param      bool         $disableNotification  Отключение уведомлений (нужно только для Телеграма)
     *
     * @return    void
     */
    public function sendMessage($chatId, $message, $parseMode = null, $disablePreview = false, $replyToMessageId = null, $replyMarkup = null, $disableNotification = false, $inline = true)
    {
        if ($replyMarkup) {
            if ($inline) {
                $replyMarkup = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($replyMarkup);
            } else {
                $replyMarkup = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($replyMarkup, true); // true for one-time keyboard
            }
        }

        $this->bot->sendMessage($chatId, $message, $parseMode, $disablePreview, $replyToMessageId, $replyMarkup, $disableNotification);
    }

    public function setWebHook()
    {
        $this->bot->deleteWebhook();
        $this->bot->setWebHook('https://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?handler=telegram');
    }

    /**
     * Обновление сообщения у пользователя. Подойдёт для изменения кнопок в чате с клиентом
     *
     * @param      string|int   $chatId              Айди чата
     * @param      string|int   $messageId              Айди чата
     * @param      string       $message              Текст сообщения
     * @param      null         $replyMarkup          Отправляемые команды
     *
     * @return    void
     */
    public function updateMessage($chatId, $messageId, $message, $replyMarkup = null, $inline = true, $send_new = false)
    {
        // Если использована команда On то с неё надо создать новое сообщение
        if ($send_new) {
            return $this->sendMessage($chatId, $message, 'HTML', false, null, $replyMarkup, false);
        }

        if (!is_null($replyMarkup)) {
            if ($inline) {
                $replyMarkup = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($replyMarkup);
            } else {
                $replyMarkup = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($replyMarkup, true); // true for one-time keyboard
            }
        }

        $this->bot->editMessageText($chatId, $messageId, $message, "HTML", false, $replyMarkup, null);
    }

    /**
     * Вывод всплывающих уведомлений (реализовано только в телеграм. Остальные сервисы такое не поддерживают)
     *
     * @param      int    $callbackQueryId  The callback query identifier
     * @param      string    $text             The text
     * @param      bool      $showAlert        The show alert
     */
    public function answerCallbackQuery($callbackQueryId, $text = null, $showAlert = false)
    {
        $this->bot->answerCallbackQuery($callbackQueryId, $text, $showAlert);
    }

    /**
     * Получение текста из сообщения
     *
     * @param      object|array  $object  Входящее сообщение
     *
     * @return     string  Текст сообщения
     */
    public function getText($object)
    {
        return $object->getText();
    }

    /**
     * Получение айди сообщения
     *
     * @param     object
     *
     * @return int
     */
    public function getMessageId($object)
    {
        if ($object instanceof \TelegramBot\Api\Types\CallbackQuery) {
            $object = $object->getMessage();
        }
        return $object->getMessageId();
    }

    /**
     * Получение команды
     *
     * @param      object|array   $object  Входящее сообщение
     *
     * @return     string|null  The command.
     */
    public function getCommand($object)
    {
        return $object->getData();
    }

    /**
     * Получение имени пользователя из чата
     *
     * @param      object|array  $object  Входящее сообщение
     *
     * @return     string  Возвращает имя пользователя
     */
    public function getUsername($object)
    {
        return $object->getFrom()->getUsername();
    }

    /**
     * Получение айди чата
     *
     * @param      object|array   $object  Входящее сообщение
     *
     * @return     string|int|long Возвращает айди чата
     */
    public function chatId($object)
    {
        if ($object instanceof \TelegramBot\Api\Types\CallbackQuery) {
            $object = $object->getMessage();
        }
        return $object->getChat()->getId();
    }

    /**
     * Отправка ссылок на товары
     *
     * @param      string|int|long  $chatId  Айди чата в который отправляется сообщение
     * @param      string           $answer   Текст сообщения
     * @param      array            $result   Найденые товары
     */
    public function sendLinks($chatId, $answer, $result, $callback = false)
    {
        $this->sendMessage($chatId, $answer);

        foreach ($result as $key => $value) {
            $text = $value['title'];
            if (isset($value['price'])) {
                $price = number_format($value['price'], 0, '', ' ');
                $text .= " ({$price} тенге за 1 шт) ";
            }
            $text .= "\n{$value['link']}";
            $this->bot->sendPhoto($chatId, $value['image'], $text, null, null, false, 'HTML');
        }

        if (is_callable($callback)) {
            $callback();
        }
    }

    /**
     * Обработка отдельных команд
     *
     * @param      strign    $event     Стока событие
     * @param      callBack  $callback  Функция обработчик
     */
    public function command($event, $callback)
    {
        $this->bot->command($event, $callback);
    }

    /**
     * Обработчик текстовых сообщений (вызывается только если в тексте нет команд)
     *
     * @param      callBack  $callback  Функция обработчик
     */
    public function on($callback)
    {
        $this->bot->on(function (\TelegramBot\Api\Types\Update $update) use ($callback) {
            $callback($update->getMessage());
        }, function () {
            return true;
        });
    }

    /**
     * Обработчик входящих команд от пользователя
     *
     * @param      callBack  $callback  Функция обработчик
     */
    public function callbackQuery($callback)
    {
        $this->bot->callbackQuery($callback);
    }

    /**
     * Обработчик неизвестных команд
     *
     * @param      string|int|long  $chatId       Айди чата куда отправляется сообщение
     * @param      string           $message_text  Входящее сообщение
     * @param      int              $id            Айди (Не всегда есть)
     * @param      array            $default_menu  Дефолтное меню
     */
    public function callNotFound($chatId, $message_text, $id, $default_menu = [])
    {

    }

    /**
     * Функция которая в обязательном порядке должна закрывать скрипт
     *
     * Критично для телеграма
     */
    public function main()
    {
        $this->bot->run();
    }

    public function __call($method, $args)
    {
        if (method_exists($this->bot, $method)) {
            return call_user_func_array(array($this->bot, $method), $args);
        }
        throw new \Exception("Неизвестный метод {$method}", 1);
    }

}
