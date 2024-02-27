<?php

namespace App\Helpers\Parser;

use App\Exports\ExchangeExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class Transformer
{
    public function transform(array $data) :array
    {
        $fileName = 'currencies_'. Carbon::now()->format('Y-m-d H:i:s') . '.xlsx';

        (new ExchangeExport());

        Excel::store(new ExchangeExport, $fileName);

        return $data;
    }
}
