<?php

namespace App\Providers;

use App\Models\Delivery;
use App\Policies\DeliveryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Delivery::class => DeliveryPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}