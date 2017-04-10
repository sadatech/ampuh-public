<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function sdf(){
        return $this->belongsToMany('App\SDF', 'sdf_brands', 'brand_id', 'sdf_id');
    }


}
