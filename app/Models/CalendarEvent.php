<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'delivery_id',
        'event_date',
        'event_title',
        'event_description',
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
