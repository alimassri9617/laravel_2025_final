<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverNotification extends Model
{
    protected $table = 'driver_notifications';

    protected $fillable = [
        'driver_id',
        'title',
        'body',
        'read',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
