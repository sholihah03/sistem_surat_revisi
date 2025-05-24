<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PengajuanSurat;

class SuratDisetujuiRwUntukRt extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function build()
    {
        return $this->subject('Surat Warga Telah Disetujui RW')
            ->view('email.suratDisetujuiRwUntukRt');
    }
}
