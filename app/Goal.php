<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    public function hunt()
    {
        return $this->belongsTo('App\Hunt');
    }

    public function solutions()
    {
        return $this->hasMany('App\Solutions');
    }
}
