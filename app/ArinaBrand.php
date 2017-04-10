<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArinaBrand extends Model
{
    protected $table = 'arina_branches';

    protected $fillable = ['region_id', 'cabang'];


    public function aro () {
        return $this->hasMany('App\Branch_aro', 'branch_id', 'id');
    }
}
