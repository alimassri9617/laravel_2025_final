<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'payment_method',
        'transaction_id',
        'amount',
        'currency',
        'status'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}