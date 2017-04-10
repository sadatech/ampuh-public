<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    //
        use SoftDeletes;

    /**
     * Filtering Account
     *
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }

    public function store()
    {
        return $this->belongsTo('App\Store', 'id', 'account_id');
    }
}
