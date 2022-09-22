<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    public function getDataFromTg(Request $request)
    {
        return $request->all();
    }
}
