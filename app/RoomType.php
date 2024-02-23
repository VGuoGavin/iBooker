<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    public $timestamps = false;

    public function rooms()
    {
        return $this->belongsToMany('App\Room');
    }
}
