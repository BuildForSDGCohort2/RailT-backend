<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['ticket', 'schedule'];

    // Relationship b/w Schedule and ticket
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule');
    }
}
