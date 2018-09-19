<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id' => 'integer',
        'goal_id' => 'integer',
    ];

    protected $appends = ['imageSrc'];

    public function goal()
    {
        return $this->belongsTo('App\Goal');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getImageSrcAttribute()
    {
        return str_contains($this->image, 'http') ? $this->image : asset('storage/' . $this->image);
    }
}
