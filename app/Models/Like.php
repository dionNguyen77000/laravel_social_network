<?php

namespace Social_Net\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likeable';

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('Social_Net\Models\User', 'user_id');
    }

}