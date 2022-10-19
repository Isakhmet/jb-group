<?php

namespace App\Helpers\Telegram;

use GuzzleHttp\Client;

class CBCApi
{
    public function getTiresMark()
    {
        return $this->_call('tires/mark');
    }

    public function getTiresModel($car)
    {
        return $this->_call(
            'tires/model', [
            'car' => $car,
        ]
        );
    }

    public function getTiresYear($car)
    {
        return $this->_call(
            'tires/year', [
            'car' => $car,
        ]
        );
    }

    public function getTiresModify($car)
    {
        return $this->_call(
            'tires/modify', [
            'car' => $car,
        ]
        );
    }

    public function getTiresCharacters($model)
    {
        return $this->_call(
            'tires/characters', [
            'model' => $model,
        ]
        );
    }

    public function getTiresItems($catalog, $width, $height, $radius, $season = '')
    {
        return $this->_call(
            'tires/items', [
            'catalog' => $catalog, // light || commerc || truck || industrial || wheel
            'width'   => $width,
            'height'  => $height,
            'radius'  => $radius,
            'season'  => $season // summer || winter || allseason
        ]
        );
    }

    public function getContacts()
    {
        return $this->_call('tires/contacts');
    }

    public function getActions()
    {
        return $this->_call('tires/stocks');
    }

    private function _call($method, $args = [], $action = 'GET')
    {
        $url = 'https://api.cbc-parts.kz/api/chatbot/' . $method;

        $arrContextOptions = [
            "ssl"  => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: en\r\n",
            ],
        ];

        if ($action == 'GET') {
            $url .= '?' . http_build_query($args);
        }

        $res = file_get_contents($url, false, stream_context_create($arrContextOptions));


        if (($result = json_decode($res, true)) === false) {
            return [];
        }

        if (!isset($result['success']) || $result['success'] !== true) {
            return [];
        }

        return $result['data'];
    }


    public function getPartsSku($sku = '1815766')
    {
        $client = new Client([
                                 'base_uri' => 'https://api.cbc-parts.kz/',
                             ]);
        $results = $client->get('api/warehouse/search', ['query' => ['query' => $sku, 'warehouse' => 'cbc']])
                         ->getBody()
                         ->getContents();

        $results = json_decode($results, JSON_UNESCAPED_UNICODE)['data'];

        foreach ($results as &$result) {
            $result['price'] = $result['stocks'][0]['price'];
            $result['image'] = $result['images'][0] ?? 'https://api.cbc-parts.kz/images/not-found.png';
            $result['link'] = 'https://cbc-parts.kz/product/'.$result['id'];
            $result['characteristic'] = $result['characteristic'] === 'ANALOG' ? 'аналог' : 'оригинал';
        }

        if(count($results) > 5) $results['more-link'] = 'https://cbc-parts.kz/parts/1815766?city=almaty';

        return $results;
    }

    public function getPartsVin($vin)
    {
        $client = new Client(['base_uri' => 'https://api.cbc-parts.kz']);
        $results = $client->post('/api/catalog/all/vin',  ['json' => ['vin' => $vin]])
            ->getBody()
            ->getContents();
        return json_decode($results, JSON_UNESCAPED_UNICODE)['data'];
    }
}
