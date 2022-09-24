<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Requests\TelegramWebhookRequest;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function getDataFromTg(TelegramWebhookRequest $request)
    {
        $this->telegramLog($request->all());
        $key = $request->has('callback_query') ? 'callback_query.data' : 'message.text';
        $keys = explode('.', $key);
        $params = $request->get($keys[0]);
        $command = $params[$keys[1]];

        (new TelegramBotService(new Telegram()))->runCommand($command, $params);

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
