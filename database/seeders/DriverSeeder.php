<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run()
    {
        // Create 10 drivers using the factory
        Driver::factory()->count(10)->create();
    }
}
