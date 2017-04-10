<?php

namespace App\Filter;

class BaFilter extends QueryFilters
{
    /**
     * Query berdasarkan nama Ba
     * @param $name
     * @return null
     */
    public function name ($name) {
        return (!$this->requestAllData($name)) ? $this->builder->where('name', 'like', '%'.$name.'%') : null;
    }

    /**
     * Tampilin BA berdasarkan tokonya
     * Ini akan ke request jika user pertama udah klik toko nya dlu
     * @param $value
     * @return mixed
     */
    public function customerId ($value) {
        return $this->builder->whereHas('store', function ($query) use ($value) {
            $query->where('store_id',$value);
        });
    }

    /**
     * Misal udah pilih BA maka tampilin province yang dia kerja doang ditempat tersebut
     * @param $value
     * @return mixed
     */
    public function province ($value) {
        return $this->builder->whereHas('store.city', function ($query) use ($value) {
            $query->where('province_name', 'like', '%'.$value.'%');
        });
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
     * Exclude current ID when switching BA
     *
     * @param $id
     * @return mixed
     */
    public function currentId($id)
    {
        return $this->builder->where('id', '!=', $id);
    }

    /**
     * Only get BA who has store attach when switching BA
     *
     * @return mixed
     */
    public function clearEmptyStoreBa()
    {
        return $this->builder->has('store');
    }
}