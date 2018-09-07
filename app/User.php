<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function ownedHunts()
    {
        return $this->hasMany('App\Hunt', 'owner_id');
    }

    public function hunts()
    {
        return $this->belongsToMany('App\Hunt');
    }

    public function solutions()
    {
        return $this->hasMany('App\Solution');
    }
}
