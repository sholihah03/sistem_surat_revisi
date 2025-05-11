<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KirimOTPYangKartuKeluargaSudahAda extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $nama;

    public function __construct($otp, $nama)
    {
        $this->otp = $otp;
        $this->nama = $nama;
    }


    public function build()
    {
        return $this->subject('Kode OTP Verifikasi Akun')
                    ->view('email.kirimOtpYangKkSudahAda');
    }
}
