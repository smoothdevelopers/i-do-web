<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    public function vendorCategory()
    {
        return $this->belongsTo('App\VendorCategory', 'vendor_category_id');
    }
}
