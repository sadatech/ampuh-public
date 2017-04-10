<?php

namespace App;

use App\Filter\QueryFilters;
use App\Traits\ConfigTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{

    use SoftDeletes, ConfigTrait;

    protected $fillable = [
        'store_no', 'customer_id','store_name_1','store_name_2','channel','account_id',
        'city_id','region_id','reo_id','alokasi_ba_oap','alokasi_ba_myb','alokasi_ba_gar',
        'alokasi_ba_cons','created_by','updated_by','deleted_by','isHold', 'alokasi_ba_mix'
    ];
    protected $dates = ['created_at', 'update_at', 'deleted_at', 'request_date', 'first_date'];
    /**
     * Handle null buat alokasi BA
     */
    public static function boot() {
        parent::boot();

        static::saving(
            function($model){
                foreach ($model->attributes as $key => $value) {
                    if($value !== 0) {
                        if($key == 'deleted_at' || $key == 'store_name_2' || $key == 'reo_id') {
                            $model->{$key} = empty($value) ? null : $value;
                        }else{
                            //Ngerubah yang empty jadi 0 (buat alokasi)
                            $model->{$key} = empty($value) ? 0 : $value;
                        }
                    }
                }
            });

        static::created(function (Store $store) {
            $store->insertToBaConfiguration ($store->fresh()->toArray());
        });
    }

    /**
     * Insert data yang baru diinsert untuk ke data konfigurasi ba
     *
     * @param $freshData
     */
    public function insertToBaConfiguration($freshData)
    {
        $this->travelNewStore($freshData['id']);
    }

    /**
     * Sebuah toko bisa memiliki banyak BA
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function haveBa()
    {
        return $this->belongsToMany('App\Ba')->withTimestamps();
    }

    /**
     * Toko punya satu Kota
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city ()
    {
        return $this->belongsTo('App\City');
    }

    /**
     * Toko punya Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    /**
     * Toko punya Region
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region ()
    {
        return $this->belongsTo('App\Region');
    }
    
    /**
     * ambil reo di toko
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reo()
    {
        return $this->belongsTo('App\Reo');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User','updated_by');
    }

    public function deleted_by()
    {
        return $this->belongsTo('App\User','deleted_by');
    }

    public function sp()
    {
        return $this->belongsTo('App\SP', 'id', 'store_id');
    }

    public function inWip()
    {
        return $this->belongsTo('App\WIP', 'id', 'store_id');
    }

    public function inWIp2()
    {
        return $this->hasMany('App\WIP', 'store_id', 'id');
    }



    /**
     * Query Filter yang tentang store
     *
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }


    /**
     * Query konfigurasi buat store
     *
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfiguration($query, QueryFilters $filters)
    {
        $data = $query->withCount(['haveBa as cons' => function ($query) {
            return $query->where('brand_id', 1);
        }, 'haveBa as oap' => function ($query) {
            return $query->where('brand_id', 2);
        }, 'haveBa as nyx' => function ($query) {
            return $query->where('brand_id', 3);
        }, 'haveBa as gar' => function ($query) {
            return $query->where('brand_id', 4);
        }, 'haveBa as myb' => function ($query) {
            return $query->where('brand_id', 5);
        }, 'haveBa as mix' => function ($query) {
            return $query->where('brand_id', 6);
        }])->with('reo.user', 'city', 'account', 'region', 'haveBa')
        ->orWhere(function ($query) {
           return $query->whereNull('isHold')
                         ->orWhere('isHold', 0);
        });
        return $filters->apply($data);
    }

    /**
     * Query konfigurasi buat di save ke history
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHistoryConfiguration($query)
    {
        return $query->withCount(['haveBa as cons' => function ($query) {
            return $query->where('brand_id', 1);
        }, 'haveBa as oap' => function ($query) {
            return $query->where('brand_id', 2);
        }, 'haveBa as nyx' => function ($query) {
            return $query->where('brand_id', 3);
        }, 'haveBa as gar' => function ($query) {
            return $query->where('brand_id', 4);
        }, 'haveBa as myb' => function ($query) {
            return $query->where('brand_id', 5);
        }, 'haveBa as mix' => function ($query) {
            return $query->where('brand_id', 6);
        }])
        ->orWhere(function ($query) {
            return $query->whereNull('isHold')
                ->orWhere('isHold', 0);
        })
        ->with('reo.user', 'city', 'account', 'region');
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
        return $query->whereHas('reo',function ($query) use ($reoId) {
            return $query->where('user_id',$reoId);
        });
    }
    public function scopeShowForAro($query, $id)
    {
        return $query->whereHas('city.aro', function ($query) use ($id) {
            return $query->where('user_id', $id);
        });
    }


    /**
     * Ambil data actual ba yang kerja di toko tersebut dengan brand tersebut dan data alokasi yang diminta
     *
     * @param $query
     * @param $storeId
     * @return mixed
     */
    public function scopeActualBa($query, $storeId)
    {
        return $query->where('id',$storeId)
                     ->select('alokasi_ba_nyx', 'alokasi_ba_oap', 'alokasi_ba_myb', 'alokasi_ba_gar', 'alokasi_ba_cons', 'alokasi_ba_mix', 'id')
                     ->withCount(['haveBa as cons' => function ($query) {
                                     return $query->where('brand_id', 1);
                                }, 'haveBa as oap' => function ($query) {
                                     return $query->where('brand_id', 2);
                                }, 'haveBa as nyx' => function ($query) {
                                     return $query->where('brand_id', 3);
                                }, 'haveBa as gar' => function ($query) {
                                     return $query->where('brand_id', 4);
                                }, 'haveBa as myb' => function ($query) {
                                     return $query->where('brand_id', 5);
                                }, 'haveBa as mix' => function ($query) {
                                     return $query->where('brand_id', 6);
                                }]);
    }

    /**
     * Ambil data actual ba yang kerja di toko tersebut pada satu  brand tersebut dan data alokasi yang diminta
     *
     * @param $query
     * @param $storeId
     * @param $brandName
     * @param $brandId
     * @return mixed
     */
    public function scopeActualBaOneBrand($query, $storeId, $brandName, $brandId)
    {
        return $query->where('id',$storeId)
            ->select('alokasi_ba_'. $brandName, 'id')
            ->withCount(['haveBa as ba' => function ($query) use ($brandId) {
                return $query->where('brand_id', $brandId);
            }]);
    }


    /**
     * Get Allocation for each brand in each store
     *
     * @param $query
     * @return mixed
     * @internal param $storeId
     */
    public function scopeTravelToAllStore($query)
    {
        return $query->select('id as storeId', 'alokasi_ba_myb as myb', 'alokasi_ba_cons as cons', 'alokasi_ba_gar as gar','alokasi_ba_oap as oap', 'alokasi_ba_mix as mix');
    }

    /**
     * Get Allocation for each brand in one store
     *
     * @param $query
     * @param $storeId
     * @return mixed
     * @internal param $storeId
     */
    public function scopeTravelOneStore($query, $storeId)
    {
        return $query->select('id as storeId', 'alokasi_ba_myb as myb', 'alokasi_ba_cons as cons', 'alokasi_ba_gar as gar','alokasi_ba_oap as oap', 'alokasi_ba_mix as mix')->where('id', $storeId);
    }

    public function scopeBaFromStore($query, $brandId, $storeId)
    {
        return $query->with('haveBa', 'city', 'reo.user', 'region', 'account')
                     ->withCount(['haveBa as ba' => function ($query) use ($brandId) {
                         return $query->where('bas.brand_id', $brandId);
                     }])
                     ->where('id', $storeId);
    }

    /**
     *  Get Ba in Store in determine Brand
     *
     * @param $query
     * @param $storeId
     * @param $brandId
     * @return mixed
     */
    public function scopeBaInStoreAndBrand($query, $storeId, $brandId)
    {
        return $query->with('haveBa.store')
                     ->where('id', $storeId)
                     ->whereHas('haveBa', function ($query) use ($brandId){
                            return $query->where('brand_id', $brandId);
                     });
    }

}
