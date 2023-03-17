<?php

namespace App\Helpers\Telegram;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BotRepository
{
    private string $urlTire;
    private string $city;

    public function __construct()
    {
        $this->urlTire = env('F7_API_URL', 'https://api.f7.kz/api/');
    }

    public function getCities()
    {
        return Http::get($this->urlTire . 'common/cities');
    }

    public function getFilters($filters)
    {
        return Http::post($this->urlTire .'catalog/filters', ['city' => $this->city, 'type' => 1, 'filters' => $filters]);
    }
}
