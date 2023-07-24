<?php

namespace App\Services\Notify\Type;

use App\Services\Notify\Notify;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\Exception;

class SmsNotify implements Notify
{
    public function send(array $params)
    {
        $client = new Client(['base_uri' => 'https://smsc.kz']);
        $branch = auth()->user()->branches()->first();
        $text = sprintf("Код подтверждения для совершения операции: %s \n Обмен валют %s",
                        $params['message'],
                        $branch?->name ?? 'AZIA'
        );
        $request = [
            'login' => config('notify.sms.login'),
            'psw' => config('notify.sms.password'),
            'phones' => $params['phone'],
            'mes' => $text,
            'sender' => $branch?->slug ?? 'AZIA'
        ];

        $notify = new \App\Models\Notify();
        $notify->type = 'sms';
        $notify->to = $params['phone'];
        $notify->info = json_encode($request);

        try {
            $response = $client->get('/sys/send.php', ['query' => $request]);
            $result = json_decode(json_encode($response->getBody()->getContents(), true));
            Log::info($result);
            $notify->response_from_service = $result;
        }catch (Exception $exception) {
            Log::error("SMS {$exception->getMessage()}", $request);
            $notify->response_from_service = $exception->getMessage();
        }

        $notify->save();
    }
}
