<?php

namespace App\Http\Controllers;

use App\Services\Notify\NotifyFactory;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function notify(Request $request)
    {
        $code = rand(1000, 9999);
        $phone = $request->get('phone');

        (new NotifyFactory())->createNotifyType('sms')->send(['message' => $code, 'phone' => $phone]);

        return [
            'code' => $code
        ];
    }
}
