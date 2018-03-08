<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $table = 'vendor_categories';

    public function vendors()
    {
        return $this->hasMany('App\Vendor', 'vendor_category_id');
    }
}
