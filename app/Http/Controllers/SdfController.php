<?php

namespace App\Http\Controllers;

use App\Account;
use App\Activities;
use App\Filter\SdfFilter;
use App\Helper\ExcelHelper;
use App\Region;
use App\Reo;
use App\Replace;
use App\SdfBrand;
use App\Traits\ConfigTrait;
use App\Traits\StringTrait;
use App\WIP;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

use App\SDF;
use App\Brand;
use App\Store;
use App\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Facades\Datatables;


class SdfController extends Controller
{
    use ConfigTrait, StringTrait;
    protected $excelHelper;

    public function __construct(ExcelHelper $excelHelper)
    {
        $this->excelHelper = $excelHelper;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sdfs = SDF::get()->sortByDesc('created_at');
        $brands = Brand::get();
        return view('sdf', compact('sdfs', 'brands'));
    }

    /**
     * Get the SDF Detail
     *
     * @param SDF $sdf
     * @return SDF
     */
    public function find(Sdf $sdf)
    {
        return SDF::with('brand', 'store.city')->find($sdf->id);
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasAccess($role)
    {
        return \Auth::user()->role == $role;
    }

    /**
     * @param Request $request
     * @param SDF $sdf
     * @param SdfFilter $filter
     * @return mixed
     */
    public function datatable(Request $request, SDF $sdf, SdfFilter $filter)
    {
        $archived = $request->exists('archived');
        $link = ($archived) ? 'Restore' : 'Archive';
        $sym = ($archived) ? 'fa-undo' : 'fa-archive';
        $sdf = $sdf->brandJoin(($archived) ? '=' : '!=', $filter);

        if($archived) $sdf->onlyTrashed();
//        dd($sdf->get());
        return Datatables::of($sdf)
            ->withTrashed()
            ->addColumn('idDetail', function ($item) {
                return "<a href='/sdf/detail/$item->id' data-target='#ajax' data-toggle='modal' > $item->id </a>";
            })
            ->addColumn('sdfDetail', function ($item) {
                return "<a href='/sdf/detail/$item->id' data-target='#ajax' data-toggle='modal' > $item->no_sdf </a>";
            })
            ->editColumn('created_at', function ($item) {
                return $this->readableDateFormat($item->created_at);
            })
            ->editColumn('request_date', function ($item) {
                return $this->readableDateFormat($item->request_date);
            })
            ->editColumn('first_date', function ($item) {
                return $this->readableDateFormat($item->first_date);
            })
            ->editColumn('deleted_at', function ($item) {
                return $this->readableDateFormat($item->deleted_at) ?: '';
            })
            ->addColumn('hold', function ($item) {
                $hold = "Hold";
                $color = 'green-meadow';
                $func = 'hold';
                if ($item->hold == 1) {
                    $hold = "Release";
                    $color = 'red';
                    $func = 'unhold';
                }
                return "<button onclick='$func($item->id)' class='btn $color'> $hold  </button>";
            })
            ->addColumn('city',function ($item){
                return $item->city_name;
            })
//            ->addColumn('status', function ($item) {
//                if ($item->numberOfBa < 1 && $item->fullfield != 'fullfield') {
//
//                    $tokenCount = count(SDF::where('token', $item->token)->get());
//                    if ($tokenCount >= 2) {
//                        return "<a style='width: 100px;margin-left: 10px' href='#' id='{{ $item->id }}' onclick='stay($item->id, $item->brandId)' class='btn btn-primary'> <i class='fa '></i>Stay </a>";
//                    } else if ($tokenCount == 1) {
//                        return "<a style='width: 100px;margin-left: 10px' href='#' id='{{ $item->id }}' onclick='mobile($item->id, $item->brandId)' class='btn btn-danger'> <i class='fa '></i>Add Toko </a>";
//                    }
//                }
//            })
            ->addColumn('download', function ($item) {
                return "<a href='/sdf/attachment/$item->id' class='btn btn-danger'> <i class='fa fa-file-pdf-o'></i>  </a>";
            })
            ->addColumn('update', function ($item) {
                return "<a href='/sdf/update/$item->token' data-target='#ajax' data-toggle='modal' class='btn blue'> <i class='fa fa-refresh'></i></a>";
            })
            ->addColumn('archive', '<a href="#" id="{{ $id }}" onclick="' . $link . '(this.id)" class="btn green-meadow">' . $link . ' <i class="fa ' . $sym . '"></i> </a>')
            ->make(true);
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

    /**
     * Create SDF
     *
     * @param  Request $request array
     * @return Redirect
     */
    public function create(Request $request)
    {
        $sdf = SDF::create($request->all());
        $brands = $request->brand;
        //mulai arai dari 1
        $brands = array_combine(range(1, count($brands)), $brands);
        foreach ($brands as $key => $value) {
            $sb['brand_id'] = $key;
            $sb['numberOfBa'] = $value;
            //Kalo ga ada ba nya ga usah di insert
            if (!empty($sb['numberOfBa'])) {
                $sdf->brand()->attach($key, $sb);
                for ($i = 0; $i < $value; $i++) {
                    $this->createWip($sdf, $sb['brand_id'], $request->request_date, $request->first_date);
                }
            }
        }
        return $this->broadcastToArea($sdf);
    }

    /**
     * WF = Create SDF -> BC -> Create WIP -> $$
     *
     * @param $sdf
     * @param $brand_id
     * @param $request_date
     * @param $first_date
     * @param int $headCount
     */
    public function createWip($sdf, $brand_id, $request_date, $first_date, $headCount = 0)
    {
        $wip = new WIP;
        $data = [
            'store_id' => $sdf->store_id,
            'brand_id' => $brand_id,
            'sdf_id' => $sdf->id,
            'status' => 'NEW STORE',
            'fullfield' => 'hold',
            'reason' => 'Alokasi BA baru',
            'effective_date' => DateTime::createFromFormat('d/m/Y', $first_date)->format('Y-m-d'),
            'filling_date' => DateTime::createFromFormat('d/m/Y', $request_date)->format('Y-m-d'),
            'head_count' => $headCount,
            'pending' => 0
        ];
        $wip->create($data);
    }

    /**
     * @param $sdf
     * @return \Illuminate\Http\RedirectResponse
     */
    public function broadcastToArea($sdf)
    {
        $store = Store::find($sdf->store_id)->store_name_1;
        //$sdf->find($sdf->id)->notify(new NewSDFReceived($store));
        $store_id = $sdf->store_id;
        $first_date = $sdf->first_date;
        $city = Store::with('city')->find($store_id)->city->id;

        $notification = Notification::create([
            'name' => "L'Oreal mengirimkan SDF baru pada toko ". $store,
            'message' => 'SDF Baru telah di buat',
            'status' => 'new',
            'role' => 'aro',
            'read' => 0,
            'firs_date' => $first_date,
            'icon' => 'fa fa-plus'
        ]);
        Activities::create([
            'activity' => 'Create new SDF for ' . $store,
            'type' => 'App\SDF',
            'relations_id' => $sdf->id,
            'user_id' => Auth::id()
        ]);
        $notification->sdf()->attach($sdf, ['city_id' => $city]);

        return redirect()->back();
    }


    public function destroy($id)
    {
        $sdf = SDF::find($id)->delete();
        DB::table('sdfs')
            ->where('id', $id)
            ->update(['deleted_by' => Auth::id()]);
        return response()->json('success');

    }

    public function restore($id)
    {
        $sdf = SDF::withTrashed()->find($id)->restore();
        DB::table('sdfs')
            ->where('id', $id)
            ->update(['deleted_by' => 0]);

        return response()->json('success');
    }

    /**
     * Hold SDF per alokasi
     * @param $id
     */
    public function hold($id)
    {
        DB::transaction(function() use($id){
            $sdf = SDF::with('wip')->find($id);
            $recentToken = $sdf->token;

            //Update description
            $replace = Replace::create(['description' => 'hold'])->id;
            $sdf->wip()->update(['replace_id' => $replace, 'isHold' => 1]);
            $sdf->update(['token' => str_random(20)]);

            $sdfYangDitinggalkan = SDF::where('token', $recentToken)->with('wip', 'brand');
//            return $sdfYangDitinggalkan->get();
            $sdfYangDitinggalkan->get()->map(function ($item) use ($sdfYangDitinggalkan) {
                // Calculate allocations
                $item->brand()->updateExistingPivot(
                    $item->brand->first()->id,
                    ['numberOfBa' => 1 / $sdfYangDitinggalkan->count()]
                );
                // also change hc on wip
                WIP::where('sdf_id', $item->id)->first()->update(
                    ['head_count' => 1 / $sdfYangDitinggalkan->count()]
                );
            });

            Activities::create([
                'activity' => "HOLD SDF $sdf->no_sdf",
                'type' => 'App\SDF',
                'relations_id' => $sdf->id,
                'user_id' => Auth::id()
            ]);
        });
    }

    public function unhold($id)
    {
        $sdf = SDF::with('wip')->find($id);
        //Update description
        $sdf->wip()->update(['replace_id' => null, 'isHold' => 0]);

        Activities::create([
            'activity' => "Release SDF $sdf->no_sdf",
            'type' => 'App\SDF',
            'relations_id' => $sdf->id,
            'user_id' => Auth::id()
        ]);
        return 'released';
    }

    public function exists(Request $request)
    {
        $sdf = SDF::where('no_sdf', $request->no_sdf)->get();
        if ($sdf->count() == 1) {
            $sdf = $sdf->first();
            // Cek apakah yang lagi login (reo) itu kerja buat store berdasarkan sdf ini
            $reo = Reo::select('id')->where('user_id', Auth::id())->first();
            $store = Store::select('id', 'reo_id')->where('reo_id', $reo->id)->where('id', $sdf->store_id)->first();
            if ($store == null) return response()->json(['status' => false, 'message' => 'Anda tidak bekerja pada toko tersebut!']);
            $wip = WIP::where('sdf_id', $sdf->id)
                ->get()
                ->filter(function ($item) {
                    return $item->ba_id == null && $item->fullfield == 'hold';
                });
            $brands = $wip->map(function ($item) {
                return [
                    'id' => $item->brand->id,
                    'name' => $item->brand->name
                ];
            });
            return response()->json(['status' => true, 'brands' => $brands]);
        }
        if ($sdf->count() > 1) {
            $reo = Reo::select('id')->where('user_id', Auth::id())->first();
            $brands = $sdf->filter(function ($item) use ($reo) {
                return Store::select('id', 'reo_id')->where('reo_id', $reo->id)->where('id', $item->store_id)->first() != null;
            })->map(function ($item) {
                return WIP::where('sdf_id', $item->id)->first();
            })->filter(function ($item) {
                return $item['ba_id'] == null && $item['fullfield'] == 'hold';
            })->map(function ($item) {
                return [
                    'id' => $item->brand->id,
                    'name' => $item->brand->name
                ];
            });
            if (count($brands) == 0) return response()->json(['status' => false, 'message' => 'Anda tidak bekerja pada toko tersebut!']);
            return response()->json(['status' => true, 'brands' => $brands]);
        }
        return response()->json(['status' => false]);
    }

    public function newBaSdf(Request $request)
    {
        $sdfAttachment = $request->attachment;
        $sdfAttachmentOrig = time() . '-' . $sdfAttachment->getClientOriginalName();
        $sdfAttachment->move('attachment/sdf', $sdfAttachmentOrig);

        $this->unHoldCheck(collect(explode(',', $request->store_id)));

        $toSdf = collect($request->all())
            ->filter(function ($item) {
                return $item != 0;
            })->map(function ($item, $key) use ($request, $sdfAttachmentOrig) {
                $token = str_random(20);
                $brand = Brand::where('name', 'like', $key)->first();
                if ($brand != null) {
                    $stores = collect(explode(',', $request->store_id));
                    $countStore = count($stores);
                    if ($request->has('sdfPairId')) {
                        $sdfPair = SDF::with('brand')->find($request->sdfPairId);
                        $token = $sdfPair->token;
                    }
                    return collect($stores)->map(function ($storeId) use ($request, $brand, $item, $sdfAttachmentOrig, $countStore, $token) {
                        $store = $this->getStore($storeId, $brand);
                        $this->insertSdf($request, $brand, $storeId, $sdfAttachmentOrig, $item, $countStore, $store, $token);
                        return ['success' => true];
                    });
                }
                return [];
            })->filter(function ($item, $key) {
                return Brand::where('name', 'like', '%' . $key . '%')->first() != null;
            });
        return response()->json($toSdf);
    }

    /**
     * Inserting data to sdf with some calculation for BA Allocation
     *
     * @param $request
     * @param $brand
     * @param $storeId
     * @param $sdfAttachmentOrig
     * @param $item
     * @param $countStore
     * @param $store
     * @param $token
     */
    public function insertSdf($request, $brand, $storeId, $sdfAttachmentOrig, $item, $countStore, $store, $token)
    {
        $sdf = SDF::create([
            'no_sdf' => $request->no_sdf,
            'first_date' => DateTime::createFromFormat('d/m/Y', $request['first_date'])->format('Y-m-d'),
            'request_date' => DateTime::createFromFormat('d/m/Y', $request['request_date'])->format('Y-m-d'),
            'store_id' => $storeId,
            'attachment' => $sdfAttachmentOrig,
            'created_by' => Auth::id(),
            'updated_by' => 0,
            'deleted_by' => 0,
            'token' => $token
        ]);
        $this->broadcastToArea($sdf);
        $inWip = WIP::where('store_id', $storeId)
            ->where('fullfield', '!=', 'fullfield')->where('status', 'new store')
            ->where('brand_id', $brand->id)->count();
        $allocation = $this->alokasiBa($brand->name);
        $vacantCount = ($store[$allocation] - $store['ba_count'] - $inWip);
        if ($vacantCount < $item) {
            if ($countStore > 1) {
                $mobileBaAllocation = 1 / $countStore;
                $incrementAllocation = (abs($mobileBaAllocation - $vacantCount)) + $store[$allocation];
            } else {
                $incrementAllocation = ($item - $vacantCount) + $store[$allocation];
            }
            $store->update([$allocation => $incrementAllocation]);
            $this->addAllocationFromSdf($store, ($item - $vacantCount), $brand->id);
        }
        if ($request->has('sdfPairId')) {
            $sdfPair = SDF::with('brand')->find($request->sdfPairId);
            $sdf->brand()->attach($brand->id, ['numberOfBa' => $sdfPair->brand->first()->pivot->numberOfBa]);
        } else {
            $sdf->brand()->attach($brand->id, ['numberOfBa' => $item / $request->size]);
        }
        while ($item > 0) {
            $headCount = ($item / $request->size);
            if ($request->has('sdfPairId')) {
                $sdfPair = SDF::with('brand')->find($request->sdfPairId);
                $headCount =  $sdfPair->brand->first()->pivot->numberOfBa;
            }
            $this->createWip($sdf, $brand->id, $request->request_date, $request->first_date, $headCount);
            --$item;
        }
    }

    /**
     * View for new sdf merged new and old store
     *
     * @return \Illuminate\Http\Response
     */
    public function newSdf()
    {
        $account = Account::get();
        $region = Region::get();
        $reos = Reo::with('user')->get();
        return response()->view('master.form.sdf', compact('account', 'region', 'reos'));
    }

    /**
     * Check whether in the multiple stores in the brand already had mobile BA we restricted that so they can add two ba mobile
     *
     * @param Request $request
     * @return array
     */
    public function hasMobileBaCheck(Request $request)
    {
        $hasMobileBaCheck = collect($request->stores)->map(function ($item) use ($request) {
            $brand = Brand::where('name', $request->brand)->first();
            $store = $this->getStore($item, $brand);
            $allocation = $this->alokasiBa($brand->name);
            if ($this->hasMobileBa($store[$allocation])) {
                return false;
            }
            return true;
        });
        if ($hasMobileBaCheck->contains(false)) {
            $error = 'Salah satu toko yang dipilih pada Brand  ' . $request->brand . ' Telah memiliki BA Mobile';
            return ['success' => false, 'error' => $error];
        }
        return ['success' => true];
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
     * Get the Store Detail
     *
     * @param $storeId
     * @param $brand
     * @return mixed
     */
    public function getStore($storeId, $brand)
    {
        return Store::withCount(['haveBa as ba' => function ($query) use ($brand) {
            return $query->where('bas.brand_id', $brand->id);
        }])->find($storeId);
    }

    public function stay($id,$brandId)
    {
        $string = str_random(20);
        $id_store = SDf::where('id', $id)->get()->pluck('store_id');
        $brand = DB::table('sdf_brands')->join('brands', 'sdf_brands.brand_id', '=', 'brands.id')->
        where('sdf_brands.sdf_id', $id)->select('brands.name')->first();

        $alokasi = Store::find($id_store);
        if ($brand->name == 'CONS') {
            $alokasi->alokasi_ba_cons = 1;
        }
        if ($brand->name == 'OAP') {
            $alokasi->alokasi_ba_oap = 1;
        }
        if ($brand->name == 'NYX') {
            $alokasi->alokasi_ba_nyx = 1;
        }
        if ($brand->name == 'GAR') {
            $alokasi->alokasi_ba_gar = 1;
        }
        if ($brand->name == 'MYB') {
            $alokasi->alokasi_ba_myb = 1;
        }
        $alokasi->save();
        WIP::where([['sdf_id', $id],['brand_id',$brandId]])->update(['head_count' => 1]);
        $token = SDF::find($id);
        $token->token = $string;
        $token->save();
        SdfBrand::where([['sdf_id', $id],['brand_id',$brandId]])->update(['numberOfBa' => 1]);
        return response()->json('success');

    }

    /**
     * Downloading SDF Attachment
     *
     * @param SDF $sdf
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAttachment(SDF $sdf)
    {
        return response()->download(public_path('attachment/sdf/' . $sdf->attachment), $sdf->attachment);
    }

    public function show($id)
    {
        $sdf = SDF::with('store.reo.user','store.account', 'wip', 'brand')->withTrashed()->find($id);
        return view('partial.detail-sdf', compact('sdf'));
    }

    /**
     * Unhold store if inserting new sdf to old store
     *
     * @param Collection $collection
     * @return Collection
     */
    private function unHoldCheck(Collection $collection)
    {
        return $collection->map(function ($item) {
            $store =  Store::find($item);
            if ($store->isHold = 1) {
                $store->update(['isHold' => null]);
            }
            return $store;
        });
    }

    public function update($token)
    {
        $sdf = SDF::with('store', 'wip', 'brand')->where('token', $token)->get();
        return view('partial.update-sdf', compact('sdf'));
    }

    public function available(Request $request, SDF $sdf, SdfFilter $filter)
    {
        $recentSDF = SDF::where('token', $request->token)->with('brand', 'store')->first();
        $store = $recentSDF->store;
        $brand = $recentSDF->brand->first()->id;
        $sdf = $sdf->brandJoin('!=', $filter)
            ->where('no_sdf', 'like', '%'.$request->term.'%')
            ->where('token', '!=', $request->token)
            ->where('brands.id', $brand)
            ->whereHas('brand', function($query) {
                $query->where('numberOfBa', 1);
            })
            ->whereHas('store', function ($query) use ($store){
                $query->where('channel', $store->channel);
            });
        return $sdf->get()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->no_sdf ." | ". $item->store->store_name_1
            ];
        });
    }

