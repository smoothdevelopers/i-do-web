<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageBlacklist extends Model
{
    public function blocked()
    {
        return $this->belongsTo('App\User', 'blocked_id');
    } 

    public function blocker()
    {
        return $this->belongsTo('App\User', 'blocker_id');
    }

    public function scopeBlocked($query, $user1, $user2)
    {
        return $query->where(function ($query) use ($user1, $user2) {
                $query->where('blocked_id', '=', $user1->id)
                      ->where('blocker_id', '=', $user2->id);
        })->orWhere(function ($query) use ($user1, $user2) {
                $query->where('blocker_id', '=', $user1->id)
                      ->where('blocked_id', '=', $user2->id);
        })->first();
    }
}
