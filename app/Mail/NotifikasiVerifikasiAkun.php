<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiVerifikasiAkun extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $batasWaktu;
    public $link;

    public function __construct($nama, $batasWaktu, $link)
    {
        $this->nama = $nama;
        $this->batasWaktu = $batasWaktu;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('🔔 Notifikasi Verifikasi Akun Warga Baru')
            ->view('email.notifikasiVerifikasiAkunWarga');
    }
}
