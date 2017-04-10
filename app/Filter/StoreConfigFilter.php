<?php

namespace App\Filter;


class StoreConfigFilter extends QueryFilters
{
    /**
     * Filter config berdasarkan Id toko dengan filter customer Id
     *
     * @param $storeId
     * @return mixed
     */
    public function customerId ($storeId) {
        if ($this->isDifferentMonth()) {
            return $this->builder->where('store_id', $storeId );
        }
        return $this->builder->where('id', $storeId);
    }

    /**
     * FIltering by is showing the store name in UI
     *
     * @param $storeId
     * @return mixed
     */
    public function storeName($storeId)
    {
        if ($this->isDifferentMonth()) {
            return $this->builder->where('store_id', $storeId );
        }
        return $this->builder->where('id', $storeId);
    }

    /**
     * Filter config berdasarkan provinsi
     *
     * @param $provinceName
     * @return mixed
     */
    public function province ($provinceName) {
        if ($this->isDifferentMonth()) {
            return $this->builder->where('provinsi', 'like', '%'.$provinceName.'%');
        }
        return $this->builder->whereHas('city', function ($query) use ($provinceName) {
            return $query->where('province_name', 'like', '%'.$provinceName.'%');
        });
    }

    /**
     * Sorting data ngikutin yang ada dari vue table
     *
     * @param $value
     * @return mixed
     */
    public function sort ($value) {
        $sort = explode('|', $value);
        return $this->builder->orderBy($sort[0], $sort[1]);
    }

    /**
     * Filter only store that have reo which is being selected
     *
     * @param $id
     * @return mixed
     */
    public function namaReo($id)
    {
        if ($this->isDifferentMonth()) {
            return $this->builder->whereHas('store', function ($query) use ($id){
                return $query->where('stores.reo_id', $id);
            });
        }
        return $this->builder->where('reo_id', $id);
    }

    /**
     * Constraint Based on chosen account
     *
     * @param $id
     * @return mixed
     */
    public function account($id)
    {
        if ($this->isDifferentMonth()) {
            return $this->builder->whereHas('store', function ($query) use ($id){
                return $query->where('stores.account_id', $id);
            });
        }
        return $this->builder->where('account_id', $id);
    }

    /**
     * Filtering store based on channel
     *
     * @param $channelName
     * @return mixed
     */
    public function channel($channelName)
    {
        if ($this->isDifferentMonth()) {
            return $this->builder->whereHas('store', function ($query) use ($channelName) {
                return $query->where('stores.channel', 'like', '%'.$channelName.'%');
            });
        }
        return $this->builder->where('channel', 'like', '%'. $channelName . '%');
    }

    /**
     * Filtering Store based on the region
     *
     * @param $id
     * @return mixed
     */
    public function region($id)
    {
        if ($this->isDifferentMonth()) {
            return $this->builder->whereHas('store', function ($query) use ($id){
                return $query->where('stores.region_id', $id);
            });
        }
        return $this->builder->where('region_id', $id);
    }

}