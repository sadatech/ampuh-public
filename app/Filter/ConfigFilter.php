<?php

namespace App\Filter;


class ConfigFilter extends QueryFilters
{
    /**
     * Filter config berdasarkan nama BA
     *
     * @param $id
     * @return mixed
     */
    public function name ($id) {
        if ($this->isDifferentMonth()) {
            return $this->builder->where('ba_id', $id );
        }
        return $this->builder->whereHas('ba',function ($query) use ($id) {
            return $query->where('bas.id', $id);
        });
    }

    /**
     * Filter config berdasarkan toko
     *
     * @param $storeId
     * @return mixed
     */
    public function customerId ($storeId) {
        return $this->builder->where('store_id', $storeId );
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
        return $this->builder->whereHas('store.city', function ($query) use ($provinceName) {
            return $query->where('cities.province_name', 'like', '%'.$provinceName.'%');
        });
    }

    /**
     * Filter configuration data by channel
     *
     * @param $channelName
     * @return mixed
     */
    public function channel($channelName)
    {
        return $this->builder->whereHas('store', function ($query) use ($channelName) {
           return $query->where('stores.channel', 'like', '%'.$channelName.'%');
        });
    }

    /**
     * Filter configuration data by brand
     *
     * @param $brandName
     * @return mixed
     */
    public function brand($brandName)
    {
        return $this->builder->whereHas('brand', function ($query) use ($brandName) {
           return $query->where('brands.name', 'like', '%'.$brandName.'%');
        });
    }

    /**
     * Filter berdasarkan Toko Id showing store name 
     *
     * @param $id
     * @return mixed
     */
    public function storeName($id)
    {
        return $this->builder->where('store_id', $id);
    }

    /**
     * Filter Configurasi berdasarkan
     *
     * @param $name
     * @return mixed
     */
    public function account($name)
    {
        return $this->builder->whereHas('store', function ($query) use ($name) {
            return $query->where('stores.account_id', $name);
        });
    }

    /**
     * Filter berdasarakan region tokonya
     *
     * @param $regionId
     * @return mixed
     */
    public function region($regionId)
    {
        return $this->builder->whereHas('store', function ($query) use ($regionId) {
           return $query->where('stores.region_id', $regionId);
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
     * Set the Request Type
     *
     * @param $value
     * @return mixed
     */
    public function requestType($value)
    {
        return $this->setRequestType($value);
    }

    /**
     * Filter only store that have reo which is being selected
     *
     * @param $id
     * @return mixed
     */
    public function namaReo($id)
    {
        return $this->builder->whereHas('store', function ($query) use ($id) {
            return $query->where('stores.reo_id', $id);
        });
    }

    /**
     * Filter by area arina branch
     *
     * @param $id
     * @return mixed
     */
    public function area($id)
    {
        return $this->builder->where('arina_brand_id', $id);
    }

    /**
     * Filtering turnover based on updated at month
     *
     * @param $month
     * @return mixed
     */
    public function monthTurnOver($month)
    {
        return $this->builder->whereMonth('resign_at', $month);
    }

    /**
     * Filtering turnover based on updated at year
     *
     * @param $year
     * @return mixed
     */
    public function yearTurnOver($year)
    {
        return $this->builder->whereYear('resign_at', $year);
    }

}