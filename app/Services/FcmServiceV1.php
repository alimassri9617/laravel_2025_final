<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FcmServiceV1
{
    protected $client;
    protected $credentials;
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $jsonKeyPath = storage_path('app/firebase-service-account.json');

        if (!file_exists($jsonKeyPath)) {
            Log::error('Firebase service account JSON key file not found at: ' . $jsonKeyPath);
            throw new \Exception('Firebase service account JSON key file not found.');
        }

        $this->credentials = new ServiceAccountCredentials(
            null,
            $jsonKeyPath,
            ['https://www.googleapis.com/auth/firebase.messaging']
        );

        $this->projectId = env('FIREBASE_PROJECT_ID');

        $this->client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/',
        ]);
    }

    protected function getAccessToken()
    {
        if ($this->accessToken && $this->accessToken['expires_at'] > time()) {
            return $this->accessToken['access_token'];
        }

        $token = $this->credentials->fetchAuthToken();
        $this->accessToken = [
            'access_token' => $token['access_token'],
            'expires_at' => time() + $token['expires_in'] - 60,
        ];

        return $this->accessToken['access_token'];
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            $accessToken = $this->getAccessToken();

            $message = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ],
            ];

            $response = $this->client->post('messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $message,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('FCM sendNotification error: ' . $e->getMessage());
            return null;
        }
    }
}
