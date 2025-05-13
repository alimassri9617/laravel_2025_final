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
        'approved',
        'is_available'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

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

    public function routeNotificationForFcm()
{
    return $this->fcm_token;
}

    public function calendarEvents()
    {
        return $this->morphMany(\App\Models\CalendarEvent::class, 'user');
    }
}
