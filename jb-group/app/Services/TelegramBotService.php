<?php


namespace App\Services;


use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotService
{
    protected const DEFAULT_MESSAGE = 'Привет! Я бот F7. Нахожу запчасти и шины для вашего автомобиля.\nЧтобы подобрать шины на свою машину, выберите удобный для вас способ, по характеристикам или по марке автомобиля.';

    protected const DEFAULT_KEYBOARD = [
        [['text' => 'Шины', 'callback_data' => 'tires']],
        [['text' => 'Контакты', 'callback_data' => 'contacts']],
        [['text' => 'Акции', 'callback_data' => 'actions']],
    ];

    protected Telegram $bot;

    public function __construct(Telegram $bot)
    {
        $this->bot = $bot;
    }

    public function runCommand(string $command, array $params)
    {
        $command = preg_replace('/[^A-Za-z0-9\-]/', '', $command);

        if (method_exists(self::class, $command)) {
            $this->{$command}($params);
        }
    }

    public function start($params)
    {
        $this->bot::sendMessage(
            [
                'chat_id'      => $params['chat']['id'],
                'text'         => self::DEFAULT_MESSAGE,
                'parse_mode'   => 'html',
                'reply_markup' => Keyboard::make(['inline_keyboard' => self::DEFAULT_KEYBOARD,]),
            ]
        );
    }

    public function contacts($params)
    {
        $this->bot::sendMessage(
            [
                'chat_id'      => $params['message']['chat']['id'],
                'text'   => 'Выберите город:',
                'parse_mode'   => 'html',
                'reply_markup' => Keyboard::make(
                    [
                        'inline_keyboard' => [
                            [['text' => 'Алматы', 'callback_data' => 'contacts:almaty']],
                            [['text' => 'Астана', 'callback_data' => 'contacts:astana']],
                        ],
                    ]
                ),
            ]
        );
    }
}
