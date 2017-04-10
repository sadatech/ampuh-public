<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;

class StoreKonfiglog extends Model
{
    protected $fillable = [
                           'store_id', 'year', 'month', 'alokasi_ba_oap', 'alokasi_ba_myb', 'alokasi_ba_gar',
                           'alokasi_ba_cons', 'oap_count', 'myb_count', 'gar_count', 'cons_count', 'konfigurasi',
                           'alokasi_ba_mix', 'mix_count'
                          ];

    /**
     * Filter data untuk di show di website
     *
     * @param $query
     * @param QueryFilters $queryFilters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfiguration($query, QueryFilters $queryFilters)
    {
        $dataQuery = $query->with('store.city', 'store.reo.user', 'store.account')
                            ->where('month', $queryFilters->month())
                            ->where('year', $queryFilters->year());
        return $queryFilters->apply($dataQuery);
    }

    /**
     * Jika Reo show data dia kerja doang
     *
     * @param $query
     * @param $reoId
     * @return mixed
     */
    public function scopeShowForReo($query, $reoId)
    {
        return $query->whereHas('store.reo', function ($query) use ($reoId) {
            return $query->where('user_id', $reoId);
        });
    }

    public function scopeShowForAro($query, $id)
    {
        return $query->whereHas('store.city.aro', function ($query) use ($id) {
            return $query->where('user_id', $id);
        });
    }

    /**
     * Store di history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }

}
