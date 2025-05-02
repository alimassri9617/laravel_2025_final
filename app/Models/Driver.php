<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Driver extends Model
{
    use HasFactory, Notifiable;

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
        'fcm_token',
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
    public function routeNotificationForFcm()
{
    return $this->fcm_token;
}
}
