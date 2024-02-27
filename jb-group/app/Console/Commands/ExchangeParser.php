<?php

namespace App\Console\Commands;

use App\Exports\ExchangeExport;
use App\Helpers\Parser\Transformer;
use Illuminate\Console\Command;
use App\Services\Parser\MigParser;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExchangeParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parser exchanges web sites';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = 'currencies_'. Carbon::now()->format('Y-m-d H:i:s') . '.xlsx';

        new ExchangeExport();

        /*$model = \App\Models\ExchangeParser::query()
            ->orderBy('currency', 'desc')
            ->limit(23)
            ->get(['currency', 'buy', 'sell']);
        dd($model);

        new ExchangeExport();*/
dd('asd');
        //return Excel::store(new ExchangeExport, $fileName);
        /*$currencies = (new MigParser())->parse();



        $currencies = (new Transformer())->transform($currencies);*/
    }
}
