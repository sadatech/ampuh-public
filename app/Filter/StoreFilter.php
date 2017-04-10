<?php

namespace App\Filter;



use App\Ba;
use App\Branch_aro;
use App\Brand;
use App\Store;
use App\WIP;
use Illuminate\Http\Request;


class StoreFilter extends QueryFilters
{
    protected $storeId;

    /**
     * Query toko berdasarkan customer ID
     * Jika minta request all maka fungsi ini bakal return null
     *
     * @param $value
     * @return null
     */
    public function customerId($value)
    {
        return (!$this->requestAllData($value)) ? $this->builder->where('customer_id', 'like', '%'.$value.'%') : $this->builder;
    }


    /**
     * Query toko yang alokasi berdasarkan brandnya masih kosong.
     *
     * @param $value
     * @return mixed
     */
    public function oneBrand($value)
    {
        $brand = Brand::find($value);

        $actual = Store::select('alokasi_ba_'. $brand->name, 'id')
            ->withCount(['haveBa as ba' => function ($query) use ($brand) {
                return $query->where('brand_id', $brand->id);
            }])
            ->get();
        foreach ($actual as $store) {
            if($store->alokasi_ba_{$brand->name} >= $store->ba_count) {
                $this->storeId[] = $store->id;
            }
       }

        $this->builder->find($this->storeId);
        return;
    }
    /**
     * Query nama toko berdasarkan nama store name 1 nya
     *
     * @param $value
     * @return mixed
     */
    public function storeName($value)
    {
        return (!$this->requestAllData($value)) ? $this->builder->where('store_name_1', 'like', '%'.$value.'%')->where('store_no', 'not like', '%rotasi%') : $this->builder->where('store_no', 'not like', '%rotasi%') ;
    }


    /**
     * Tampilin toko selain toko yang ba tersebut bekerja
     *
     * @param $value
     * @return mixed juice avocado with milk so yummy
     */
    public function excludeBaStore ($value) {
        return $this->builder->whereDoesntHave('haveBa', function ($query) use ($value) {
            return $query->where('ba_id', $value);
        });
    }

    public function excludeBaStoreBrand($data)
    {
//        return $this->builder->whereHas('inWip', function ($query) use ($data) {
//            return $query->where('store_id', '!=' , $data['store'])
//                         ->where('brand_id', '!=', $data['brand']);
//        });
    }

    /**
     * Constraint only show store that have same ba allocation
     *
     * @param $baId
     * @return mixed
     */
    public function sameAllocation ($baId)
    {
        $ba = Ba::with('brand')->withCount('store')->find($baId);

        $brand = 'alokasi_ba_' . strtolower($ba->brand->name);
        $sameAllocation =  number_format(1 / $ba->store_count, 6) + 0;

        return $this->builder->whereHas('inWip', function ($query) use ($sameAllocation, $brand) {
            return $query->where('fullfield', 'not like', 'fullfield')
                         ->where('head_count', 'like' , '%'. $sameAllocation . '%');
        });
    }

    /**
     * Constraint only show store that have same ba allocation plus one when adding new store
     *
     * @param $baId
     * @return mixed
     */
    public function sameAllocationPlusOne($baId)
    {
        $ba = Ba::with('brand')->withCount('store')->find($baId);

        $brand = 'alokasi_ba_' . strtolower($ba->brand->name);
        $sameAllocation =  number_format(1 / ($ba->store_count + 1), 6) + 0;

        return $this->builder->whereHas('inWip', function ($query) use ($sameAllocation, $brand) {
            return $query->where('fullfield', 'not like', 'fullfield')
                         ->where('head_count', 'like' , '%'. $sameAllocation . '%');
        });
    }
    /**
     *  Tampilin Toko berdasarkan tokonya
     * Ini akan ke request jika user pertama udah klik Ba  nya dlu
     *
     * @param $value
     * @return mixed
     */
    public function name ($value) {
        return $this->builder->whereHas('haveBa', function ($query) use ($value) {
            $query->where('ba_id',$value);
        });
    }

    /**
     * Misal udah pilih provinsi maka city nya akan ke filter berdasarkan provinsi tersebut
     *
     * @param $value
     * @return mixed
     */
    public function province ($value) {
        return $this->builder->whereHas('city', function ($query) use ($value) {
            $query->where('province_name', 'like', '%'.$value.'%');
        });
    }

