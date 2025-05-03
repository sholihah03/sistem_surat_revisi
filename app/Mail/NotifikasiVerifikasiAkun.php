<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiVerifikasiAkun extends Mailable
{
    use Queueable, SerializesModels;

    public $pendaftaran;

    public function __construct(Pendaftaran $pendaftaran)
    {
        $this->pendaftaran = $pendaftaran;
    }

    public function build()
    {
        return $this->subject('Verifikasi Pendaftaran Warga Baru')
                    ->view('email.notifikasiVerifikasiAkunWarga');
    }
}
