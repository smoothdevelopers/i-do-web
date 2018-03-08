<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeddingLike extends Model
{
    protected $table = 'wedding_likes';
    
    public function wedding()
    {
        return $this->belongsTo('App\Wedding', 'wedding_id');
    }

    public function liker()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
