<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'password',
        'image'
    ];

    // Relationships
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }
    
}