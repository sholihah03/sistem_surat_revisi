<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifikasiDataDitolak extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $alasan;
    public $loginUrl;


    public function __construct($nama, $alasan, $loginUrl)
    {
        $this->nama = $nama;
        $this->alasan = $alasan;
        $this->loginUrl = $loginUrl;

    }

    public function build()
    {
        return $this->subject('Verifikasi Data Ditolak')
                    ->view('email.verifikasiDataDitolak');
    }
}

