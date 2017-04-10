<?php


namespace App\Filter;


class CityFilter extends QueryFilters
{
    /**
     * query data provinsi berdasarkan user query dan di distinct biar ga double data yang tampil
     * @param $value
     * @return null
     */
    public function province($value)
    {
        return (!$this->requestAllData($value)) ? $this->builder->where('province_name', 'like', '%'.$value.'%') : null ;
    }


    /**
     * Filter berdasarkan nama BA dia kerja di city mana aja dimasukin disini
     * @param $name
     * @return mixed
     */
    public function name($name)
    {
      return $this->builder->wherehas('store.haveBa', function ($query) use ($name) {
          $query->where('ba_id',$name);
      });
    }

    /**
     * Di filter lagi bakal nampilin city yang berdasarkan toko aja
     * @param $value
     * @return mixed
     */
    public function customerId($value)
    {
        return $this->builder->whereHas('store', function ($query) use ($value) {
            $query->where('stores.id',$value);
        });
    }

    /**
     *
     * filter city berdasarkan inputan user
     * @param $value
     * @return null
     */
    public function city($value)
    {
        return (!$this->requestAllData($value)) ? $this->builder->where('city_name', 'like', '%'.$value.'%') : null ;
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
}