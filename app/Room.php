<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hash;

class Room extends Model
{
    // Create custom primary key
    public $incrementing = false;
    protected $keyType = 'string';


    protected $attributes = [
        'active' => true
    ];

    // Attributes

    public function building()
    {
        return $this->belongsTo('App\Building');
    }

    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'room_facilities', 'room_id', 'facility_id');
    }

    public function types()
    {
        return $this->belongsToMany('App\RoomType', 'room_room_type', 'room_id', 'type_id');
    }

    /**
     * Boot function
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->generatePrimaryKey();
        });
    }

    protected function generatePrimaryKey()
    {
        $id = preg_replace("/[^A-Za-z0-9 ]/", '', Hash::make($this->name . microtime()));
        $id = substr($id, -8);
        $this->attributes['id'] = $id;
        if( is_null($this->attributes['id']) )
            return false;
        else
            return true;
    }
}
