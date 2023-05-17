<?php

namespace App\Services\Notify\Type;

use App\Services\Notify\Notify;
use GuzzleHttp\Client;

class SmsNotify implements Notify
{
    private array $phones;

    private string $text;

    public function __construct($phones, $text)
    {
        $this->phones = $phones;
        $this->text = $text;
    }

    public function send()
    {
        $client = new Client(['base_uri' => 'https://smsc.kz']);
        $request = [
            'login' => config('notify.sms.login'),
            'psw' => config('notify.sms.password'),
            'phones' => $this->phones,
            'mes' => $this->text,
        ];

        $response = $client->get('/sys/send.php', ['query' => $request]);
        $result = json_decode(json_encode($response->getBody()->getContents(), true));
        dd($result);
    }
}
