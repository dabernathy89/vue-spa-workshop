<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $guarded = [];

    public function goal()
    {
        return $this->belongsTo('App\Goal');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
