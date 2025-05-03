<?php

namespace App\Http\Controllers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function authenticate()
    {
        // Path to your service account JSON key file
        $keyFilePath = storage_path('app/google-service-account.json');

        // Check if the file exists
        if (!file_exists($keyFilePath)) {
            return response()->json(['error' => 'Service account JSON key file not found.'], 500);
        }

        // Define the scopes required for your application
        $scopes = ['https://www.googleapis.com/auth/cloud-platform'];

        // Instantiate the ServiceAccountCredentials
        $credentials = new ServiceAccountCredentials($scopes, $keyFilePath);

        // Fetch an access token
        $accessToken = $credentials->fetchAuthToken();

        return response()->json([
            'access_token' => $accessToken,
        ]);
    }
}
