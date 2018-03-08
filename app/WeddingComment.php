<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeddingComment extends Model
{
    protected $table = 'wedding_comments';

    public function wedding()
    {
        return $this->belongsTo('App\Wedding', 'wedding_id');
    }

    public function commenter()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
