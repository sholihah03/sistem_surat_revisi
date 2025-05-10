<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPengajuanSuratKeRT extends Mailable
{
    use Queueable, SerializesModels;

    public $namaWarga;
    public $jenisPengajuan;

    public function __construct($namaWarga, $jenisPengajuan = null)
    {
        $this->namaWarga = $namaWarga;
        $this->jenisPengajuan = $jenisPengajuan;
    }

    public function build()
    {
        return $this->subject('Pengajuan Surat Baru dari Warga')
                    ->view('email.notifikasiPengajuanSurat');
    }
}
