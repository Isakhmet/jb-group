<?php

namespace App\Http\Controllers;

use App\Services\Notify\NotifyFactory;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function notify(Request $request)
    {
        //(new NotifyFactory())->createNotifyType('sms')->send();
        return [
            'code' => '8803'
        ];
    }
}
