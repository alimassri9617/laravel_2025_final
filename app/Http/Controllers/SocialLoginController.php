<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Client;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Split name into first/last names
            $nameParts = explode(' ', $googleUser->name, 2);
            
            $client = Client::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'fname' => $nameParts[0],
                    'lname' => $nameParts[1] ?? '',
                    'password' => bcrypt(uniqid()),
                    'phone' => '000-000-0000'
                ]
            );

            // Create API token
            $token = $client->createToken('google-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'client' => $client,
                'redirect' => route('client.dashboard')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Google authentication failed',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}