<?php

namespace App\Exports;

use App\Models\ExchangeParser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExchangeExport implements FromCollection
{
    private Collection $collection;

    private const RATE_CURRENCIES = [
        'USD' => [
            'buy' => 0.2,
            'sell' => -0.2
        ]
    ];

    public function __construct()
    {
        $currencies = \App\Models\ExchangeParser::query()
            ->whereNotIn('currency', ['PLN', 'MYR', 'ILS', 'GOLD'])
            ->orderBy('created_at', 'desc')
            ->limit(19)
            ->get(['currency', 'buy', 'sell']);

        $data = [];

        foreach ($currencies as $key => $currency) {
            $data[$key]['currency'] = $currency->currency;
            $data[$key]['buy'] = $currency->buy;
            $data[$key]['sell'] = $currency->sell;

            if (isset(self::RATE_CURRENCIES[$currency->currency])) {
                $data[$key]['buy'] = $currency->buy + self::RATE_CURRENCIES[$currency->currency]['buy'];
                $data[$key]['sell'] = $currency->sell + self::RATE_CURRENCIES[$currency->currency]['sell'];
            }
        }

        $this->collection = collect($data);
        $this->collection->prepend(['currency' => 'Валюты', 'buy' => 'Покупка', 'sell' => 'Продажа']);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection;
    }
}
