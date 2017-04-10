<?php

namespace App\Http\Controllers;

use App\Account;
use App\Agency;
use App\ArinaBrand;
use App\Ba;
use App\Brand;
use App\City;
use App\Region;
use App\Store;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export data toko masih belum semuanya dari excel
     *
     */
    public function exportToko()
    {
        ini_set('auto_detect_line_endings', true);
        Excel::filter('chunk')->selectSheetsByIndex(0)->load(storage_path('konfig_toko_gt_januari_2017.xlsx'))->chunk(2000, function($results)
        {
            collect($results)->map(function ($row) {
                return $row->map(function ($item) {
                    $city = City::where('city_name', 'like', '%'.$item['kota']. '%')->where('province_name', 'like', '%'. $item['provinsi'].'%')->first();
                    $item['account_id'] = Account::where('name', 'like', '%'. $item['account'] . '%')->first()->id;
                    $item['city_id']  = ($city != null) ? $city->id : 66910;
                    $item['region_id'] = Region::where('name', $item['region'])->first()->id;
                    $item['shipping_id'] = ($item['shipping_id']) ?: '';
                    $item['reo_id'] = rand(3,4);
                    $item['store_no'] = ($item['store_no']) ?: '';
                    $item['customer_id'] = ($item['cust_id']) ?: '';
                    Store::create($item->toArray());
                });
            });
        });
        return 'tess';
    }

    /**
     * Export data ba masih belum semuanya dari excel
     *
     */
    public function exportBa()
    {
        Excel::filter('chunk')->selectSheetsByIndex(0)->load(storage_path('konfig_ba_januari_2017.xlsx'))->chunk(2000, function($results)
        {
            collect($results)
                ->map(function ($row) {
                return $row->map(function ($item) {
                    if ($item['nama_ba'] != 'vacant') {
                        $city = City::where('city_name', 'like', '%' . $item['kota'] . '%')->where('province_name', 'like', '%' . $item['provinsi'] . '%')->first();
                        $statusList = ['mobile', 'stay', 'rotasi'];
                        $agency = Agency::where('name', 'like', '%' . $item['agency'] . '%')->first();
                        $item['nik'] = ($item['nip']) ?: '121212121';
                        $item['city_id'] = ($city != null) ? $city->id : 66910;;
                        $item['brand_id'] = Brand::where('name', 'like', '%' . $item['brand'] . '%')->first()->id;
                        $item['rekening'] = '112212223';
                        $item['bank_name'] = 'BNI';
                        $item['no_ktp'] = (is_numeric($item['no_ktp']) && $item['no_ktp'] != null) ? $item['no_ktp'] : 1121212;
                        $item['no_hp'] = ($item['no_hp']) ?: 112321312;
                        $item['class'] = ($item['class']) ?: '1';
                        $item['join_date'] = ($item['join_date']) ?: Carbon::now();
                        $item['name'] = $item['nama_ba'];
                        $item['status'] = (collect($statusList)->contains($item['status_ba'])) ? $item['status_ba'] : 'stay';
                        $item['agency_id'] = ($agency != null) ? $agency->id : '1';
                        $item['uniform_size'] = $this->decideUniformSize($item['size_baju']);
                        $item['total_uniform'] = 1;
                        $item['description'] = ($item['keterangan']) ?: 'bagus';
                        $item['gender'] = ($item['jenis_kelamin'] == 'P') ? 'female' : 'male';
                        $item['approval_id'] = 2;
                        $item['area_id'] = 1;
                        $item['position_id'] = 1;
                        $item['professional_id'] = 1;
                        $item['division_id'] = 1;
                        $item['education'] = 'SLTA';
                        $item['birth_date'] = '2016-07-04';
                        $item['arina_brand_id'] = ArinaBrand::where('cabang', 'like', '%' . $item['cabang_arina'] . '%')->first()->id;
                        $baCheck = Ba::where('name', $item['name'])->where('no_ktp', $item['no_ktp'])->with('store')->first();
                        if ($baCheck == null) {
                            $ba = Ba::create($item->except('brand')->toArray());
                        } else {
                            $ba = $baCheck;
                        }
                        $store = Store::where('store_name_1', $item['store_name_1'])->first();

                        if($store != null) {
                            $ba->store()->attach($store->id);
                            $ba->createNewBaHistory($ba->id, 'new', $store->id);
                        }
                    }
                });
            });
        });
        return 'berhasil bos';
    }

    /**
     * Switching value for uniform size
     *
     * @param $value
     * @return string
     */
    public function decideUniformSize($value)
    {
        switch ($value) {
            case 'S':
            default:
                return 'S/7';
            case 'M':
                return 'M/9';
            case 'L':
                return 'L/11';
        }
    }
}
