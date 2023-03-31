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
        $this->urlTire = config('chat-bot.api.back');
    }

    public function getCities()
    {
        return Http::get($this->urlTire . 'common/cities');
    }

    public function getFilters($filters)
    {
        return Http::post($this->urlTire .'catalog/filters', ['city' => $this->city, 'type' => 1, 'filters' => $filters]);
    }

    public function getCarFilters($city, $data = [], $type = 2)
    {
        $query = [
            'params' => json_encode($data),
            'city' => $city,
            'type' => $type
        ];

        return Http::get($this->urlTire . 'catalog/marks', http_build_query($query));
    }

    public function getWheelsCharFilters($city = 'almaty', $data = [])
    {
        $params = [
            'filters' => $data,
            'city' => $city,
            'type' => 2
        ];

        return Http::post($this->urlTire . 'catalog/filters', $params);
    }

    public function getWheelsByChar($city = 'almaty', $data = [])
    {
        $query = [
            'filters' => $data,
            'city' => $city,
            'sorting' => 'new',
        ];

        return Http::get($this->urlTire . 'catalog/wheels', http_build_query($query));
    }

    public function getWheels($city, $data = [])
    {
        $query = [
            'params' => json_encode($data),
            'city' => $city,
            'type' => 2,
            'sorting' => 'new',
        ];

        return Http::get($this->urlTire . 'catalog/marks/wheels/product', http_build_query($query));
    }

    public function getTyresByCar($city, $data = [])
    {
        $query = [
            'params' => json_encode($data),
            'city' => $city,
            'type' => 1,
            'sorting' => 'new',
        ];

        return Http::get($this->urlTire . 'catalog/marks/product', http_build_query($query));
    }
}
