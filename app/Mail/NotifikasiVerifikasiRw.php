<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiVerifikasiRw extends Mailable
{
    use Queueable, SerializesModels;

    public $namaRw;
    public $jenisSurat;
    public $namaWarga;

    public function __construct($namaRw, $jenisSurat, $namaWarga)
    {
        $this->namaRw = $namaRw;
        $this->jenisSurat = $jenisSurat;
        $this->namaWarga = $namaWarga;
    }

    public function build()
    {
        return $this->subject('Verifikasi Surat oleh RW Dibutuhkan')
                    ->view('email.notifikasiVerifikasiSuratRw');
    }
}
