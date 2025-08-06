<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiVerifikasiAkun extends Mailable
{
    use Queueable, SerializesModels;

    public $scan;
    public $alamat;
    public $scan_id;

    /**
     * Create a new message instance.
     */
    public function __construct($scan, $alamat, $scan_id)
    {
        $this->scan = $scan;
        $this->alamat = $alamat;
        $this->scan_id = $scan_id;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Permintaan Verifikasi KK Baru')
            ->view('email.notifikasiVerifikasiDataWarga');
    }
}
