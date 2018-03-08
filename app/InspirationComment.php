<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspirationComment extends Model
{
    protected $table = 'inspiration_comments';

    public function inspiration()
    {
        return $this->belongsTo('App\Inspiration', 'inspiration_id');
    }

    public function commenter()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
