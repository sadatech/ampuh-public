<?php

namespace App\Filter;

class AccountFilter extends QueryFilters
{
    /**
     * Query Filtering Account berdasarkan Ba yang dipilih
     *
     * @param $name
     * @return null
     */
    public function name ($name) {
        return $this->builder->whereHas('store.haveBa', function ($query) use ($name) {
            return $query->where('ba_store.ba_id', $name);
        });
    }

    /**
     * Tampilin Account yang ada di toko tersebut
     *
     * @param $value
     * @return mixed
     */
    public function customerId ($value) {
        return $this->builder->whereHas('store', function ($query) use ($value) {
            $query->where('stores.id',$value);
        });
    }

    /**
     * Tampilin Account berdasarkan provinsi yang dipilih
     *
     * @param $value
     * @return mixed
     */
    public function province ($value) {
        return $this->builder->whereHas('store.city', function ($query) use ($value) {
            $query->where('cities.province_name', 'like', '%'.$value.'%');
        });
    }

    /**
     * Dapatkan data semua account berdasarkan namanya
     *
     * @param $accountName
     * @return mixed
     */
    public function account($accountName)
    {
        return (!$this->requestAllData($accountName)) ?  $this->builder->where('name', 'like', '%'.$accountName.'%') : null;
    }
}