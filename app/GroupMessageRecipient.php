<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMessageRecipient extends Model
{
    public function message()
    {
        return $this->belongsTo('App\Message', 'message_id');
    }

    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id');
    }
}
