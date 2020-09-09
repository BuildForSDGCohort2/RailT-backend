<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainServiceProvider extends Model
{
    protected $fillable = ['name', 'about', 'address'];

    // Relationship b/w carrier and train_service_provider
    public function tspCarriers()
    {
        return $this->hasMany(Carrier::class, 'c_owner');
    }
}
