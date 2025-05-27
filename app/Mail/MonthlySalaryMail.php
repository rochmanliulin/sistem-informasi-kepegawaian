<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthlySalaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $nama;

    public function __construct($pdf, $nama)
    {
        $this->pdf = $pdf;
        $this->nama = $nama;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Slip Gaji Bulanan')
                    ->view('emails.monthly')
                    ->attachData($this->pdf, 'Slip-Gaji-Bulanan.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

}
