<?php

namespace App\Services;

use GuzzleHttp\Client;

class FcmService
{
    protected $serverKey;
    protected $client;

    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key');
        $this->client = new Client();
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        $message = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ];

        $response = $this->client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => [
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $message,
        ]);

        return json_decode($response->getBody(), true);
    }
}
