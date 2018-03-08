<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngagementLike extends Model
{
    protected $table = 'engagement_likes';

    public function engagement()
    {
        return $this->belongsTo('App\Engagement', 'engagement_id');
    }

    public function liker()
    {
        return $this->hasOne('App\User', 'user_id');
    }
}
