<?php

namespace App\Exports;

use App\Models\ExchangeParser;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExchangeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ExchangeParser::all();
    }
}
