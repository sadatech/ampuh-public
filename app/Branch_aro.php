<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;

class Branch_aro extends Model
{
    protected $tables = ['branch_aros'];

    public function arina_branch()
    {
        return $this->belongsTo('App\Arina_branch', 'branch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTO('App\User', 'user_id', 'id');
    }
}
