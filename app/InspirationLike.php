<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspirationLike extends Model
{
    protected $table = 'inspiration_likes';

    public function inspiration()
    {
        return $this->belongsTo('App\Inspiration', 'inspiration_id');
    }

    public function liker()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
