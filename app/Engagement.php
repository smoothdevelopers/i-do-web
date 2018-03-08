<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Engagement extends Model implements HasMedia
{
    use HasMediaTrait;
    
    public function creator()  
    {
        return $this->belongsTo('App\User', 'creator_id');
    }
    public function bride()
    {
        return $this->belongsTo('App\User', 'bride_id');
    }

    public function groom()
    {
        return $this->belongsTo('App\User', 'groom_id');
    }

    protected $fillable = [
        'groom_id',
        'bride_id',
        'creator_id',
        'accepted',
        'is_surprise',
        'suprise_other',
        'proposal_plan',
        'proposal_date',
        'culture',
        'proposal_lat',
        'proposal_lng',
        'proposal_place',
        'phrase',
        'privacy',
    ];

    protected $casts = [
        'is_surprise' => 'boolean',
    ];
}
