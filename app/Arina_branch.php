<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;

class Arina_branch extends Model
{
	public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
    public function branch_aro()
    {
    	return $this->belongsTo('App\Branch_aro');
    }
}