<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    public function getDataFromTg(Request $request)
    {
        Log::info('tg data:', $request->all());
        return $request->all();
    }
}
