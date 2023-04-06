<?php

namespace App\Helpers\Telegram;

use Illuminate\Support\Facades\Http;

class BotRepository
{
    private string $api;
    private string $partsApi;
    private string $city;

    public function __construct()
    {
        $this->api = config('chat-bot.api.back');
        $this->partsApi = config('chat-bot.api.parts');
    }

    public function getCities()
    {
        return Http::get($this->api . 'common/cities');
    }

    public function getFilters($filters)
    {
        return Http::post($this->api .'catalog/filters', ['city' => $this->city, 'type' => 1, 'filters' => $filters]);
    }

    public function getCarFilters($city, $data = [], $type = 1)
    {
        $query = [
            'params' => json_encode($data),
            'city' => $city,
            'type' => $type
        ];

        return Http::get($this->api . 'catalog/marks', http_build_query($query));
    }

    public function getCharFilters($city, $data = [], $type = 1)
    {
        $params = [
            'filters' => $data,
            'city' => $city,
            'type' => $type
        ];

        return Http::post($this->api . 'catalog/filters', $params);
    }

    public function getProductsByChar($productType, $city = 'almaty', $data = [])
    {
        $query = [
            'filters' => $data,
            'city' => $city,
            'sorting' => 'new',
        ];

        return Http::get($this->api . "catalog/$productType", http_build_query($query));
    }

    public function getWheels($city, $data = [])
    {
        $query = [
            'params' => json_encode($data),
            'city' => $city,
            'type' => 2,
            'sorting' => 'new',
        ];

        return Http::get($this->api . 'catalog/marks/wheels/product', http_build_query($query));
    }

    public function getTyresByCar($city, $data = [])
    {
        $query = [
            'params' => json_encode($data),
            'city' => $city,
            'type' => 1,
            'sorting' => 'new',
        ];

        return Http::get($this->api . 'catalog/marks/product', http_build_query($query));
    }

    public function getPartsSku($sku, $city)
    {
        $query = [
            'query' => $sku,
            'warehouse' => 'cbc',
            'city' => $city
        ];

        $results = Http::get($this->partsApi . 'api/warehouse/search', http_build_query($query))->json();

        foreach ($results['data'] as &$result) {
            $result['price'] = $result['stocks'][0]['price'];
            $result['image'] = $result['images'][0] ?? $this->partsApi.'/images/not-found.png';
            $result['link'] = config('chat-bot.api.parts-front').'/product/'.$result['id'];
            $result['characteristic'] = $result['characteristic'] === 'ANALOG' ? 'Аналог' : 'Оригинал';
        }

        if (count($results) > 5) {
            $results = array_slice($results, 0, 5);
        }

        $results['data']['more-link'] = config('chat-bot.api.parts-front')."/parts/$sku?city=$city";

        return $results;
    }

    public function getPartsVin($vin)
    {
        return Http::post($this->partsApi . 'api/catalog/all/vin', ['vin' => $vin]);
    }
}
