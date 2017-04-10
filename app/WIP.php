<?php

namespace App;

use App\Filter\QueryFilters;
use App\Filter\WIPFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class WIP extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'wips';
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'ba_id', 'brand_id', 'sdf_id', 'replace_id', 'status', 'fullfield', 'filling_date', 'effective_date', 'reason', 'head_count', 'pending','isHold'];


    protected $dates = ['effective_date', 'filling_date', 'deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::updated(function (WIP $wip) {
            $updatedWip = $wip->fresh()->toArray();

            $clearToArchive = $wip->all()->filter(function ($item) use ($updatedWip){
                return $item->sdf_id == $updatedWip['sdf_id'] && $item->fullfield != 'fullfield';
            });


            if ($updatedWip['fullfield'] == 'fullfield' && count($clearToArchive) == 0) {
                $wip->archiveSdf($updatedWip['sdf_id']);
            }

        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(){
        return $this->belongsTo('App\Store');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ba(){
        return $this->belongsTo('App\Ba');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand(){
        return $this->belongsTo('App\Brand');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sdf(){
        return $this->belongsTo('App\SDF');
    }

    public function replacement()
    {
        return $this->belongsTo('App\Replace', 'replace_id');
    }


    /**
     * Soft Deleting SDF when WIP is change to fulfilled
     *
     * @param $sdfId
     * @return bool|null
     */
    public function archiveSdf($sdfId)
    {
        return SDF::find($sdfId)->delete($sdfId);
    }

    /**
     * Reject resign updating WIp scope
     *
     * @param $query
     * @param Ba $ba
     * @return mixed
     */
    public function scopeRejectResign($query, Ba $ba)
    {
        return $query->where('ba_id', $ba->id)
                     ->where('fullfield', 'hold')
                     ->whereNull('replace_id');
    }

    /**
     * Scope when Ba is failed to rejoin
     *
     * @param $query
     * @param Ba $ba
     * @return mixed
     */
    public function scopeRejectRejoin($query, Ba $ba)
    {
        return $query->where('ba_id', $ba->id)
                     ->where('fullfield', 'fullfield')
                     ->whereNotNull('replace_id');
    }

    public function scopeFilter($query, QueryFilters $filter)
    {
        $wip = WIP::with('store.city', 'store.account', 'brand', 'sdf', 'replacement.baReplace', 'ba')
            ->where('pending', 0)
            ->where('fullfield', '!=', 'fullfield')
            ->whereHas('ba', function ($query) {
                $query->whereIn('bas.approval_id', [2, 5, 8]);
            })
            ->orWhere('ba_id', null)
            ->orderBy('wips.created_at', 'desc');
        if(isset($query->storeName)) {
            $wip->where('fullfield', '!=', 'fullfield');
        }
        if (Auth::user()->role == 'reo') {
            $wip->whereHas('store.reo', function ($query) {
                return $query->where('id', $this->getReoID());
            });
        }

        if (Auth::user()->role == 'aro') {
            /*$wip->whereHas('store.city.aro', function ($query) {
                //return $query->where('user_id', 5);
            });*/
        }

        return $filter->apply($wip);
    }

    public function scopeNotifyAro($query, $aroId)
    {
        return $query->with('store.city.aro.user')->whereNull('replace_id')
            ->orWhereHas('replacement', function ($query) {
                return $query->where('status', 'Tidak Lulus');
            })
            ->whereHas('store.city.aro', function ($query) use ($aroId) {
              return $query->where('user_id', $aroId);
            });
    }

}
