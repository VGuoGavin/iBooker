<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hash;

class Signature extends Model
{
    public function signee()
    {
        return $this->belongsTo('App\User');
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->generateSignature();
        });
    }

    protected function generateSignature()
    {
        $message = Hash::make($this->booking_id . time());
        $secret = $this->signee->private_key;
        // 定义要用于生成随机字符串的字符集
        $chars = "ABCDEFGHJKLMNPQRSTUVWXY3456789";
        // 生成随机字符串
        $signature = substr(str_shuffle($chars), 0, 6);
        $this->attributes['message'] = $message;
        $this->attributes['signature'] = $signature;
        if( is_null($this->attributes['signature']) || is_null($this->attributes['message']))
            return false;
        else
            return true;
    }

    public function getIsValidAttribute()
    {
        return sodium_crypto_sign_verify_detached($this->signature, $this->message, $this->signee->public_key);
    }

}
