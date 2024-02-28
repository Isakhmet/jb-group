<?php

namespace App\Console\Commands;

use App\Exports\ExchangeExport;
use App\Mail\CurrencyEmail;
use Illuminate\Console\Command;
use App\Services\Parser\MigParser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Matrix\Exception;

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
        try {
            (new MigParser())->parse();

            $fileName = 'currencies_'. Carbon::now()->format('Y-m-d H:i:s') . '.xlsx';
            Excel::store(new ExchangeExport, $fileName);

            if (file_exists(storage_path('app/'.$fileName))) {
                Mail::to(env('MAIL_TO', 'aziaexchange01@mail.ru'))->send(new CurrencyEmail($fileName));
            }
        }catch (Exception $exception) {
            Log::info($exception);
            dd($exception);
        }
    }
}
