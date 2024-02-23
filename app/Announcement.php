<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    const ONE_YEAR_EXPIRY = 0;
    const ONE_MONTH_EXPIRY = 1;
    const ONE_WEEK_EXPIRY = 2;
    const ONE_DAY_EXPIRY = 3;
    const ONE_HOUR_EXPIRY = 4;

    protected $dates = [
        'posted_at',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'expiry' => self::ONE_YEAR_EXPIRY,
    ];

    public function announcer()
    {
        return $this->belongsTo('App\User', 'announcer_id');
    }

    public function getExpiryStringAttribute()
    {
        return '1 '.['year', 'month', 'week', 'day', 'hour'][$this->expiry];
    }
}
