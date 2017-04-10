<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    public function reo()
    {
        return $this->belongsToMany('App\Reo', 'city_reos');
    }

    public function aro()
    {
        return $this->hasMany('App\Branch_aro', 'branch_id', 'branch_id');
    }

    /**
     * Ambil toko yang ada di sebuah city
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store', 'id', 'city_id');
    }

    /**
     * Filtering city dan province disini
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }



}
