<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Delivery;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
{
    use HandlesAuthorization;

    public function view(Client $client, Delivery $delivery)
    {
        return $client->id === $delivery->client_id;
    }

    public function create(Client $client)
    {
        return true; // Any authenticated client can create deliveries
    }

    public function update(Client $client, Delivery $delivery)
    {
        return $client->id === $delivery->client_id && $delivery->status === 'pending';
    }

    public function delete(Client $client, Delivery $delivery)
    {
        return $client->id === $delivery->client_id && $delivery->status === 'pending';
    }

    public function sendMessage(Client $client, Delivery $delivery)
    {
        return $client->id === $delivery->client_id && in_array($delivery->status, ['accepted', 'in_progress']);
    }
}