    /**
     * Filter store only on chosen  channel if any
     *
     * @param $channelName
     * @return mixed
     */
    public function channel($channelName)
    {
        return $this->builder->where('channel', 'like', '%'.$channelName.'%');
    }


    /**
     * Jika dia login sebagai Reo hanya tampilkan store yang dia punya akses aja
     *
     * @param $reoId
     * @return mixed
     */
    public function reoId($reoId)
    {
        if (is_numeric($reoId)) {
            $reoRegion = Store::where('reo_id', $reoId)->first();
            return $this->builder->orWhere(function ($query) use ($reoRegion) {
                   return $query->where('store_no', 'like', '%rotasi%')->where('region_id', $reoRegion->region_id);
            });
        }
        return $this->builder;
    }

    /**
     * Jika dia login sebagai Aro hanya tampilkan store yang dia punya akses aja
     *
     * @param $aroId
     * @return mixed
     */
    public function aroId($aroId)
    {
        if (is_numeric($aroId)) {
            $regionId = Branch_aro::with('arina_branch')->where('user_id', $aroId)->first()->arina_branch->region_id;
            return $this->builder->orWhere(function ($query) use ($regionId) {
                return $query->where('store_no', 'like', '%rotasi%')->where('region_id', $regionId)->has('inWip');
            });

        }
        return $this->builder;
    }

    /**
     * Filter Berdasarkan Account untuk ba nya
     *
     * @param $value
     * @return mixed
     */
    public function account($value)
    {
        return $this->builder->whereHas('store', function ($query) use ($value) {
            return $query->where('stores.account_id', $value);
        });
    }

    /**
     * Constraint the store options with the same classing system only based on the first store User choose
     *
     * @param $firstStoreId
     * @return mixed
     */
    public function firstStore($firstStoreId)
    {
        $store = Store::find($firstStoreId);
        return $this->sameClassSystem($store->channel);
    }

    /**
     * Helper to decide which channel should be included in query based on the same classing system
     *
     * @param $channel
     * @return mixed
     */
    public function sameClassSystem($channel)
    {
        if ($channel == 'Dept Store' || $channel == 'Drug Store') {
            return $this->builder->where('channel', 'like', 'Dept Store')
                                 ->orWhere('channel', 'like', 'Drug Store');
        }
        return $this->builder->where('channel', 'like', 'MENSA')
                       ->orWhere('channel', 'like', 'GT/MTI')
                       ->orWhere('channel', 'like', 'MTKA Hyper/Super');
    }

    /**
     * Only get data which available from WIP
     *
     * @param $execute
     * @return mixed
     */
    public function onlyInWip($execute)
    {
        return $this->builder->whereHas('inWip', function ($query) {
            return $query->where('fullfield', 'not like', 'fullfield')
                         ->whereHas('ba', function ($query) {
                            $query->whereIn('bas.approval_id', [2, 5, 8]);
                         })
                         ->orWhere('ba_id', null);
        });
    }

    /**
     * Limit The head count when bulk rolling
     *
     * @param $headCount
     * @return mixed
     */
    public function limitHeadCount($headCount)
    {
        return $this->builder->whereHas('inWip', function ($query) use ($headCount) {
            return $query->where('fullfield', 'not like', 'fullfield')
                         ->where('head_count', 'like', '%' . $headCount . '%');
        });
    }

    /**
     * Make sure not showing stores that already being selected
     *
     * @param $stores
     * @return mixed
     */
    public function preventDuplicate($stores)
    {
        return $this->builder->whereNotIn('id', $stores);
    }

    /**
     * Constraint bulk rolling to only choose store with same brand
     *
     * @param $firstWip
     * @return mixed
     */
    public function sameBrand($firstWip)
    {
        $wipBrand = WIP::find($firstWip)->brand_id;

        return $this->builder->whereHas('inWip', function ($query) use ($wipBrand) {
            return $query->where('brand_id', $wipBrand);
        });
    }

    /**
     * Constraining to only move to same brand if not bulk rolling
     *
     * @param $brand
     * @return mixed
     */
    public function constraintBrand($brand)
    {
        if ($brand != false) {
            return $this->builder->whereHas('inWip', function ($query) use ($brand) {
                return $query->where('brand_id', $brand);
            });
        }
    }
}