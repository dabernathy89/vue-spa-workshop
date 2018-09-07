<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hunt extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function participants()
    {
        return $this->belongsToMany('App\User');
    }

    public function goals()
    {
        return $this->hasMany('App\Goal');
    }

    public function winner()
    {
        return $this->belongsTo('App\User', 'winner_id');
    }

    public function ownedBy($user)
    {
        return $this->owner_id === $user->id ?? null;
    }

    public function includesUser($user)
    {
        return $this->participants->pluck('id')->containsStrict($user->id ?? null);
    }

    public function getIsOpenAttribute()
    {
        return $this->status === 'open';
    }

    public function getIsClosedAttribute()
    {
        return $this->status === 'closed';
    }
}
