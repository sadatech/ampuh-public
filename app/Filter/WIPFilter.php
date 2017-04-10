<?php
namespace App\Filter;


class WIPFilter extends QueryFilters
{
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
     * Filter config berdasarkan provinsi
     *
     * @param $provinceName
     * @return mixed
     */
    public function province($provinceName)
    {
//        if ($this->isDifferentMonth()) {
//            return $this->builder->where('provinsi', 'like', '%'.$provinceName.'%');
//        }
        return $this->builder->whereHas('store.city', function ($query) use ($provinceName) {
            return $query->where('cities.province_name', 'like', '%' . $provinceName . '%');
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
            return $query->where('stores.channel', 'like', '%' . $channelName . '%');
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
        return $this->builder->where('brands.name', 'like', '%' . $brandName . '%');

    }

}