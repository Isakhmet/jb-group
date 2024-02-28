<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CurrencyEmail extends Mailable
{
    use Queueable, SerializesModels;

    private string $fileName;

    /**
     * CurrencyEmail constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.currency')
            ->subject('Аналитический отчет')
            ->attach(storage_path('app/public/'.$this->fileName), [
                'as' => 'currency.xlsx',
            ])
            ->with([
                'key' => 'value',
            ]);
    }
}
