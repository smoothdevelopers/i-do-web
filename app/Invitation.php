<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public function wedding()
    {
        return $this->belongsTo('App\Wedding', 'wedding_id');
    }

    public function invitee()
    {
        return $this->hasOne('App\User', 'invitee_id');
    }

    public function inviter()
    {
        return $this->hasOne('App\User', 'inviter_id');
    }
}
