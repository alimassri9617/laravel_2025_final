<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'password',
        'vehicle_type',
        'plate_number',
        'driver_license',
        'price_model',
        'work_area',
        'image',
        
        'approved'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}
