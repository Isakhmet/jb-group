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
            $diff = (new MigParser())->parse();

            if (!$diff) return 0;

            $fileName = 'currencies_'. Carbon::now()->format('Y-m-d H:i:s') . '.xlsx';
            Excel::store(new ExchangeExport, $fileName);

            $emails = [
                'aziaexchange01@mail.ru',
                'trillioner8@mail.ru'
            ];

            if (file_exists(storage_path('app/'.$fileName))) {
                foreach ($emails as $email) {
                    Mail::to($email)->send(new CurrencyEmail($fileName));
                }
            }
        }catch (Exception $exception) {
            Log::info($exception);
            dd($exception);
        }
    }
}
