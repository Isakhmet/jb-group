<?php

return [
    'handlers' => [
        'telegram' => [
            'handler' => \App\Helpers\Telegram\Handlers\TelegramHandler::class,
            'token' => env('TELEGRAM_BOT_TOKEN', '5354690402:AAGB6mQwXQjNhr__UzTHKFtYq3v2WEUekv0'),
            'list_id' => 6
        ],
        'whatsapp' => [
            'handler' => \App\Helpers\Telegram\Handlers\WhatsappHandler::class,
            'token' => env('WHATSAPP_BOT_TOKEN','ev98126l5ykreu3q'),
            'instance' => '411223',
            'list_id' => 5,
            'operators' => [
                '77778511931@us.c'
            ],
            'button_type' => 0,
            'buttons_always_shown' => ['В меню','Назад']
        ]
    ]
];
