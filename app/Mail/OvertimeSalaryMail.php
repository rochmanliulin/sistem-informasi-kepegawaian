<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OvertimeSalaryMail extends Mailable
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
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Slip Gaji Lembur')
                    ->view('emails.overtime') // bukan markdown
                    ->attachData($this->pdf, 'Slip-Gaji-Lembur.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
