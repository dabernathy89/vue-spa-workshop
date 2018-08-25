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
}
