<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'fb_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function weddingAsGroom()
    {
        return $this->hasOne('App\Wedding', 'groom_id');
    }

    public function weddingAsBride()
    {
        return $this->hasOne('App\Wedding', 'bride_id');
    }

    public function engagementAsGroom()
    {
        return $this->hasOne('App\Engagement', 'groom_id');
    }

    public function messages() 
    {
        return $this->belongsToMany('App\Message');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function engagementAsBride()
    {
        return $this->hasOne('App\Engagement', 'bride_id');
    }

    public function invitationsAsInvitee()
    {
        return $this->hasMany('App\Invitation', 'invitee_id');
    }


    public function invitationsAsInviter()
    {
        return $this->hasMany('App\Invitation', 'inviter_id');
    }

}
