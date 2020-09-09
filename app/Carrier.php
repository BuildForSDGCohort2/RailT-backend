<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = ['c_name', 'c_regNumber', 'c_capacity', 'c_owner'];

    // Relationship b/w carrier and train_service_provider
    public function trainServiceProvider()
    {
        return $this->belongsTo(TrainServiceProvider::class, 'c_owner');
    }

    // Relationship b/w Schedule and the carrier
    public function cSchedules()
    {
        return $this->hasMany(Schedule::class, 'carrier');
    }
}
