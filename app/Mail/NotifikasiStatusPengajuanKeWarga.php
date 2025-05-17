<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiStatusPengajuanKeWarga extends Mailable
{
    use Queueable, SerializesModels;

    public $namaWarga;
    public $jenisSurat;
    public $status;
    public $alasanPenolakan;
    public $linkDetail;

    public function __construct($namaWarga, $jenisSurat, $status, $alasanPenolakan = null, $linkDetail = '#')
    {
        $this->namaWarga = $namaWarga;
        $this->jenisSurat = $jenisSurat;
        $this->status = $status;
        $this->alasanPenolakan = $alasanPenolakan;
        $this->linkDetail = $linkDetail;
    }

    public function build()
    {
        return $this->subject('Status Pengajuan Surat Anda')
                    ->view('email.statusPengajuanKeWarga');
    }
}
