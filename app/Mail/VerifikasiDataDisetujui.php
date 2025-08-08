<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifikasiDataDisetujui extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $rt;
    public $rw;
    public $loginUrl;

    public function __construct($nama, $rt, $rw, $loginUrl)
    {
        $this->nama = $nama;
        $this->rt = $rt;
        $this->rw = $rw;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Verifikasi Data Berhasil oleh RT')
                    ->view('email.verifikasiDataDisetujui');
    }
}

