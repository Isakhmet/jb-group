<?php

namespace App\Helpers\Telegram\Handlers;

class WhatsappHandler {

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

    /**
     * Объект родительского класса для записи логов
     *
     * @var        Object
     */
    private $parent = null;

    /**
     * Входящие данные присланные от вк
     *
     * @var        array
     */
    private $callback_event = [];

    private $options = [
        'handler' => 'Whatsapp',
        'token' => '',
        'instance' => '',
        'list_id' => 0,
        'operators' => [],
        'button_type' => 0,
        'buttons_always_shown' => []
    ];

    private $function_command = false;

    /**
     * Конвертация для вацап, только он не умеет кнопки отправлять
     *
     * @var        array
     */
    private $parse_command = [

    ];

    public function __construct($opts, $parent){
        $this->options = array_merge($this->options, $opts);
        $this->parent = $parent;
        $this->bot = new \Mike4ip\ChatApi($this->options['token'], 'https://eu33.chat-api.com/instance'.$this->options['instance']);
        $this->callback_event = $this->_callback_getEvent();

        if ($this->callback_event['fromMe']) {
            exit;
        }

        if (strpos($this->chatId($this->callback_event), '@g.us') !== false) {
            throw new Exception("Попытка написать от группы", 1);
        }
    }

    public function setWebHook(){
        //$this->bot->setWebHook('https://'.$_SERVER['SERVER_NAME'].'/'.$_SERVER['SCRIPT_NAME']);
    }

    /**
     * Отправка сообщения пользователю
     *
     * @param      string|int   $chat_id              Айди чата
     * @param      string       $message              Текст сообщения
     * @param      string       $parseMode            Нужен ли парсинг сообщения для телеграм это HTML если в сообщении хранится код
     * @param      bool         $disablePreview       Нет необходимости в ней
     * @param      int          $replyToMessageId     Айди сообщения на который делается ответ
     * @param      null         $replyMarkup          Отправляемые команды
     * @param      bool         $disableNotification  Отключение уведомлений (нужно только для Телеграма)
     *
     * @return    void
     */
    public function sendMessage($chat_id, $message, $parseMode = null, $disablePreview = false, $replyToMessageId = null, $replyMarkup = null, $disableNotification = false) {

        $message = htmlspecialchars_decode(strip_tags(str_replace(['<b>','</b>'], ['*','*'], $message)), ENT_QUOTES);
        if (!empty($replyMarkup)) {
            $num_command = 1;
            $buttons = [];
            $count_buttons = 0;
            foreach ($replyMarkup as $row) {
                foreach ($row as $key => $value) {
                    $count_buttons++;
                }
            }

            foreach ($replyMarkup as $row) {
                foreach ($row as $key => $value) {
                    if ($this->options['buttons_type'] == 1 || $count_buttons < 4 || in_array($value['text'], $this->options['buttons_always_shown'])) {
                        $this->parent->setCommandCache($chat_id, $value['text'], $value['callback_data'], 3600 * 4);
                        $buttons[] = $value['text'];
                    } else {
                        if ($num_command == 1) {
                            $message .= "\n";
                        }
                        $this->parent->setCommandCache($chat_id, '/'.$num_command, $value['callback_data'], 3600 * 4);
                        $message .= "\n*/".$num_command."* - {$value['text']}";
                        $num_command++;
                    }
                }
            }

            if (!empty($buttons)) {
                if ($buttons[0] === 0) {
                    unset($buttons[0]);
                    $buttons = array_values($buttons);
                }
                $this->parent->setLog($chat_id, $message, $buttons);

                $res = $this->bot->sendButtons($chat_id, $message, $buttons);
                $this->parent->setLog($chat_id, $message, 'Результат', $res);
                return $res;
            }
        }

        $this->bot->sendMessage($chat_id, $message);
    }

    /**
     * Обновление сообщения у пользователя. Подойдёт для изменения кнопок в чате с клиентом
     *
     * @param      string|int   $chat_id              Айди чата
     * @param      string|int   $message_id              Айди чата
     * @param      string       $message              Текст сообщения
     * @param      null         $replyMarkup          Отправляемые команды
     *
     * @return    void
     */
    public function updateMessage($chat_id, $message_id, $message, $replyMarkup = null, $inline = true, $send_new = false){
        $this->sendMessage($chat_id, $message, "HTML", false, false, $replyMarkup, null);
    }

