<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngagementComment extends Model
{
    protected $table = 'engagement_comments';

    public function engagement()
    {
        return $this->belongsTo('App\Engagement', 'engagement_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }

}
