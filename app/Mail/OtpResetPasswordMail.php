<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $nama;
    public $kodeOtp;
    public $linkVerifikasi;

    /**
     * Create a new message instance.
     */

    public function __construct($nama, $kodeOtp, $linkVerifikasi)
    {
        $this->nama = $nama;
        $this->kodeOtp = $kodeOtp;
        $this->linkVerifikasi = $linkVerifikasi;
    }

    public function build()
    {
        return $this->subject('Kode OTP Reset Password')
            ->view('email.otp-reset-password');
    }

    /**
     * Get the message envelope.
     */


    /**
     * Get the message content definition.
     */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
