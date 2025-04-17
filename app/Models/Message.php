<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'sender_id',
        'sender_type',
        'message'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }
}