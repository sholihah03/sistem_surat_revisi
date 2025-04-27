<?php
namespace App\Services;

use GuzzleHttp\Client;

class OCRService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OCR_API_KEY'); // API Key dari .env
    }

    public function processImage($imagePath)
    {
        $url = 'https://api.ocr.space/parse/image'; // URL untuk API OCR

        $response = $this->client->post($url, [
            'form_params' => [
                'apikey' => $this->apiKey, // API Key
                'language' => 'eng', // Bahasa yang digunakan untuk OCR, bisa 'ind' untuk bahasa Indonesia
                'file' => fopen($imagePath, 'r') // Mengirim file untuk diproses
            ]
        ]);

        $body = json_decode($response->getBody(), true); // Menyimpan response API dalam bentuk array

        // Mengambil hasil OCR yang diekstrak
        if (isset($body['ParsedResults'][0]['ParsedText'])) {
            return $body['ParsedResults'][0]['ParsedText']; // Mengembalikan teks hasil OCR
        }

        return null; // Jika tidak ada hasil
    }
}
