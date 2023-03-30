<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Services\BotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function main(Request $request, $handler)
    {
        $this->telegramLog($request->all());
        $validator = Validator::make(['handler' => $handler], [
            'handler' => 'required|in:telegram,whatsapp'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => $validator->errors()->messages()], 400);
        }

        (new BotService($handler))->start();
    }

    public function telegramLog($log)
    {
        if(isset($log['callback_query']['message']['reply_markup'])) {
            unset($log['callback_query']['message']['reply_markup']);
        }

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
