<?php

namespace App\Http\Controllers\Master;

use App\City;
use App\Store;
use App\Filter\CityFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    /**
     * get data province yang udah di query
     *
     * @param Request $request
     * @param CityFilter $cityFilter
     * @return
     */
    public function filter(Request $request, CityFilter $cityFilter)
    {
        return City::filter($cityFilter)
            ->select('province_name')
            ->where('province_name', 'like', '%'.$request->term.'%')
            ->distinct()
            ->get(); 
    }

    /**
     * Filtering city based on user action
     *
     * @param Request $request
     * @param CityFilter $cityFilter
     * @return mixed
     */
    public function filterCity(Request $request, CityFilter $cityFilter)
    {
        return City::filter($cityFilter)
            ->select('id','city_name')
            ->where('province_name', $request->province_name)
            ->where('city_name', 'like', '%'.$request->term.'%')
            ->get();
    }
    public function allStore(Request $request)
    {
        $city = City::where('province_name',$request->province_name)->get();
        foreach ($city as $city_id) {
            $city_ids[] = $city_id->id;
        }
        $store = Store::whereIn('city_id',$city_ids)->whereNotNull('reo_id')->get();
        if ($store->count() > 0) {
            $data = array('status' => false,
                          'code'    => 0,
                          'content' => 'Ada Reo di provinsi store');  
        }
        else{
            $data = array('status' => true,
                          'code'    => 1,
                          'content' => 'Reo kosong semua di provinsi store');  
        }
        $response =[

            'data' => $data,

        ];

        return $response;
    }
    public function getStore(Request $request)
    {
        $city = City::where('province_name', $request->province_name)->get();
        foreach ($city as $city_id) {
            $city_ids[] = $city_id->id;
        }
        $store = Store::with('city')->whereIn('city_id', $city_ids)->get();
        foreach ($store as $store_id) {
            $data_store[] = $store_id;
        }
        return $data_store;
    }

    /**
     * Get Region based on city
     *
     * @param City $city
     * @return mixed
     */
    public function cityRegion(City $city)
    {
        return $city->region_id;
    }

    /**
     * Get City Name based on ID
     *
     * @param City $city
     * @return mixed
     */
    public function cityName(City $city)
    {
        return $city->city_name;
    }
}
