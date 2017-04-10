<?php

namespace App;

use App\Filter\QueryFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Ba extends Model
{
    use SoftDeletes;

    protected $fillable = ['nik', 'name', 'area_id', 'no_ktp', 'no_hp', 'city_id', 'position_id', 'professional_id', 'foto_ktp', 'foto_tabungan', 'pas_foto', 'birth_date', 'brand_id', 'rekening', 'bank', 'bank_name', 'brand', 'status',
        'join_date', 'agency_id', 'uniform_size', 'total_uniform', 'description', 'class', 'gender', 'resign_stat', 'resign_reason', 'approval_id', 'approval_reason', 'branch', 'division_id',
        'status_sp', 'tanggal_sp', 'sp_approval', 'keterangan_sp', 'foto_sp', 'foto_akte', 'anak_lahir_date', 'arina_brand_id', 'resign_at', 'resign_info', 'join_date_mds', 'extra_keterangan'];
    protected $dates = ['created_at', 'update_at', 'deleted_at', 'join_date', 'birth_date', 'tanggal_sp', 'cuti_date', 'anak_lahir_date', 'resign_at', 'join_date_mds'];

    public static function boot()
    {
        parent::boot();

        static::created(function (Ba $ba) {
            $createdData = $ba->fresh()->toArray();
            Notification::create([
                'name' => Auth::user()->name . '(ARO) menambahkan BA baru dan butuh approval anda!',
                'message' => '',
                'status' => 'new',
                'ba_id' => $createdData['id'],
                'role' => 'arina',
                'read' => 0,
                'icon' => 'fa fa-user-plus'
            ]);
        });

        static::updated(function (Ba $ba) {
            $updatedData = $ba->fresh()->toArray();
            if ($updatedData['approval_id'] == 1) {
                Notification::create([
                    'name' => 'BA Baru (' . $updatedData->name . ') butuh approval anda!',
                    'message' => '',
                    'status' => 'new',
                    'ba_id' => $updatedData['id'],
                    'role' => 'loreal',
                    'read' => 0,
                    'icon' => 'fa fa-user-plus'
                ]);
            }
            if ($updatedData['approval_id'] == 5 || $updatedData['approval_id'] == 8) {
                // check dulu apa udah di takeout belum tokonya, kalau udah di takeout ga usah di trigger lagi
                $storeCheck = Store::whereHas('haveBa', function ($query)  use ($updatedData) {
                    return $query->where('ba_id', $updatedData['id']);
                })->get()->filter(function ($item) {
                    if ($item->isHold != null) {
                        return $item->isHold == 1;
                    }
                    return true;
                });
                if (count($storeCheck) != 0 ) $ba->triggerDetachConfiguration($updatedData);
            }
        });

        static::updating(function (Ba $ba) {
            $freshData = $ba->fresh()->toArray();
            $dirtyData = $ba->getDirty();
            if (isset($dirtyData['approval_id']) &&
                ($dirtyData['approval_id'] == 2 &&
                    ($freshData['approval_id'] == 0))
            ) {
                $ba->triggerNewBa($freshData);
            }
        });
    }

    /**
     * Detach data yang ada di ba summary
     *
     * @param $updatedData
     */
    public function triggerDetachConfiguration($updatedData)
    {
        BaSummary::where('ba_id', $updatedData['id'])
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->update(['ba_id' => 0]);
    }


    /**
     * Trigger ketika ada ba baru masukin juga ke summary data dan juga rejoin
     *
     * @param $freshData
     * @return mixed
     */
    public function triggerNewBa($freshData)
    {
        $status = ($freshData['approval_id'] == 0) ? 'new' : 'rejoin';
        $stores = Ba::with('store')->find($freshData['id'])->store;
        if ($freshData['status'] == 'rotasi') {
            if (BaSummary::where('ba_id', $freshData['id'])->first() == null) {
                return $stores->map(function ($item) use ($freshData, $status) {
                    $storeCount = Ba::withCount('store')->find($freshData['id']);
                    $rotationStore = Store::where('id', $item->id)->first();
                    Store::where('id', $item->id)->update(['alokasi_ba_cons' => $rotationStore['alokasi_ba_cons'] += $storeCount->store_count]);
                    $this->createNewBaHistory($freshData['id'], $status, $item->id);
                    return BaSummary::create(['ba_id' => $freshData['id'],
                        'store_id' => $item->id,
                        'brand_id' => 1,
                        'month' => Carbon::now()->month,
                        'year' => Carbon::now()->year]);
                });
            }
            return 'update not create';
        }
        return $stores->map(function ($item) use ($freshData, $status) {
            $this->createNewBaHistory($freshData['id'], $status, $item->id);
            return BaSummary::where('store_id', $item->id)
                ->where('brand_id', $freshData['brand_id'])
                ->where('ba_id', 0)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first()
                ->update(['ba_id' => $freshData['id']]);
        });
    }

    /**
     * Save data history New Ba
     *
     * @param $baId
     * @param $status
     * @param $storeId
     * @return BaHistory
     */
    public function createNewBaHistory($baId, $status, $storeId)
    {
        return BaHistory::create([
            'ba_id' => $baId,
            'status' => $status,
            'store_id' => $storeId
        ]);
    }

    /**
     * Filtering BA Berdasarakan Request User
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }


    /**
     * Filtering scope untuk master data
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMasterFilter($query, QueryFilters $filters)
    {
        $join = $query->with('city', 'approval', 'brand', 'agency', 'store', 'store.account', 'position', 'division', 'area','history.store.account','history.store.reo.user');
        return $filters->apply($join);
    }

    public function scopeCommandConfiguration($query)
    {
        return $query->join('ba_store', 'bas.id', '=', 'ba_store.ba_id')
            ->join('brands', 'brands.id', '=', 'bas.brand_id')
            ->rightJoin('stores', 'stores.id', '=', 'ba_store.store_id')
            ->join('accounts', 'stores.account_id', '=', 'accounts.id')
            ->join('regions', 'stores.region_id', '=', 'regions.id')
            ->join('cities', 'stores.city_id', '=', 'cities.id')
            ->join('reos', 'stores.reo_id', '=', 'reos.id')
            ->join('users', 'users.id', 'reos.user_id')
            ->join('agencies', 'bas.agency_id', '=', 'agencies.id')
            ->select('bas.*', 'stores.*', 'bas.id as ba_id', 'stores.id as store_id', 'accounts.name as account_name', 'cities.city_name', 'cities.province_name', 'users.name as reo_name', 'brands.name as brand_name', 'regions.name as region_name', 'ba_store.id as globalId', 'agencies.name as agencyName', \DB::raw('MONTH(CURDATE()) as currentMonth'), \DB::raw('YEAR(CURDATE()) as currentYear'))
            ->withCount('store');
    }

    /**
     * Jika Reo show data dia kerja doang
     * Check pertama ke toko kalau dia kerja ambil aja dari toko, check kedua dari city ketika dia udah ga active
     *
     * @param $query
     * @param $reoId
     * @return mixed
     */
    public function scopeShowForReo($query, $reoId)
    {
        return $query->whereHas('store.reo', function ($query) use ($reoId) {
            return $query->where('user_id', $reoId);
        })->orWhereHas('city.store', function ($query) use ($reoId) {
            return $query->where('stores.reo_id', $reoId)
                ->whereNotIn('approval_id', [2]);
        })->orWhereHas('store', function ($query)  {
            return $query->whereIn('stores.id', [144, 145, 146, 147, 148, 775, 776, 777, 778, 779])
                ->where('status', 'rotasi');
        });
    }

    /**
     * Only showing active BA
     *
     * @param $query
     */
    public function scopeOnlyActive($query)
    {
        return $query->where('approval_id', 2);
    }

    /**
     * Only showing resign BA
     *
     * @param $query
     */
    public function scopeOnlyResign($query)
    {
        return $query->where('approval_id', 5);
    }

    /**
     * Show Master Data berdasarkan area Aro
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeShowForAro($query, $id)
    {
        return $query->whereHas('arinaBrand.aro', function ($query) use ($id) {
            return $query->where('user_id', $id);
        });
    }

    /**
     * SP History data
     *
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpHistory($query, QueryFilters $filters)
    {
        return $filters->apply($query->with('store.account', 'city')->whereNotNull('status_sp'));
    }


    /**
     * Seorang ba bisa bekerja di lebih dari 1 toko
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function store()
    {
        return $this->belongsToMany('App\Store')->withTimestamps();
    }

    /**
     * Seorang Ba bisa tinggal atau mungkin kerja di toko yang berada di kota yang sama atau tidak
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo('App\City', 'city_id');
    }

    /**
     * 1 Ba punya 1 Brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function history()
    {
        return $this->hasMany('App\BaHistory');
    }



    /**
     * Data Approval ba tersebut
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approval()
    {
        return $this->belongsTo('App\Approve', 'approval_id');
    }

    /**
     * agencynya si ba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agency()
    {
        return $this->belongsTo('App\Agency', 'agency_id');
    }

    /**
     * Get arina branch from ba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function arinaBrand()
    {
        return $this->belongsTo('App\ArinaBrand', 'arina_brand_id');
    }

    /**
     * komen apa ya ?
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function area()
    {
        return $this->belongsTo('App\Area');
    }


    public function exitForm()
    {
        return $this->hasOne('App\ExitForm');
    }


}