<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('private-delivery.{deliveryId}', function ($user, $deliveryId) {
    // Check if the authenticated user is either the client or the driver of the delivery
    $delivery = Delivery::find($deliveryId);
    if (!$delivery) {
        return false;
    }

    // Assuming $user is either a Client or Driver model instance
    // Adjust this check based on your authentication setup

    // Check if user is client and owns the delivery
    if ($user instanceof \App\Models\Client && $delivery->client_id === $user->id) {
        return ['id' => $user->id, 'type' => 'client'];
    }

    // Check if user is driver and accepted the delivery
    if ($user instanceof \App\Models\Driver && $delivery->driver_id === $user->id) {
        return ['id' => $user->id, 'type' => 'driver'];
    }

    return false;
});
