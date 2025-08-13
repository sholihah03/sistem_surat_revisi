<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiVerifikasiDataWarga extends Mailable
{
    use Queueable, SerializesModels;

    public $scan;
    public $alamat;
    public $scan_id;
    public $rt;

    /**
     * Create a new message instance.
     */
    public function __construct($scan, $alamat, $scan_id, $rt)
    {
        $this->scan = $scan;
        $this->alamat = $alamat;
        $this->scan_id = $scan_id;
        $this->rt = $rt;

    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Permintaan Verifikasi Data Warga')
            ->view('email.notifikasiVerifikasiDataWarga');
    }
}
