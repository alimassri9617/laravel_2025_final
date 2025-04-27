<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Delivery;

Broadcast::channel('delivery.{deliveryId}', function ($user, $deliveryId) {
    $delivery = Delivery::find($deliveryId);
    if (! $delivery) {
        return false;
    }
    // $user is either a Client or Driver model here
    return $user->id === $delivery->client_id
        || $user->id === $delivery->driver_id;
});
