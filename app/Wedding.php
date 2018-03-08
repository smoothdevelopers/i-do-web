<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    protected $table = 'weddings';

    public function groom()
    {
        return $this->hasOne('App\User', 'groom_id');
    }

    public function bride()
    {
        return $this->hasOne('App\User', 'bride_id');
    }

    public function photos()
    {
        return $this->hasMany('App\WeddingPhoto', 'wedding_id');
    }

    public function invitations()
    {
        return $this->hasMany('App\Invitation', 'wedding_id');
    }
}
