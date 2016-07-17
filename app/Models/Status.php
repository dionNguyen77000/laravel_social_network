<?php

namespace Social_Net\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = [
        'body'
    ];

    public function user()
    {
        return $this->belongsTo('Social_Net\Models\User', 'user_id');
    }
    /*This method to distinguish between statuses and replies. If a status which doesn't have the
    parent_id -> it is the status.
    */
    public function scopeNotReply($query)
    {
        return $query->whereNull('parent_id');
    }
    // One status can have many replies to itselve
    public function replies()
    {
        return $this->hasMany('Social_Net\Models\Status','parent_id');
    }

}
