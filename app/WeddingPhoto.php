<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\SaveRequest;

class WeddingPhoto extends Model
{
    protected $table = 'wedding_photos';

    public function wedding()
    {
        return $this->belongsTo('App\Wedding', 'wedding_id');
    }
}
