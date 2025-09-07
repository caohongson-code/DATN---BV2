<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/',
            'timeout'  => 30.0,
        ]);
        $this->apiKey = env('GOOGLE_GEMINI_API_KEY');
    }

    public function chat($message)
    {
        $response = $this->client->post("models/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $message]
                        ]
                    ]
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi chưa có câu trả lời.';
    }
}