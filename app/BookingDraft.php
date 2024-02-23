<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingDraft extends Model
{
    public $incrementing = false;

    protected $dates = [
        'committed_at',
        'created_at',
        'updated_at',
        'start_datetime',
        'end_datetime'
    ];

    public function booker()
    {
        return $this->belongsTo('App\User');
    }

    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    public function schedules()
    {
        return $this->hasMany('App\Schedule');
    }

    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'booked_facilities', 'draft_id', 'facility_id')
                    ->wherePivot('room_id', $this->room_id);
    }

    public function bookedFacilities()
    {
        return $this->hasMany('App\BookedFacility', 'draft_id');
    }

    public function booking()
    {
        return $this->hasOne('App\Booking', 'draft_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->generateID();
        });
    }

    protected function generateID()
    {
        // Format: BD-
        $id = "BD-" . preg_replace("/0.(\d+) (\d+)/", "$2$1", microtime());

        $this->attributes['id'] = $id;
        if( is_null($this->attributes['id']) )
            return false;
        else
            return true;
    }

    public function getTrimmedIdAttribute()
    {
        return preg_replace('/BD\-(.*)/','$1',$this->id);
    }

    public function getIsCompleteAttribute()
    {
        return
            true
            && $this->room_id
            && $this->start_datetime
            && $this->end_datetime
            && $this->purpose;
    }

    public function getCompletionAttribute()
    {
        $completion = [
            'percent' => 0,
            'messages' => [],
        ];
        $incomplete_count = 0;
        array_push($completion['messages'], [
            'status' => isset($this->room_id) ? 'complete' : 'incomplete',
            'message' => isset($this->room_id) ? 'Room chosen' : 'Which room are you gonna pick?'
        ]);
        $incomplete_count += !isset($this->room_id);
        array_push($completion['messages'], [
            'status' => isset($this->start_datetime) ? 'complete' : 'incomplete',
            'message' => isset($this->start_datetime) ? 'Start date time chosen' : 'When is the room booked?'
        ]);
        $incomplete_count += !isset($this->start_datetime);
        array_push($completion['messages'], [
            'status' => isset($this->end_datetime) ? 'complete' : 'incomplete',
            'message' => isset($this->end_datetime) ? 'End date time chosen' : 'When is the booking finished?'
        ]);
        $incomplete_count += !isset($this->end_datetime);
        array_push($completion['messages'], [
            'status' => isset($this->purpose) ? 'complete' : 'incomplete',
            'message' => isset($this->purpose) ? 'Booking purpose set' : 'What is the booking for?'
        ]);
        $incomplete_count += !isset($this->purpose);
        array_push($completion['messages'], [
            'status' => !$this->facilities->isEmpty() ? 'complete' : 'warning',
            'message' => !$this->facilities->isEmpty() ? 'Required facilities chosen' : 'Are you sure no facilities are required?'
        ]);
        $completion['percent'] = (count($completion['messages']) - $incomplete_count)/count($completion['messages']) * 100;
        return $completion;
    }
}
