<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function getDataFromTg(Request $request)
    {
        $this->telegramLog($request->all());

        $message = $request->get('message') ?? $request->get('callback_query');
        $answer = "Привет! Я бот F7. Нахожу запчасти и шины для вашего автомобиля.\nЧтобы подобрать шины на свою машину, выберите удобный для вас способ, по характеристикам или по марке автомобиля.";

        if($message) {

            switch ($message['text']) {
                case '/start' :

                $this->sendMessage(
                    [
                        'chat_id' => $message['chat']['id'],
                        'text' => $answer,
                        'parse_mode' => 'html',
                        'reply_markup' => Keyboard::make(
                            [
                                'inline_keyboard' => [
                                    [['text' => 'Шины', 'callback_data' => 'tires']],
                                    [['text' => 'Контакты', 'callback_data' => 'contacts']],
                                    [['text' => 'Акции', 'callback_data' => 'actions']],
                                ],
                            ]
                        )
                    ]
                );

                break;

                case 'contacts' :
                    $this->sendMessage(
                        [
                            'chat_id' => $message['chat']['id'],
                            'parse_mode' => 'html',
                            'reply_markup' => Keyboard::make(
                                [
                                    'inline_keyboard' => [
                                        [['text' => 'Алматы', 'callback_data' => 'contacts:almaty']],
                                        [['text' => 'Астана', 'callback_data' => 'contacts:astana']],
                                    ],
                                ]
                            )
                        ]
                    );

            }
        }

        return $request->all();
    }

    public function sendMessage($array)
    {
        //$tg = new Telegram();
        return Telegram::sendMessage($array);
    }

    public function telegramLog($log)
    {
        $answer = json_encode($log, JSON_UNESCAPED_UNICODE);

        return Telegram::sendMessage(
            [
                'chat_id' => '576051075',
                'text' => $answer,
                'parse_mode' => 'html',
            ]
        );
    }
}