    /**
     * Получение айди сообщения
     *
     * @param     object
     *
     * @return int
     */
    public function getMessageId($object){

        return 0;
    }

    /**
     * Получение текста из сообщения
     *
     * @param      object|array  $object  Входящее сообщение
     *
     * @return     string  Текст сообщения
     */
    public function getText($object){
        return $this->callback_event['body'];
    }

    /**
     * Получение команды
     *
     * @param      object|array   $object  Входящее сообщение
     *
     * @return     string|null  The command.
     */
    public function getCommand($object){

        $command = trim($this->getText($object));
        if ($command[0] === '/') {
            return substr($command, 1);
        }
        return null;
    }

    /**
     * Получение имени пользователя из чата
     *
     * @param      object|array  $object  Входящее сообщение
     *
     * @return     string  Возвращает имя пользователя
     */
    public function getUsername($object){
        return $this->callback_event['senderName'];
    }

    /**
     * Получение айди чата
     *
     * @param      object|array   $object  Входящее сообщение
     *
     * @return     string|int|long Возвращает айди чата
     */
    public function chatId($object = false){
        return $this->callback_event['chatId'];
    }

    /**
     * Отправка ссылок на товары
     *
     * @param      string|int|long  $chat_id  Айди чата в который отправляется сообщение
     * @param      string           $answer   Текст сообщения
     * @param      array            $result   Найденые товары
     */
    public function sendLinks($chat_id, $answer, $result, $callback = null){
        $this->sendMessage($chat_id, $answer);
        $last_key = array_key_last($result);
        foreach ($result as $key => $value) {

            $text = $value['title'];
            if (isset($value['price'])) {
                $price = number_format($value['price'], 0, '', ' ');
                $text .= " ({$price} тенге за 1 шт) ";
            }
            $text .= "\n{$value['link']}";

            // Отправляем сообщение и прикрепляем к нему фото
            $res = $this->bot->sendFile($chat_id, $value['image'], $value['link'], $text);
            if ($last_key == $key && is_callable($callback)) {
                usleep(4000000);
                $callback();
            }
        }
    }

    /**
     * Обработка отдельных команд
     *
     * @param      strign    $event     Стока событие
     * @param      callBack  $callback  Функция обработчик
     */
    public function command($event, $callback){
        $command = $this->getCommand($this->callback_event);
        if ($event == $command) {
            $this->function_command = true;
            $callback($this->callback_event);
        }
    }

    /**
     * Обработчик текстовых сообщений (вызывается только если в тексте нет команд)
     *
     * @param      callBack  $callback  Функция обработчик
     */
    public function on($callback){
        $command = $this->getCommand($this->callback_event);
        if (!$this->function_command && !$this->callback_event['fromMe']) {
            $callback($this->callback_event); // fix telegram helper
        }
    }

    /**
     * Обработчик входящих команд от пользователя
     *
     * @param      callBack  $callback  Функция обработчик
     */
    public function callbackQuery($callback){
        if (($this->getCommand($this->callback_event)) !== null && !$this->function_command) {
            $callback($this->callback_event);
        }
    }

    /**
     * Обработчик неизвестных команд
     *
     * @param      string|int|long  $chat_id       Айди чата куда отправляется сообщение
     * @param      string           $message_text  Входящее сообщение
     * @param      int              $id            Айди (Не всегда есть)
     * @param      array            $default_menu  Дефолтное меню
     */
    public function callNotFound($chat_id, $message_text, $id, $default_menu = []){
        $this->sendMessage($chat_id, 'Не смогли распознать Ваш вопрос', null, null, null, $default_menu);
    }

    /**
     * Функция которая в обязательном порядке должна закрывать скрипт
     *
     * Оставлено для совместимости
     */
    public function main(){}

    /**
     * Внутренний обработчик событий, отлавливает входящие сообщения
     *
     * @return     array  ( description_of_the_return_value )
     */
    private function _callback_getEvent() {
        return json_decode(file_get_contents('php://input'), true)['messages'][0];
    }

    public function __call($method, $args){
        if (method_exists($this->bot, $method)) {
            return call_user_func_array(array($this->bot, $method), $args);
        }
        throw new \Exception("Неизвестный метод {$method}", 1);
    }

}
