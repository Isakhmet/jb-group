<?php


namespace App\Services\Parser;


use App\Models\ExchangeParser;
use App\Services\Parser\Contracts\AbstractDomDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MigParser extends AbstractDomDocument
{
    private array $selector = [
        'currency' => 'div.informer-additional table tbody tr td.currency',
        'buy' => 'div.informer-additional table tbody tr td.buy',
        'sell' => 'div.informer-additional table tbody tr td.sell',
    ];

    private string $url = 'https://mig.kz/additional#main';

    /**
     * @return array
     */
    public function parse(): array
    {
        $data = Http::get($this->url);
        $currencies = [];

        try {

            $elements = [];
            $title = [];
            $buy = [];
            $sell = [];

            foreach ($this->selector as $element) {
                $elements[] = (new MigParser())->getDocument($data, $element);
            }

            foreach ($elements[0] as $node) {
                $var = preg_replace("/[^A-Z]/", "", $node->nodeValue);
                $title[] = $var;
            }

            foreach ($elements[1] as $node) {
                $var = preg_replace("/[^0-9\.]/", "", $node->nodeValue);
                $buy[] = $var;
            }

            foreach ($elements[2] as $node) {
                $var = preg_replace("/[^0-9\.]/", "", $node->nodeValue);
                $sell[] = $var;
            }


            for ($i = 0; $i < count($title); $i++) {
                $currencies[$title[$i]][] = $buy[$i];
                $currencies[$title[$i]][] = $sell[$i];
            }


        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            echo $exception->getMessage();
        }

        $this->save($currencies);

        return $currencies;
    }

    public function save($data)
    {
        $keys = array_keys($data);

        foreach ($keys as $key) {
            ExchangeParser::query()->create(
                [
                    'currency'      => $key,
                    'buy'              => $data[$key][0],
                    'sell'             => $data[$key][1],
                ]
            );
        }
    }
}
