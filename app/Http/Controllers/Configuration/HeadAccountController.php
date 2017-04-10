<?php

namespace App\Http\Controllers\Configuration;

use App\Ba;
use App\Filter\ConfigFilter;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class HeadAccountController extends Controller
{
    public function index()
    {
        return view('configuration.hc.allData');
    }

    public function dtAllData(ConfigFilter $filters)
    {
        $hcALl = Ba::masterFilter($filters);
        $hcALl->where('approval_id', '!=', '5');
        /*if (self::isReo()) {
            $hcALl->showForReo(Auth::user()->id);
        }*/
        return Datatables::of($hcALl)
            ->addColumn('costCenter', function ($ba) {
                return $ba->store->map(function ($item) {
                    if ($item->account->name == 'MDS')
                        return 'MDS';
                    return 'CPD';
                })->first();
            })
            ->addColumn('nonBa', function ($item) {
                return $item->store->map(function ($store) {
                    // codes here
                })->first();
            })
            ->addColumn('distributor', function ($item) {
                return $item->store->map(function ($store) {
                    return ($store->account->name == 'MDS') ? 1 : '';
                })->first();
            })
            ->addColumn('ba', function ($item) {
                return $item->store->map(function ($store) {
                    return ($store->account->name != 'MDS') ? 1 : '';
                })->first();
            })
            ->editColumn('birth_date', function ($item) {
                return $this->readableDateFormat($item->birth_date);
            })
            ->editColumn('join_date', function ($item) {
                return $this->readableDateFormat($item->join_date);
            })
            ->addColumn('serviceYear', function ($ba) {
                return round(((Carbon::now()->diff($ba->join_date)->days) / 365), 1);
            })
            ->addColumn('age', function ($ba) {
                return (Carbon::now()->diff($ba->birth_date)->y);
            })
            ->addColumn('masa_kerja', function ($ba) {
                return round(((Carbon::now()->diff($ba->join_date)->days) / 365));
            })
            ->addColumn('serviceYears2', function ($ba) {
                //=IF(AG974<=2;"0-2";IF(AND(AG974>=3;AG974<6);"3-5";IF(AND(AG974>=6;AG974<=9);"6-9";">9")))
                $masker = round(((Carbon::now()->diff($ba->join_date)->days) / 365));
                if ($masker <= 2) return '0-2';
                elseif ($masker >= 3 AND $masker < 6) return '3-5';
                elseif ($masker >= 6 AND $masker <= 9) return '6-9';

                return '>9';
            })
            ->addColumn('ba_loreal', function ($ba) {
                if ($ba->brand_id == 1 || $ba->brand_id == 2) return 1;
            })
            ->addColumn('ba_myb', function ($ba) {
                if ($ba->brand_id == 5) return 1;
            })
            ->addColumn('ba_gar', function ($ba) {
                if ($ba->brand_id == 4) return 1;
            })
            ->addColumn('ba_nyx', function ($ba) {
                if ($ba->brand_id == 3) return 1;
            })
            ->addColumn('channel', function ($ba) {
                return $ba->store->map(function ($item) {
                    return $item->channel;
                })->first();
            })
            ->addColumn('region', function ($ba) {
                return $ba->store->map(function ($item) {
                    return $item->region->name;
                })->first();
            })
            ->addColumn('storeName', function ($ba) {
                return $ba->store->map(function ($store) {
                    return $store->store_name_1;
                })->implode(', ');
            })
            ->make(true);
    }

    public function cpd()
    {
        return view('configuration.hc.cpd');
    }

    /**
     * Format timestamp menjadi format carbon baru
     *
     * @param $date
     * @return string
     */
    private function readableDateFormat($date)
    {
        return Carbon::parse($date)->format('d-M-y');
    }
}
