<?php

namespace App\Services\Notify;

use App\Services\Notify\Type\SmsNotify;

class NotifyFactory
{
    private array $types = [
        'sms' => SmsNotify::class
    ];

    public function createNotifyType(string $type) : Notify
    {
        return new $this->types[$type]();
    }
}
