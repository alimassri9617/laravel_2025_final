<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 1) Session‐based clients must pass 'web' + 'auth:client'
        Broadcast::routes([
            'middleware' => ['web', 'auth:client'],
        ]);  
        // 2) Session‐based drivers must pass 'web' + 'auth:driver'
        Broadcast::routes([
            'middleware' => ['web', 'auth:driver'],
        ]);  

        // Load your channel authorization callbacks
        require base_path('routes/channels.php');
    }
}
