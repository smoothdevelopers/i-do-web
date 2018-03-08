<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspiration extends Model
{
    public function comments()
    {
        return $this->hasMany('App\InspirationComment', 'inspiration_id');
    }

    public function likes()
    {
        return $this->hasMany('App\InpirationLikes', 'inspiration_id');
    }
}
