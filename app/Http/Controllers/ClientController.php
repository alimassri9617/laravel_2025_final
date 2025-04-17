<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('client.login');
    }
    

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $client = Client::where('email', $request->email)
                      ->where('password', $request->password) // In production, use Hash::check()
                      ->first();

        if ($client) {
            Session::put('client_id', $client->id);
            Session::put('client_name', $client->fname . ' ' . $client->lname);
            return redirect()->route('client.dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    // Handle logout
    public function logout()
    {
        Session::forget(['client_id', 'client_name']);
        return redirect()->route('client.login');
    }

    // Show dashboard
    public function dashboard()
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $clientId = Session::get('client_id');
        $deliveries = Delivery::where('client_id', $clientId)->get();

        return view('client.dashboard', [
            'clientName' => Session::get('client_name'),
            'deliveries' => $deliveries
        ]);
    }

    // Show all deliveries
    public function deliveries()
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $clientId = Session::get('client_id');
        $deliveries = Delivery::where('client_id', $clientId)->get();

        return view('client.deliveries', [
            'clientName' => Session::get('client_name'),
            'deliveries' => $deliveries
        ]);
    }

    // Show single delivery
    public function showDelivery($id)
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $delivery = Delivery::findOrFail($id);
        
        // Basic authorization
        if ($delivery->client_id != Session::get('client_id')) {
            abort(403, 'Unauthorized access');
        }

        return view('client.delivery-details', [
            'clientName' => Session::get('client_name'),
            'delivery' => $delivery
        ]);
    }

    // Show new delivery form
    public function createDelivery()
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        return view('client.new-delivery', [
            'clientName' => Session::get('client_name')
        ]);
    }

    // Store new delivery
    public function storeDelivery(Request $request)
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $request->validate([
            'pickup_location' => 'required',
            'destination' => 'required',
            'package_type' => 'required|in:small,medium,large,extra_large',
            'delivery_type' => 'required|in:standard,express,overnight',
            'delivery_date' => 'required|date',
            'special_instructions' => 'nullable'
        ]);

        $delivery = new Delivery();
        $delivery->client_id = Session::get('client_id');
        $delivery->pickup_location = $request->pickup_location;
        $delivery->destination = $request->destination;
        $delivery->package_type = $request->package_type;
        $delivery->delivery_type = $request->delivery_type;
        $delivery->delivery_date = $request->delivery_date;
        $delivery->special_instructions = $request->special_instructions;
        $delivery->status = 'pending';
        $delivery->amount = $this->calculateAmount($request->package_type, $request->delivery_type);
        $delivery->save();

        return redirect()->route('client.deliveries')->with('success', 'Delivery created successfully!');
    }

    // Calculate delivery amount
    private function calculateAmount($packageType, $deliveryType)
    {
        $basePrices = [
            'small' => 5.00,
            'medium' => 10.00,
            'large' => 15.00,
            'extra_large' => 25.00
        ];

        $multipliers = [
            'standard' => 1.0,
            'express' => 1.5,
            'overnight' => 2.0
        ];

        return $basePrices[$packageType] * $multipliers[$deliveryType];
    }
}