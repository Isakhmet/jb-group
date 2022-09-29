<?php

namespace App\Http\Controllers\Test;

use App\Helpers\Telegram\Bot;
use App\Helpers\Telegram\CBCApi;
use App\Helpers\Telegram\Handlers\TelegramHandler;
use App\Helpers\Telegram\Handlers\WhatsappHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\TelegramWebhookRequest;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function main(Request $request, $handler)
    {
        //$this->telegramLog($request->all());
        $validator = Validator::make(['handler' => $handler], [
            'handler' => 'required|in:telegram,whatsapp'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => $validator->errors()->messages()], 400);
        }

        $config = [
            'stats_iblock_id' => 2,
            'votes_iblock_id' => 1,
            'logger' => true,
            'handler' => $handler,
            'handlers' => config('bots.handlers')
        ];

        $bot = new Bot($config);

        $bot->callApi = new CBCApi();

        $bot->command('start', function ($message) use ($bot) {
            $answer = "Привет! Я бот F7. Нахожу запчасти и шины для вашего автомобиля.\nЧтобы подобрать шины на свою машину, выберите удобный для вас способ, по характеристикам или по марке автомобиля.";

            $chat_id = $bot->chatId($message);
            $bot->lastName($bot->getUsername($message));
            if ($bot->isWhatsapp()) {
                $answer .= "\n\n*Для выбора команды необходимо написать слэш (/), а потом цифру функции, которую хотите выполнить*.\n Например: */1*";
            }
            $bot->sendMessage($chat_id, $answer, null, false, null, [
                [['text' => 'Шины', 'callback_data' => 'tires']],
                [['text' => 'Контакты', 'callback_data' => 'contacts']],
                [['text' => 'Акции', 'callback_data' => 'actions']],
            ]);
        });

        /*
         Команды присланные через кнопки
        */
        $bot->callbackQuery(function ($message) use ($bot) {

            /**
             * Установка имени пользователя
             */
            $bot->lastName($bot->getUsername($message));

            // Айди чата
            $chat_id = $bot->chatId($message);

            // Айди последнего сообщения
            $message_id = $bot->getMessageId($message);

            // Команда для бота
            $commandRaw = trim($bot->getCommand($message));

            $command = explode(':', $commandRaw)[0];

            (new TelegramBotService())->botCalledQuery($bot, $command, $commandRaw, $chat_id, $message_id, $message);
        });

        /*
           Обработчик текстовых входящих сообщений
         */
        $bot->on(function ($message) use ($bot){

            if (is_null($message)) {
                return false;
            }

            $bot->lastName($bot->getUsername($message));

            // Айди чата
            $chat_id = $bot->chatId($message);

            // айди сообщения
            $message_id = $bot->getMessageId($message);

            // получаем текст входящего сообщения
            $message_text = $bot->getText($message);

            // достаём командку по кешу
            $commandRaw = $bot->getCommandCache($chat_id, $message_text);

            $command = explode(':', $commandRaw)[0];

            (new TelegramBotService())->botCalledQuery($bot, $command, $commandRaw, $chat_id, $message_id, $message, $message_text, true);
        });

        $bot->main();
    }

    public function getDataFromTg(TelegramWebhookRequest $request)
    {
        $this->main($request);
        //$this->telegramLog($request->all());
        /*$key = $request->has('callback_query') ? 'callback_query.data' : 'message.text';
        $keys = explode('.', $key);
        $params = $request->get($keys[0]);
        $command = $params[$keys[1]];
        $args = [];

        if(Str::contains($command, ':')) {
            [$command, $args] = explode(':', $command);
        }

        (new TelegramBotService(new Telegram()))->runCommand($command, $params, $args);*/

        return $request->all();
    }

    public function telegramLog($log)
    {
        $answer = json_encode($log, JSON_UNESCAPED_UNICODE);

        return Telegram::sendMessage(
            [
                'chat_id'    => '576051075',
                'text'       => $answer,
                'parse_mode' => 'html',
            ]
        );
    }
}
