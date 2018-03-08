<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngagementPhoto extends Model
{
    protected $table = 'engagement_photos';

    public function engagement()
    {
        return $this->belongsTo('App\Engagement', 'engagement_id');
    }
}
