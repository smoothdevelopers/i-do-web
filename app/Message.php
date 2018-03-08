<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message',
        'sent_at',
        'received_at',
        'seen_at',
    ];

    protected $dates = [
        'sent_at',
        'received_at',
        'seen_at',
    ];


    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id');
    }

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
}
