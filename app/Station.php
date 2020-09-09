<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['s_name', 's_state', 's_town', 's_number'];

    // Relationship b/w Schedule and the station-?from
    public function staSchFroms()
    {
        return $this->hasMany(Schedule::class, 'from');
    }
 
    // Relationship b/w Schedule and the station-?to
    public function staSchTos()
    {
        return $this->hasMany(Schedule::class, 'to');
    }
}
