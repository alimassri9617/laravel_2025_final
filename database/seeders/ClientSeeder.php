<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Delivery;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Create a test client
        $client = Client::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'cli@example.com',
            'phone' => '1234567890',
            'password' => 'password123' // In production, use Hash::make()
        ]);

        // Create some deliveries for the client
        Delivery::create([
            'client_id' => $client->id,
            'pickup_location' => '123 Main St, New York',
            'destination' => '456 Elm St, Boston',
            'package_type' => 'medium',
            'delivery_type' => 'standard',
            'delivery_date' => now()->addDays(3),
            'amount' => 15.00,
            'status' => 'pending'
        ]);

        Delivery::create([
            'client_id' => $client->id,
            'pickup_location' => '789 Oak St, Chicago',
            'destination' => '101 Pine St, Seattle',
            'package_type' => 'large',
            'delivery_type' => 'express',
            'delivery_date' => now()->addDays(1),
            'amount' => 30.00,
            'status' => 'in_progress'
        ]);
    }
}