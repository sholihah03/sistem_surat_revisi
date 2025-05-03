<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifikasiAkunDitolak extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $alasan;

    public function __construct($nama, $alasan)
    {
        $this->nama = $nama;
        $this->alasan = $alasan;
    }

    public function build()
    {
        return $this->subject('Verifikasi Akun Ditolak')
                    ->view('email.verifikasiAkunDitolak');
    }
}

