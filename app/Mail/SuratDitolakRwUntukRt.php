<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuratDitolakRwUntukRt extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;
    public $alasanPenolakan;

    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;

        if (isset($pengajuan->alasan_penolakan_pengajuan)) {
            $this->alasanPenolakan = $pengajuan->alasan_penolakan_pengajuan;
        } elseif (isset($pengajuan->alasan_penolakan_pengajuan_lain)) {
            $this->alasanPenolakan = $pengajuan->alasan_penolakan_pengajuan_lain;
        } else {
            $this->alasanPenolakan = '-';
        }
    }

    public function build()
    {
        return $this->subject('Pengajuan Surat Warga Ditolak RW')
                    ->view('email.suratDitolakRwUntukRt')
                    ->with([
                        'alasanPenolakan' => $this->alasanPenolakan,
                    ]);
    }
}
