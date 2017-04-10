<?php

namespace App\Http\Controllers\Master;

use App\Account;
use App\Branch_aro;
use App\City;
use App\Filter\StoreConfigFilter;
use App\Filter\StoreFilter;
use App\Helper\ExcelHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewStoreRequest;
use App\Notification;
use App\Region;
use App\Reo;
use App\SDF;
use App\Store;
use App\StoreKonfiglog;
use App\User;
use App\WIP;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Facades\Datatables;

class StoreController extends Controller
{
    private $excelHelper;

    /**
     * StoreController constructor.
     * @param ExcelHelper $excelHelper
     */
    public function __construct(ExcelHelper $excelHelper)
    {
        return $this->excelHelper = $excelHelper;
    }

    public function index()
    {
        $store = Store::all();
        $account = Account::all();
        $region = Region::all();
        $city = City::all();
        return view('master.store', compact('store', 'account', 'region', 'city'));
    }

    /**
     * Creating New Store and integrate new store with WIP and Sdf
     *
     * @param NewStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NewStoreRequest $request)
    {
        $request->newStore();

        return response()->json(['success' => true]);

    }

    /**
     * FInding one store
     *
     * @param Store $store
     * @return Store
     */
    public function find(Store $store)
    {
        return $store;
    }

    public function tes(){
      dd("hahahah");
    }

    public function create()
    {
        $account = Account::get();
        $region = Region::get();
        $reos = Reo::with('user')->get();
        return view('master.form.store', compact('reos', 'account', 'region'));
    }

