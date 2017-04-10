<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;

class BaKonfigLog extends Model
{
    protected $fillable = ['year', 'month', 'konfigurasi', 'no', 'nik', 'name', 'no_ktp', 'no_hp', 'provinsi', 'kota', 'nama_reo', 'region', 'brand', 'store_no', 'brand', 'customer_id', 'store_name_1', 'store_name_2', 'channel', 'account', 'status', 'join_date', 'size_baju', 'jumlah_seragam', 'keterangan', 'masa_kerja', 'class', 'jenis_kelamin', 'hc', 'status', 'ba_id', 'store_id', 'agency', 'status_sp', 'tanggal_sp', 'sp_approval'];

    /**
     * Filtering History BA Berdasarkan Request User
     *
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter ($query, QueryFilters $filters) {
        // TODO constraint tahun juga
        $constrain = $query->where('month', $filters->month())
                            ->select('year as currentYear', 'month as currentMonth', 'provinsi as province_name', 'kota as city_name', 'nama_reo as reo_name', 'region as region_name', 'store_no', 'customer_id', 'nik', 'name', 'no_ktp', 'no_hp', 'store_name_1', 'store_name_2', 'channel' , 'account', 'status', 'join_date', 'size_baju as uniform_size', 'jumlah_seragam as total_uniform', 'keterangan as description', 'class', 'jenis_kelamin as gender', 'hc as store_count','no as globalId', 'brand as brand_name', 'account as account_name', 'agency as agencyName');
        return $filters->apply($constrain);
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
}
