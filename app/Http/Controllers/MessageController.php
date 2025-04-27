<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'sender_type' => 'required|in:client,driver',
            'sender_id' => 'required|integer',
            'delivery_id' => 'required|exists:deliveries,id',
        ]);

        // Verify that the sender has permission to send messages for this delivery
        $delivery = \App\Models\Delivery::find($request->delivery_id);
        
        if (!$delivery) {
            return response()->json(['error' => 'Delivery not found'], 404);
        }
        
        if ($request->sender_type == 'client' && $delivery->client_id != $request->sender_id) {
            return response()->json(['error' => 'Unauthorized client  '], 403);
        }
        
        if ($request->sender_type == 'driver' && $delivery->driver_id != $request->sender_id) {
            return response()->json(['error' => 'Unauthorized driver'], 403);
        }

        // Create the message
        $message = Message::create($validated);
        
        // Broadcast the event
        event(new NewMessage($message));
        
        return response()->json(['success' => true, 'message' => $message]);
    }
}