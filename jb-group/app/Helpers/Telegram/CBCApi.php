<?php

namespace App\Helpers\Telegram;

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
}
