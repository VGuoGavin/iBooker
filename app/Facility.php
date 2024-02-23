<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public $timestamps = false;

    public function rooms()
    {
        return $this->belongsToMany('App\Room', 'room_facilities', 'facility_id', 'room_id');
    }
}
