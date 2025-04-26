<?php
namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class Login extends Controller
{
    public function logout(Request $request)
    {
        // If you're using Auth system, you can log out like this:
        Auth::logout();
        // Or clear custom session if you're using manual auth
        $request->session()->flush();
        return redirect()->route('home')->with('success', 'Logout successful');
    }
    public function login(Request $request)
    {
        // Validate request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:21',
        ]);
        // Try to find the user as a client
        $client = Client::where("email", $credentials["email"])->first();
        if ($client && Hash::check($credentials['password'], $client->password)) {
            // Login successful
            // Set the client session
            session(['client_id' => $client->id]);
            session(['client_name' => $client->fname . ' ' . $client->lname]);
            return redirect()->route('client.dashboard')->with('success', 'Login successful');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }
}