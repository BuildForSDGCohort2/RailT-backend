<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['departure_time', 'arriva_time', 'from', 'to', 'carrier'];

    // Relationship b/w Schedule and the carrier
    public function schCarrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier');
    }

    // Relationship b/w Schedule and the station-?from
    public function staFrom()
    {
        return $this->belongsTo(Station::class, 'from');
    }

    // Relationship b/w Schedule and the station-?to
    public function staTo()
    {
        return $this->belongsTo(Station::class, 'to');
    }

    // Relationship b/w Schedule and ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'schedule');
    }
}
