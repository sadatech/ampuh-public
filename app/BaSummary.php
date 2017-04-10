<?php

namespace App;

use App\Filter\QueryFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaSummary extends Model
{
    protected $fillable = ['ba_id', 'store_id', 'brand_id', 'month', 'year', 'store_count_static'];


    /**
     * Configuration Query
     *
     * @param $query
     * @param QueryFilters $filters
     * @param $month
     * @param $year
     */
    public function scopeConfigurationData($query, QueryFilters $filters, $month, $year)
    {
        $filters->apply($query->with('ba.agency', 'ba.arinaBrand', 'store.city', 'store.reo.user', 'store.region', 'store.account', 'brand')
                ->whereHas('store', function ($query) {
                    return $query->whereNull('deleted_at');
                })
                ->withCount('storeCount as store')
                ->where('month', $month)
                ->where('year', $year));
    }

    /**
     * One to one to  Ba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ba()
    {
        return $this->belongsTo('App\Ba');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    /**
     * One to One to brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    /**
     * Count how many store one ba work at
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storeCount()
    {
        return $this->hasMany('App\BaStore', 'ba_id', 'ba_id');
    }

    /**
     * Constraint for reo to only see their area configuration only
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeShowForReo($query, $id)
    {
        return $query->whereHas('store.reo', function ($query) use ($id) {
            return $query->where('reos.user_id', $id);
        });
    }

    public function scopeShowForAro($query, $id)
    {
        return $query->whereHas('store.city.aro', function ($query) use ($id) {
            return $query->where('user_id', $id);
        });
    }

    /**
     * Find the vacant ba rotation
     *
     * @param $query
     * @param $storeId
     * @return mixed
     */
    public function scopeFindRotationReplacement($query, $storeId)
    {
        return $query->where('store_id', $storeId)
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->where('ba_id', 0);
    }

    /**
     * Detect if there is empty store in some brand
     *
     * @param $query
     * @param $storeId
     * @param $brandId
     * @return mixed
     */
    public function scopeHasEmptySpot($query, $storeId, $brandId)
    {
        return $query->where('store_id', $storeId)
                     ->where('month', Carbon::now()->month)
                     ->where('year', Carbon::now()->year)
                     ->where('brand_id', $brandId)
                     ->where('ba_id', 0);
    }

    /**
     * Reject when BA failed to reenter the game
     *
     * @param $query
     * @param Ba $ba
     * @return mixed
     */
    public function scopeRejectRejoin($query, Ba $ba)
    {
        return $query->where('ba_id', $ba->id)
                     ->where('month', Carbon::now()->month)
                     ->where('year', Carbon::now()->year);
    }


}
