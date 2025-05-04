<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition()
    {
        return [
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => 'password', // Will be hashed by model mutator
            'vehicle_type' => $this->faker->randomElement(['car', 'motorcycle', 'bicycle', 'van']),
            'plate_number' => strtoupper($this->faker->bothify('??###??')),
            'driver_license' => strtoupper($this->faker->bothify('DL#######')),
            'price_model' => $this->faker->randomElement(['fixed', 'per_km', 'per_hour']),
            'work_area' => $this->faker->city(),
            'image' => 'drivers/' . $this->faker->image('public/images/drivers', 400, 300, null, false),
            'approved' => $this->faker->boolean(100), // 80% chance approved
        ];
    }
}
