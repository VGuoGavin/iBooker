<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;

class User extends Authenticatable
{
    use Notifiable;

    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;

    const ROLE_USER = 1;
    const ROLE_AUTHORITY = 10;
    const ROLE_ADMIN = 100;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'private_key',
    ];

    /**
     * Default values for attributes
     *
     * @var array
     */
    protected $attributes = [
        'active' => self::STATUS_ACTIVE,
        'role_id' => self::ROLE_USER,
    ];

    /**
     * Check if user is admin
     *
     * @return boolean
     */
    public function getIsAdminAttribute()
    {
        return $this->role_id == self::ROLE_ADMIN;
    }

    /**
     * Check if user is authority
     *
     * @return boolean
     */
    public function getIsAuthorityAttribute()
    {
        return $this->role_id == self::ROLE_AUTHORITY
            || $this->role_id == self::ROLE_ADMIN;
    }

    public function getKeypairAttribute()
    {
        return sodium_crypto_sign_keypair_from_secretkey_and_publickey(
            $this->private_key,
            $this->public_key
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->generatePrimaryKey();
            $model->generateRSAPair();
        });
    }

    /**
     * Generate RSA key pair
     *
     * @return boolean
     */
    protected function generateRSAPair()
    {
        $keypair = sodium_crypto_sign_keypair();
        print_r($keypair);
        $secret = sodium_crypto_sign_secretkey($keypair);
        print_r($secret);
        $public = sodium_crypto_sign_publickey($keypair);
        print_r($public);

        $this->attributes['public_key'] = $public;
        $this->attributes['private_key'] = $secret;
        if( is_null($this->attributes['public_key']) || is_null($this->attributes['private_key']))
            return false;
        else
            return true;
    }

    /**
     * Generate user id
     *
     * @return boolean
     */
    protected function generatePrimaryKey()
    {
        $id = preg_replace("/[^A-Za-z0-9 ]/", '', Hash::make($this->name . time()));
        $id = substr($id, -32);
        $this->attributes['id'] = $id;
        if( is_null($this->attributes['id']) )
            return false;
        else
            return true;
    }
}