    /**
     * Pairing sdf to exists relations
     * @param Request $request
     * @return array
     */
    public function tambahSDF(Request $request)
    {
        $token = $request->token;
        $sdf_id = $request->sdf;

        //SDF yang mau ditambahin
        $sdf = SDF::with('brand', 'store', 'wip')->find($sdf_id);
        $sdf->update(['token' => $token]);

        $destination = SDF::where('token', $token)->with('brand');
        $sdf->update(['first_date' => $destination->first()->first_date]);

        $destination->get()->map(function ($item) use ($destination) {
            $item->brand()->updateExistingPivot(
                $item->brand->first()->id,
                ['numberOfBa' => 1 / $destination->count()]
            );
            WIP::where('sdf_id', $item->id)->first()->update(
                [
                    'head_count' => 1 / $destination->count(),
                    'effective_date' => $item->first_date
                ]);
        });
        WIP::where('sdf_id', $sdf->id)->first()->update(['head_count' => 1 / $destination->count()]);
        return [
            'id' => $sdf->id,
            'nosdf' => $sdf->no_sdf,
            'store' => $sdf->store->store_name_1
        ];


    }

    /**
     * Removing sdf store
     * @param Request $request
     * @return mixed
     */
    public function removeStore(Request $request)
    {
        $sdf = SDF::with('brand', 'wip')->find($request->sdf);
        $recentToken = $sdf->token;
        //delete store
        $sdf->brand()->detach();
        $sdf->wip()->delete();
        $sdf->update(['token' => str_random(20)]);
        $sdf->delete();

        $sdfYangDitinggalkan = SDF::where('token', $recentToken)->with('wip');
        $map = $sdfYangDitinggalkan->get()->map(function ($item) use ($sdfYangDitinggalkan) {
            // Calculate allocations
            $item->brand()->updateExistingPivot($item->brand->first()->id, ['numberOfBa' => 1 / $sdfYangDitinggalkan->count()]);
            // also change hc on wip
            WIP::where('sdf_id', $item->id)->first()->update(['head_count' => 1 / $sdfYangDitinggalkan->count()]);
        });
        return $map;

    }

    public function export(Request $request, SDF $sdf, SdfFilter $filter)
    {
        $archived = $request->exists('archived');
        $sdf = $sdf->brandJoin(($archived) ? '=' : '!=', $filter);

        if($archived) $sdf->onlyTrashed();

        Excel::create(date('dmY') . ' SDF', function ($excel) use ($sdf) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('SDF', function ($sheet) use ($sdf) {
                $sheet->freezeFirstRow();
                $sheet->setAutoFilter('A1:G1');
                $sheet->setHeight(2, 25);
                $sheet->fromModel($this->excelHelper->mapForSDF($sdf), null, 'A1', true, true);

                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });

                $sheet->cells('A1:G1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
            });

        })->export('xlsx');
    }
}