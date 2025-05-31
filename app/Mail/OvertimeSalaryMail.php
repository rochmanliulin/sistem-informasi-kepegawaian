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
        // Inisialisasi variabel dengan data yang diterima
        // $pdf adalah data PDF yang akan dilampirkan
        // $nama adalah nama pegawai yang akan digunakan dalam email
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
        // set subject dan view untuk email
        return $this->subject('Slip Gaji Lembur')
                    ->view('emails.overtime')
                    ->attachData($this->pdf, 'Slip-Gaji-Lembur.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
