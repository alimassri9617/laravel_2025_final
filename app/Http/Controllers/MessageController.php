<?php
// app/Http/Controllers/MessageController.php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'delivery_id' => $request->delivery_id,
            'sender_id' => $request->sender_id,
            'sender_type' => $request->sender_type,
            'message' => $request->message
        ]);

        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }
}