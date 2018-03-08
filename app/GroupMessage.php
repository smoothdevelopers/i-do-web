<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function group()
    {
        return $this->belongsTo('App\Group', 'group_id');
    }

    public function recipients()
    {
        return $this->belongsToMany('App\GroupMessageRecipient', 'message_id');
    }
}
