<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'viber', 'telegram','whatsapp','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'referer_id',
        'role',
        'api_token',
        'api_secret',
//        'verified',
        'created_at',
        'updated_at',
        'email_verified_at',
//        'verified_send',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified'=>'boolean',
        'verified_send'=>'boolean',
    ];
    public function referer()
    {
      return $this->belongsTo('App\User','referer_id');
    }
    public function referals()
    {
      return $this->hasMany('App\User','referer_id','id');
    }
    public function orders()
    {
      return $this->hasMany('App\Exchange');
    }
}