    /**
     * Form edit
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $account = Account::get();
        $region = Region::get();
        $reos = Reo::with('user')->get();
        $store = Store::findOrFail($id);
        return view('master.form.store', compact('store', 'reos', 'account', 'region'));
    }

    /**
     * archieve store
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy($id)
    {
        $store = Store::find($id)->delete();
        DB::table('stores')
            ->where('id', $id)
            ->update(['deleted_by' => Auth::id()]);
        return response()->json('success');

    }

    /**
     * restore store from archieve
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function restore($id)
    {
        $store = Store::withTrashed()->find($id)->restore();
        DB::table('stores')
            ->where('id', $id)
            ->update(['deleted_by' => 0]);
        return response()->json('success');
    }


    public function update(Request $request)
    {
        $store = Store::find($request->_id);
        if ($request->alokasi_ba_oap || $request->alokasi_ba_myb || $request->alokasi_ba_gar || $request->alokasi_ba_cons) {
            $this->updateWIP($store, $request->all());
        }
        $store->update($request->all());
        DB::table('stores')
            ->where('id', $request->_id)
            ->update(['updated_by' => Auth::id()]);
        /*Notification::create([
            'name' => 'Toko ' . $store->store_name_1 . ' Telah dirubah oleh REO ' . $store->reo->user->name,
            'message' => '',
            'status' => 'info',
            'ba_id' => null,
            'role' => 'arina',
            'read' => 0
        ]);*/
        return redirect()->to('master/store');
    }


    /**
     * Datatable
     * soft delete with archieve
     * @return mixed
     */
    public function datatable(Request $request)
    {
        $archived = $request->exists('archived');
        $link = ($archived) ? 'Restore' : 'Archive';
        $sym = ($archived) ? 'fa-undo' : 'fa-archive';
        $stores = Store::with('city', 'account', 'reo.user', 'region', 'created_by', 'updated_by', 'deleted_by');
        if ($archived) {
            $stores->onlyTrashed();
        }

        if (Auth::user()->role == 'reo') {
            $stores->showForReo(Auth::user()->id);
        }
//        dd($stores->get());
        return Datatables::of($stores)
            ->addColumn('hold', function ($item) {
                if ($item->isHold == 1) {
                    return "<a href=\"#\"class=\"btn red\">Unhold  </a>";
                }
                return "<a href=\"/master/store/$item->id/hold\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn green-meadow\">Hold  </a>";
            })
            ->addColumn('edit', '<a href="/master/store/{{ $id }}/edit" class="btn green-meadow">Edit  </a>')
            ->addColumn('archive', '<a href="#" id="{{ $id }}" onclick="' . $link . '(this.id)" class="btn btn-danger">' . $link . ' <i class="fa ' . $sym . '"></i> </a>')
            ->make(true);
    }

    /**
     * Filter berdasarkan id store dan bisa jg yang lain kalau mau
     * @param StoreFilter $storeFilter
     * @return mixed
     */
    public function filter(StoreFilter $storeFilter)
    {
        return Store::filter($storeFilter)->get();

    }

    /**
     * Update WIP jika alokasi ba di toko berubah
     *
     * @param $store
     * @param $alokasi
     * @return mixed
     */
    public function updateWIP($store)
    {
        $alokasis['1'] = $store->alokasi_ba_cons;
        $alokasis['2'] = $store->alokasi_ba_oap;
        $alokasis['4'] = $store->alokasi_ba_gar;
        $alokasis['5'] = $store->alokasi_ba_myb;

        foreach ($alokasis as $id => $alokasi) {
            if (!empty($alokasi)) {

            }
        }

    }

    /**
     * Data configurasi buat toko
     *
     * @param StoreConfigFilter $filters
     * @return
     */
    public function configuration(StoreConfigFilter $filters)
    {
        if ($this->needToFetchFromHistory($filters)) {
            $configStore = StoreKonfiglog::configuration($filters);
        } else if ($this->fetchCurrentData($filters)) {
            $configStore = Store::configuration($filters);
        }
        if (self::isReo()) {
            $configStore->showForReo(Auth::user()->id);
        }
        if (self::isAro()) {
            $configStore->showForAro(Auth::user()->id);
        }
        return Datatables::of($configStore->get())
            ->addColumn('month', function ($item) use ($filters) {
                if ($filters->isDifferentMonth()) {
                    return $this->excelHelper->changeMonthFormat($item->month);
                }
                return $this->excelHelper->changeMonthFormat(Carbon::now()->month);
            })
            ->editColumn('myb_count', function ($item) use ($filters) {
                return $this->actualBrandCount($item->alokasi_ba_myb, $item->myb_count, $filters, $item->id, 5);
            })
            ->editColumn('oap_count', function ($item) use ($filters) {
                return $this->actualBrandCount($item->alokasi_ba_oap, $item->oap_count, $filters, $item->id, 2);
            })
            ->editColumn('gar_count', function ($item) use ($filters) {
                return $this->actualBrandCount($item->alokasi_ba_gar, $item->gar_count, $filters, $item->id, 4);
            })
            ->editColumn('cons_count', function ($item) use ($filters) {
                return $this->actualBrandCount($item->alokasi_ba_cons, $item->cons_count, $filters, $item->id, 1);
            })
            ->editColumn('mix_count', function ($item) use ($filters) {
                return $this->actualBrandCount($item->alokasi_ba_mix, $item->mix_count, $filters, $item->id, 6);
            })
            ->addColumn('year', function ($item) use ($filters) {
                return $filters->year();
            })->make(true);
    }

    /**
     * Show interface buat konfigurasi toko
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showConfig()
    {
        return view('configuration.store');
    }

    public function export(StoreConfigFilter $filters)
    {
        if ($filters->isDifferentMonth() || $filters->isDifferentYear()) {
            $history = StoreKonfiglog::where('month', $filters->month())
                ->where('year', $filters->year())
                ->first();
            return response()->download(storage_path('exports/' . $history->konfigurasi), $history->konfigurasi, ['Content-Type: application/vnd.ms-excel']);
        }
        Excel::create('Data Konfigurasi Store', function ($excel) use ($filters) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Toko', function ($sheet) use ($filters) {
                $sheet->setAutoFilter('A1:V1');
                $sheet->setHeight(1, 25);
                $sheet->fromModel($this->excelHelper->mapForStoreExcel(Store::configuration($filters)->get(), Carbon::now()->year, Carbon::now()->month), null, 'A1', true, true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:V1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:V1', 'thin');
            });

        })->export('xlsx');
    }

    /**
     * Ambil perhitungan alokasi ba dan actual  yang ada di suatu brand di satu toko
     *
     * @param Store $store
     * @return string
     */
    public function baAllocation(Store $store)
    {
        return Store::actualBa($store->id)->get()->flatMap(function ($item) {
            return [
                ['count' => $item->alokasi_ba_cons - $item->cons_count, 'id' => 1, 'name' => 'CONS'],
                ['count' => $item->alokasi_ba_oap - $item->oap_count, 'id' => 2, 'name' => 'OAP'],
                ['count' => $item->alokasi_ba_gar - $item->gar_count, 'id' => 4, 'name' => 'GAR'],
                ['count' => $item->alokasi_ba_myb - $item->myb_count, 'id' => 5, 'name' => 'MYB'],
                ['count' => $item->alokasi_ba_mix - $item->myb_mix, 'id' => 6, 'name' => 'MIX'],
            ];
        });
    }

    /**
     * Helper untuk ngecek apa user yang login reo atau bukan
     *
     * @return bool
     */
    public function isReo()
    {
        return Auth::user()->role == 'reo';
    }

    public function isAro()
    {
        return Auth::user()->role == 'aro';
    }

    /**
     * Cari WIP yang available di tempat reo berkerja
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function available()
    {
        $wip = WIP::where([
            ['ba_id', '!=', null],
            ['replace_id', null],
            ['status', 'replacement'],
            ['fullfield', 'hold'],
        ])
        ->with('store.city.aro', 'brand')
        ->whereHas('ba', function ($query) {
            $aroBranch = Branch_aro::where('user_id', Auth::user()->id)->pluck('branch_id')->toArray();
            return  $query->whereIn('arina_brand_id', $aroBranch);
        })
        ->whereHas('store', function ($query) {
            return $query->where('store_no', 'not like', 'rotasi')
                          ->where('store_no', 'not like', 'rotasi_mtka');
        })
            ->whereDoesntHave('store.ba')
        ->get();
        return response()->json(['status' => true, 'data' => $wip]);
    }

    /**
     * Load semua store dimana reo itu kerja dan sdf nya belom dikerjain
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReo()
    {
        $branch = Branch_aro::where('user_id', Auth::id())->pluck('branch_id');
        $sdf = SDF::with('store.reo', 'wip')->whereHas('store.city', function ($item) use ($branch) {
            $item->whereIn('branch_id', $branch);

        })->whereHas('wip', function ($query) {
            $query->where([
                ['ba_id', null],
                ['fullfield', 'hold']
            ]);
        })->whereHas('wip.replacement', function($query) {
            $query->where('status', 'Lulus');
        })
        ->groupBy('token')
        ->get()
            ->map(function ($item) {
                $sdf = SDF::where('token', $item->token);
                if($sdf->count() >= 2) {
                    //dia itu mobile
                    $sdf = $sdf->with('store')->get();
                    $storesName = $sdf->pluck('store.store_name_1');
                    $storesId = $sdf->pluck('store.id');
                    $data['id'] = $item->id;
                    $data['store_id'] = json_encode($storesId->all());
                    $data['store_name'] = implode(', ', $storesName->all());
                    return $data;
                }else {
                    $data['id'] = $item->id;
                    $data['store_id'] = $item->store_id;
                    $data['store_name'] = $item->store->store_name_1;
                    return $data;
                }
            });
        return response()->json(['status' => true, 'data' => $sdf]);
    }

    public function availBrand(Request $request)
    {
        $sdf = new SDF;
        $sdf = $sdf->where('id', $request->store_id);

        $sdf = $sdf->whereHas('wip', function ($query) {
            $query->where('ba_id', null);
            $query->where('fullfield', 'hold');
        })->with('brand')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    'brand' => $item->brand
                ];
            });
        //listing brand
        $brands = [];
        foreach ($sdf['brand'] as $brand) {
            $brands[] = [
                'id' => $brand->id,
                'name' => $brand->name
            ];
        }
//            /*return [
//                'id' => $item->brand->id,
//                'name' => $item->brand->name
//                ];*/
//            });
        if (count($brands) == 0) return response()->json(['status' => false, 'message' => 'Tidak ada brand yang membutuhkan!']);
        return response()->json(['status' => true, 'brands' => $brands]);
    }

    /**
     * View form hold toko
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formHold(Request $request, $id)
    {
        $store = Store::with('haveBa')
            /*->whereHas('haveBa', function ($query) {
                #$query->where('approval_id' , 2);
            })*/
            ->findOrFail($id);

        return view('partial.form-hold-store', compact('store', 'request'));
    }

    /**
     * Hold Toko
     * cuman bisa hold toko yang ba nya kosong
     *
     * @param $id
     * @return mixed
     */
    public function hold(Request $request, $id)
    {
        $store = Store::withCount('haveBa')->find($id);

        /* TODO : nunggu semmi benerin form rolling dan resign nya
         *
         *  if($store->have_ba_count != 0) {
             return response()->json(['status' => false, 'message' => 'Masih ada ba yang berkerja']);
         }*/
        $store->isHold = '1';
        $store->save();

        if ($request->exists('sdf')) {
            WIP::where('store_id', $store->id)->update(['fullfield' => 'hold store']);
        }
        return response()->json(['status' => true, 'message' => 'Toko Berhasil di hold', 'data' => $store]);
    }

    /**
     * Unhold toko
     *
     * @param $id
     * @return mixed
     */
    public function unhold(Request $request, $id)
    {
        $store = Store::withCount('haveBa')->find($id);

        /* TODO : nunggu semmi benerin form rolling dan resign nya
         *
         *  if($store->have_ba_count != 0) {
             return response()->json(['status' => false, 'message' => 'Masih ada ba yang berkerja']);
         }*/
        $store->isHold = 0;
        $store->save();

        if ($request->exists('sdf')) {
            WIP::where('store_id', $store->id)->update(['fullfield' => 'hold']);
        }
        return response()->json(['status' => true, 'message' => 'Toko Berhasil di unhold', 'data' => $store]);
    }


    /**
     * Get the vacant count which been calculated from the actual
     * people who works at the store and the allocation for each brand in the store
     * and also consider vacant count which being stored in the wip
     *
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function allocationInStore($id)
    {
        return collect(explode(',', $id))->map(function ($item) {
            return Store::actualBa($item)->get()->flatMap(function ($item) {
                return [
                    ['count' => ceil($item->alokasi_ba_cons) - $item->cons_count - $this->calculateVacantFromWip($item->id, 1), 'id' => 1, 'name' => 'CONS', 'store_id' => $item->id],
                    ['count' => ceil($item->alokasi_ba_oap) - $item->oap_count - $this->calculateVacantFromWip($item->id, 2), 'id' => 2, 'name' => 'OAP', 'store_id' => $item->id],
                    ['count' => ceil($item->alokasi_ba_gar) - $item->gar_count - $this->calculateVacantFromWip($item->id, 4), 'id' => 4, 'name' => 'GAR', 'store_id' => $item->id],
                    ['count' => ceil($item->alokasi_ba_myb) - $item->myb_count - $this->calculateVacantFromWip($item->id, 5), 'id' => 5, 'name' => 'MYB', 'store_id' => $item->id],
                    ['count' => ceil($item->alokasi_ba_mix) - $item->mix_count - $this->calculateVacantFromWip($item->id, 6), 'id' => 6, 'name' => 'MIX', 'store_id' => $item->id],
                ];
            });
        });
    }


    /**
     * Get the vacant count from the WIP in the store on some brand
     *
     * @param $storeId
     * @param $brandId
     * @return mixed
     */
    private function calculateVacantFromWip($storeId, $brandId)
    {
        return WIP::with('sdf')->whereHas('sdf', function ($query) use ($storeId, $brandId) {
            return $query->where('store_id', $storeId)->where('fullfield', '!=', 'fullfield')
                ->where('status', 'new store')->where('brand_id', $brandId);
        })->count();
    }

    /**
     * Check Whether the store in the brand has already got a mobile Ba by checking the allocation if decimal
     *
     * @param $allocation
     * @return bool
     */
    public function hasMobileBa($allocation)
    {
        return is_numeric($allocation) && floor($allocation) != $allocation;
    }

    /**
     * Helper for get the actual count following the allocation if there is mobile count and do nothing if there isn't a mobile Ba
     *
     * @param $allocation
     * @param $realCount
     * @param StoreConfigFilter $filter
     * @param $storeId
     * @param $brandId
     * @return mixed
     */
    public function actualBrandCount($allocation, $realCount, StoreConfigFilter $filter, $storeId, $brandId)
    {
        if ($this->needToFetchFromHistory($filter)) return $realCount;

        if ($this->hasMobileBa($allocation) && $realCount != 0 && $realCount == 1 && $allocation < 1) {
            return $realCount * $allocation;
        }
        if ($this->hasMobileBa($allocation) && $realCount != 0 && $realCount == 1 && $allocation > 1) {
            if ($allocation > $realCount) {
                $baInStore = Store::baInStoreAndBrand($storeId, $brandId)->first();
                return $baInStore->haveBa->reduce(function ($carry, $item) {
                     return $carry + (1 / count($item->store));
                });
            }

            return ($realCount * $allocation) - ($allocation - floor($allocation));
        }
        if ($this->hasMobileBa($allocation) && $realCount != 0 && $realCount > 1  && $allocation > 1) {
            if ($allocation > $realCount) {
                $baInStore = Store::baInStoreAndBrand($storeId, $brandId)->first();

                return $baInStore->haveBa->reduce(function ($carry, $item) {
                    return $carry + (1 / count($item->store));
                });
            }
            return $realCount - ($allocation - floor($allocation));
        }
        return $realCount;
    }

    /**
     * Find and return reo name from the specified Store
     *
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function findReo(Store $store)
    {
        $storeReo = Store::with('reo.user')->find($store->id);
        if ($storeReo->reo == null) {
            return response()->json(['reoId' => 0, 'userId' => 0, 'namaReo' => 'Belum Ada Reo Pada Toko Ini']);
        }
        return response()->json(['reoId' => $storeReo->reo->id, 'userId' => $storeReo->reo->user_id, 'namaReo' => $storeReo->reo->user->name]);
    }

    /**
     * Filter Reo by their Name
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function reoFilter(Request $request)
    {
        return $request->namaReo == 'all' ? Reo::with('user')->get() : Reo::with('user')->whereHas('user', function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->namaReo . '%');
        })->get();
    }

    /**
     * Check Sdf Same Brand
     *
     * @param $brandId
     * @param $sdfBrand
     * @return int
     */
    public function sdfSameBrand($brandId, $sdfBrand)
    {
        return ($brandId == $sdfBrand->first()->id) ? $sdfBrand->first()->pivot->numberOfBa : 0;
    }

    /**
     * Generate next store no
     *
     * @param Account $account
     * @param $regionId
     * @return array
     */
    public function findStoreAccount(Account $account, $regionId)
    {
        $latestId = Store::where('account_id', $account->id)
                          ->where('region_id', $regionId)
                          ->orderBy('store_no', 'desc')
                          ->first();
        if ($latestId != null) {
            $id =  preg_replace("/[^0-9]/","", $latestId->store_no);
            $numberId = substr($id, 1, 3);
            $incrementId = (int)  $numberId += 1;

            return [
                'accountName' => $account->name,
                'latestId' => str_pad($incrementId, 3, '0', STR_PAD_LEFT),
                'region' => $regionId
            ];
        }
        return [
            'accountName' => $account->name,
            'latestId' => str_pad(1, 3, '0', STR_PAD_LEFT),
            'region' => $regionId
        ];

    }

    /**
     * Determine whether should fetch the historical data or current data
     *
     * @param StoreConfigFilter $filters
     * @return bool
     */
    private function needToFetchFromHistory(StoreConfigFilter $filters)
    {
        return $filters->isDifferentMonth() || $filters->isDifferentYear();
    }

    /**
     * Determine whether should fetch the current data
     *
     * @param StoreConfigFilter $filters
     * @return bool
     */
    private function fetchCurrentData(StoreConfigFilter $filters)
    {
        return $filters->month() == Carbon::now()->month && $filters->year() == Carbon::now()->year;
    }
}
