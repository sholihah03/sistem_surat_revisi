<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class NotificationService
{
    public static function kirimEmailBrevo($toEmail, $subject, $content)
    {
        $apiKey = env('BREVO_API_KEY');
        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => ['name' => 'Admin RT/RW', 'email' => 'admin@example.com'],
            'to' => [['email' => $toEmail]],
            'subject' => $subject,
            'htmlContent' => $content,
        ]);
        return $response->successful();
    }

    public static function kirimWAWablas($noHP, $pesan)
    {
        $apiKey = env('WABLAS_API_KEY');
        $url = env('WABLAS_URL');
        $response = Http::withHeaders([
            'Authorization' => $apiKey,
        ])->post($url, [
            'phone' => $noHP,
            'message' => $pesan,
        ]);
        return $response->successful();
    }
}

