<?php

namespace App\Http\Controllers\Master;

use App\Agency;
use App\Ba;
use App\BaHistory;
use App\BaSummary;
use App\Branch_aro;
use App\Brand;
use App\ExitForm;
use App\Filter\BaFilter;
use App\Filter\ConfigFilter;
use App\Filter\SpFilter;
use App\Helper\ExcelHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RollingBaRequest;
use App\Notification;
use App\Reo;
use App\Replace;
use App\SDF;
use App\Store;
use App\Traits\ConfigTrait;
use App\Traits\StringTrait;
use App\WIP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Yajra\Datatables\Facades\Datatables;

class BaController extends Controller
{
    use StringTrait;
    protected $excelHelper;
    private $baConfigData;

    use ConfigTrait;

    /**
     * BaController constructor.
     * @param ExcelHelper $excelHelper
     */
    public function __construct(ExcelHelper $excelHelper)
    {
        $this->excelHelper = $excelHelper;
        $this->baConfigData = new Collection();
    }


    /**
     * Fetch Semua Konfigurasi BA dengan filter filternya
     *
     * @param ConfigFilter $filter
     * @return mixed
     */
    public function index(ConfigFilter $filter)
    {
        $configBa = BaSummary::configurationData($filter, $filter->month(), $filter->year());

        if (self::isReo()) {
            $configBa->showForReo(Auth::user()->id);
        }
        if (self::isAro()) {
            $configBa->showForAro(Auth::user()->id);
        }
        return Datatables::of($configBa)
            ->addColumn('masa_kerja', function ($item) {
                return (isset($item->ba->join_date) && $item->ba->join_date != '-') ? $this->excelHelper->exactTime(Carbon::now()->diff(new \DateTime($item->ba->join_date))) : '-';
            })
            ->editColumn('month', function ($item) {
                return $this->excelHelper->changeMonthFormat($item['month']);
            })
            ->editColumn('store_count', function ($item) use ($filter) {
                if ($filter->isDifferentMonth()) {
                    return $item['store_count_static'] != null ? round(1 / $item['store_count_static'], 2) : '-';
                }
                if (!isset($item['store_count'])) {
                    return '-';
                }
                if ($item['store_count'] <= 1 && !is_numeric($item['store_count']) && isset($item['store_count'])) {
                    return $item['store_count'];
                }
                return ($item['store_count'] > 0 && isset($item['store_count'])) ? round(1 / $item['store_count'], 2) : '-';
            })
            ->editColumn('ba.status_sp', function ($item) {
                if (!isset($item->ba)) {
                    return ' ';
                }
                if ($item->ba->sp_approval == 'approve' && $item->ba->status_sp != null) {
                    return $item->ba->status_sp;
                }
            })
            ->editColumn('ba.tanggal_sp', function ($item) {
                if (!isset($item->ba)) {
                    return ' ';
                }
                if ($item->ba->sp_approval == 'approve' && $item->ba->tanggal_sp != null) {
                    return $this->readableDateFormat($item->ba->tanggal_sp);
                }
            })
            ->editColumn('ba.description', function ($item) {
                if (!isset($item->ba)) {
                    return ' ';
                }
                $stores = Ba::with('store')->find($item->ba->id)->store->pluck('store_name_1');
                $keterangan = $item->ba->extra_keterangan ?: '';
                if (count($stores) > 1) {
                    return $stores->reduce(function ($tally, $item) {
                            $tally .= $item . ' , ';
                            return $tally;
                        }, 'Ba Mobile di Toko ') . $keterangan;
                }
                return $keterangan;
            })
            ->editColumn('ba.join_date_mds', function ($item) {
                if (!isset($item->ba)) {
                    return ' ';
                }
                if ($item->ba->join_date_mds != null && $item->ba->join_date_mds != '0000-00-00') {
                    return $this->readableDateFormat($item->ba->join_date_mds);
                }
                return '';
            })
            ->editColumn('ba.join_date', function ($item) {
                if (!isset($item->ba)) {
                    return ' ';
                }
                return $this->readableDateFormat($item->ba->join_date);

            })
            ->make(true);
    }

    /**
     * Ambil data detail satu Ba
     *
     * @param $ba
     * @return mixed
     */
    public function find(Ba $ba)
    {
        return Ba::with('store', 'brand')->find($ba->id);
    }

    /**
     * Master data view buat BA
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function master()
    {
        $ba = Ba::get();
        $role = Auth::user()->role;
        $approval_resign_id = ($role == 'arina') ? 3 : 4;
        $approval_newBa_id = ($role == 'arina') ? 0 : 1;
        $totalbayangbelomdiapprove = Ba::whereIn('approval_id', [$approval_newBa_id, $approval_resign_id])->count();
        return view('master.ba', compact('ba', 'totalbayangbelomdiapprove'));

        $i = 0;
        $bas = Ba::all();
        return view('master.ba', compact('bas', 'i'));
    }

    /**
     * Form Tambah BA
     *
     * @param $param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function AddBa($param)
    {
        /*Split parameter*/
        $param = base64_decode($param);
        $param = explode('/', $param);
        $agencies = Agency::get();
        $branchAro = Auth::user()->role == 'aro' ?
            Branch_aro::with('arina_branch')->whereHas('arina_branch', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })->get()
            : 'bukan aro';


        if (isset($param[0]) && $param[0] == 'rotasi') {
            $joinDate = Carbon::now()->format('d-m-Y');
            $regionId = Branch_aro::with('arina_branch')->where('user_id', Auth::user()->id)->first()->arina_branch->region_id;
            $storeReoRotasi = Store::where('store_no', 'like', '%rotasi%')->where('region_id', $regionId)->get();
            $compactVar = ['store' => 'rotasi', 'storeIds' => $storeReoRotasi, 'joinDate' => $joinDate, 'status' => 'rotasi', 'brand' => Brand::find(1), 'agencies' => $agencies, 'newRotasi' => 'newRotasi', 'branchAro' => $branchAro];
            if (isset($param[2])) {
                $wipId = $param[1];
                $storeReoRotasi = Store::where('store_no', 'rotasi')->where('region_id', $regionId)->get();
                $compactVar['rotasiReplacement'] = 'rotasiReplacement';
                $compactVar['wip_id'] = $wipId;
                $compactVar['storeIds'] = $storeReoRotasi->pluck('id');
            }
            return view('master.tambahba', $compactVar);
        }
        if (!isset($param[1])) {
            return redirect()->back();
        }
        if (isset($param[3]) && $param[3] == 'replacement') {
            $store_id = $param[0];
            $brand_id = $param[1];
            $wip_id = $param[2];
            $baInWip = WIP::where('id', $wip_id)->where('store_id', $store_id)->where('status', 'replacement')->where('brand_id', $brand_id)->first();
            $inWip = WIP::where('ba_id', $baInWip->ba_id)->where('status', 'replacement')->get();

            $storeName = $inWip->map(function ($item) {
                return $item->store->store_name_1;
            })->toArray();
            $storeIds = $inWip->map(function ($item) {
                return $item->store->id;
            })->toArray();
            $storeAccount = $inWip->map(function ($item) {
                return $item->store->account_id;
            });

            $store = implode(' , ', $storeName);
            $joinDate = Carbon::parse($baInWip->effective_date)->format('d-m-Y');
            $status = (count($inWip) == 1) ? 'stay' : 'mobile';
            $tipe = 'replacement';
            $brand = Brand::find($brand_id);
            return view('master.tambahba', compact('wip_id', 'tipe', 'brand', 'agencies', 'store', 'joinDate', 'storeIds', 'status', 'branchAro', 'storeAccount'));
        }
        //Berdasarkan SDF


        $brand = Brand::find($param[1]);
        $status = 'stay';
        //list sdf dari reo
        $reo = Reo::where('user_id', Auth::id())->first()['id'];
        $sdf = SDF::whereHas('store', function ($query) use ($reo) {
            $query->where('reo_id', $reo);
        });
        if ($sdf->count() > 1) {
            $sdf = SDF::with('wip')->whereHas('wip', function ($query) use ($param) {
                $query->where([
                    ['ba_id', '=', null],
                    ['brand_id', '=', $param[1]],
                    ['fullfield', '=', 'hold']
                ]);
            })->where('id', $param[0]);
        }
        $token = $sdf->first()['token'];
        $cekMobile = SDF::with('store')->where('token', $token)
            ->whereHas('wip', function ($query) use ($param) {
                $query->where([
                    ['ba_id', '=', null],
                    ['brand_id', '=', $param[1]],
                    ['fullfield', '=', 'hold']
                ]);
            });
        if ($cekMobile->count() > 1) {
            $status = 'mobile';
        }
        $cekMobile = $cekMobile->get();
        $store = implode(' , ', $cekMobile->map(function ($item) {
            return $item->store->store_name_1;
        })->toArray());
        $storeIds = $cekMobile->pluck('store_id');
        $storeAccount = $cekMobile->pluck('store.account_id');
//        $nosdf = $param[0];
//        $sdf = SDF::where('no_sdf', $nosdf)->with('store')->get();
//        $store = implode(' , ', $sdf->map(function ($item) {
//            return $item->store->store_name_1;
//        })->toArray());
//        $storeIds = $sdf->map(function ($item) {
//            return $item->store->id;
//        })->toArray();
//        $status = (count($sdf) == 1) ? 'stay' : 'mobile';
        $joinDate = Carbon::parse($sdf->first()['first_date'])->format('d-m-Y');
        return view('master.tambahba', compact('brand', 'agencies', 'store', 'status', 'joinDate', 'rotasi', 'storeIds', 'branchAro', 'storeAccount'));
    }

    /**
     * Insert Data Baru Ba
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $foto_ktp = $request->file('foto_ktp');
        $foto_ktp_orig = time() . '-' . $foto_ktp->getClientOriginalName();
        $foto_ktp->move('attachment/ktp', $foto_ktp_orig);

        $pas_foto = $request->file('pas_foto');
        $pas_foto_orig = time() . '-' . $pas_foto->getClientOriginalName();
        $pas_foto->move('attachment/pasfoto', $pas_foto_orig);

        $foto_tabungan = $request->file('foto_tabungan');
        $foto_tabungan_orig = time() . '-' . $foto_tabungan->getClientOriginalName();
        $foto_tabungan->move('attachment/tabungan', $foto_tabungan_orig);

        $regionId = Branch_aro::with('arina_branch')->where('user_id', Auth::user()->id)->first()->arina_branch->region_id;

        if (isset($request->store_id)) {
            $store = collect($request->store_id)->map(function ($item) {
                return $this->getStore($item);
            })->map(function ($item) {
                return [
                    'channel' => $item->channel,
                    'city' => $item->city_id
                ];
            });
            if ($store->contains('channel', 'Dept Store') || $store->contains('channel', 'Drug Store')) {
                $class = 'Silver';
            } else {
                $class = 1;
            }
        }
        if ($this->isAro() && $request->status == 'rotasi') {
            $store = Store::where('region_id', $regionId)->where('store_no', 'rotasi');
            $class = 'Silver';
        }
        $rotasiCity = Store::whereHas('city', function ($query) {
            return $query->where('city_name', 'ROTASI');
        })->first()['id'];
        $city = (is_numeric($store->first()['city'])) ? $store->first()['city'] : $rotasiCity;

        $aroBranch = Branch_aro::whereHas('arina_branch', function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->first();
        // Division ID sekarang beranggapan 1 is CPD
        // Assumption Position & Professional ID 1 is BA ^-^
        $data = [
            'nik' => $request->nik,
            'name' => $request->name,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'rekening' => $request->rekening,
            'bank_name' => $request->bank_name,
            'brand_id' => $request->brand_id,
            'account' => $request->account,
            'status' => $request->status,
            'join_date' => date('Y-m-d', strtotime($request->join_date)),
            'birth_date' => date('Y-m-d', strtotime($request->birthdate)),
            'agency_id' => $request->agency_id,
            'uniform_size' => $request->uniform_size,
            'gender' => $request->gender,
            'education' => $request->education,
            'approval_reason' => '',
            'pas_foto' => $pas_foto_orig,
            'foto_ktp' => $foto_ktp_orig,
            'foto_tabungan' => $foto_tabungan_orig,
            'class' => $class,
            'area_id' => 1,
            'approval_id' => 0,
            'city_id' => $city,
            'division_id' => '1',
            'professional_id' => '1',
            'position_id' => '1',
            'arina_brand_id' => $request->arina_branch,
            'extra_keterangan' => $request->extra_keterangan ?: '',
            'join_date_mds' => $request->join_date_mds != null ? Carbon::parse($request->join_date_mds)->format('Y-m-d') : null
        ];
        $ba = Ba::create($data);
        if (!empty($request->store_id)) {
            $ba->store()->attach($request->store_id);
        } else if (isset($regionId)) {
            // $regionId gua rubah jadi request->status
            // regionID menandakan dia adalah ba rotasi
            $ba->store()->attach($store->first()->id);
        }
        $repl = WIP::where([
            'store_id' => $request->store_id,
            'fullfield' => 'hold',
            'ba_id' => null
        ])->with('replacement')->get()->map(function($item) {
            Replace::find($item->replacement->id)->update(['status' => 'Pending approval']);
        });
        if (isset($request->tipe) == 'replacement') {
            $replace = Replace::create([
                'ba_id' => $ba->id,
                'description' => '',
                'status' => 'Menggantikan BA yang lama'
            ]);
            collect($request->store_id)->map(function ($item) use ($ba, $replace) {
                $wip = WIP::where('store_id', $item)->where('fullfield', '!=', 'fullfield')->where('ba_id', '!=', null)->first();
                $replace->update(['description' => 'BA Baru Menggantikan ' . $wip->ba->name]);
                $wip->update([
                    'replace_id' => $replace->id,
                    'fullfield' => 'fullfield',
                    'ba_id' => $ba->id,
                    'reason' => 'BA Baru Menggantikan ' . $wip->ba->name,
                ]);
            });
        }
        if (isset($request->rotasiReplacement)) {
            $replace = Replace::create([
                'ba_id' => $ba->id,
                'description' => '',
                'status' => 'Menggantikan BA yang lama'
            ]);
            $wip = WIP::find($request->wip);
            $replace->update(['description' => 'BA Rotasi Baru Menggantikan ' . $wip->ba->name]);
            $wip->update([
                'replace_id' => $replace->id,
                'fullfield' => 'fullfield',
                'ba_id' => $ba->id,
                'reason' => 'BA Rotasi Baru Menggantikan ' . $wip->ba->name,
            ]);
            $regionStore = Store::where('region_id', $regionId)->where('store_no', 'like', '%rotasi%')->first()->id;
            BaSummary::findRotationReplacement($regionStore)->first()->update(['ba_id' => $ba->id]);
        }

        return redirect()->to('master/ba');
    }

    public function getStore($id)
    {
        if ($id == 0) $id = 144;
        return Store::findOrFail($id);
    }


    /**
     * Show BA Configuration Data dan filter filternya
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function configuration()
    {
        return view('configuration.ba');
    }

    /**
     * Filter BA buat yang di select2 ketika mau konfigurasi
     * @param BaFilter $filters
     * @return mixed
     */
    public function filterBa(BaFilter $filters)
    {
        return Ba::filter($filters)->get();
    }

    /**
     * Semua data master BA
     *
     * @param ConfigFilter $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function masterData(ConfigFilter $filters)
    {
        $masterData = Ba::masterFilter($filters);
        $onlyActive = ['resign', 'rolling', 'sp', 'master'];

        if (self::isReo()) {
            $masterData->showForReo(Auth::user()->id);
        }
        if (self::isAro()) {
            $masterData->showForAro(Auth::user()->id);
        }
        if (in_array($filters->getRequestType(), $onlyActive)) {
            $masterData->onlyActive();
        }
        if ($filters->getRequestType() == 'turnover') {
            $masterData->onlyResign();
        }

//        dd($masterData->onlyActive()->first());
        return Datatables::of($masterData)
            ->addcolumn('aksi', function ($item) use ($filters) {
                if ($item->approval->id == 2) {
                    if ($filters->getRequestType() == 'resign') {
                        return '<button type="button"  class="btn red-soft" onclick="exitFormClick(' . $item->id . ')">Exit Form</button>';
                    }
                    if ($filters->getRequestType() == 'rolling') {
                        return '<button type="button"  class="btn blue-hoki" onclick="rollingBaClick(' . $item->id . ')">Update</button>';
//                        if (Auth::user()->role == 'reo') {
//                            return '<button type="button"  class="btn blue-hoki" onclick="rollingBaClick(' . $item->id . ')">Rolling Ba</button>';
//                        }
//                        return '';
                    }
                    if ($filters->getRequestType() == 'sp') {
                        return '<button type="button"  class="btn yellow-lemon" onclick="spBaClick(' . $item->id . ')">SP BA</button>';
//                        if (Auth::user()->role == 'reo') {
//                        }
//                        return '';
                    }
                    return '<button type="button"  class="btn blue-hoki" onclick="testClick(' . $item->id . ')" >Menu BA</button>';
                }
                if ($item->approval->id == 8) {
                    return '<button type="button"  class="btn red-sunglo" onclick="joinBackBa(' . $item->id . ')"  >BA Rejoin</button>';
                }
                if ($item->approval->id == 5) {
                    return '<div class="btn-group-vertical margin-right-10">' .
                        '<button type="button"  class="btn red-sunglo" onclick="resignBaDetail(' . $item->id . ')"  >Show Reason</button>' .
                        '<button type="button"  class="btn purple-plum" onclick="rejoinBa(' . $item->id . ')"  >BA Rejoin</button>' .
                        '</div>';
                }
            })
            ->editColumn('join_date_mds', function ($item) {
                if ($item->join_date_mds == null) {
                    return "-";
                } else {
                    return $item->join_date_mds;
                }
            })
            ->editColumn('approval.id', function ($item) {
                switch ($item->approval->id) {
                    case 1:
                        return '<li class="list-group-item bg-purple-plum bg-font-blue namewrapper centerin"> Waiting for Loreal Approval </li>';
                    case 0:
                        return '<li class="list-group-item bg-blue-madison bg-font-blue namewrapper centerin"> Waiting for Arina Approval </li>';
                    case 2:
                        return '<li class="list-group-item bg-green-meadow bg-font-blue namewrapper centerin"> Active </li>';
                    case 3:
                        return '<li class="list-group-item bg-red-sunglo bg-font-blue namewrapper centerin"> Waiting Arina Resign Approval </li>';
                    case 4:
                        return '<li class="list-group-item bg-purple-plum bg-font-blue namewrapper centerin"> Waiting for Loreal Resign Approval </li>';
                    case 5:
                        return '<li class="list-group-item bg-red-sunglo bg-font-blue namewrapper centerin"> Resign </li>';
                    case 6:
                        return '<li class="list-group-item bg-yellow-lemon bg-font-blue namewrapper centerin"> Waiting Arina Maternity Leave Approval  </li>';
                    case 7:
                        return '<li class="list-group-item bg-yellow-soft bg-font-blue namewrapper centerin"> Waiting Loreal Maternity Leave Approval  </li>';
                    case 8:
                        return '<li class="list-group-item bg-green-sharp bg-font-blue namewrapper centerin"> Maternity Leave </li>';
                    case 9:
                        return '<li class="list-group-item bg-purple-plum bg-font-blue namewrapper centerin">Waiting Arina Rejoin Approval</li>';
                    case 10:
                        return '<li class="list-group-item bg-blue-madison bg-font-blue namewrapper centerin">Waiting Loreal Rejoin Approval</li>';
                }
            })
            ->editColumn('month', function ($item) {
                return substr($this->readableDateFormat($item->resign_at), 3);
            })
            ->editColumn('resign_at', function ($item) {
                return $this->readableDateFormat($item->resign_at);
            })
            ->editColumn('birth_date', function ($item) {
                return $this->readableDateFormat($item->birth_date);
            })
            ->editColumn('join_date', function ($item) {
                return $this->readableDateFormat($item->join_date);
            })
            ->addColumn('masa_kerja', function ($item) {
                return $this->excelHelper->exactTime(Carbon::now()->diff(new \DateTime($item->join_date)));
            })
            ->addColumn('historyStore',function ($item){
                return $item->history->pluck('store')->map(function ($store) {
                    return $store->store_name_1;
                })->implode(', <br /> ');
            })
            ->addColumn('historyStoreNumber',function ($item){
                return $item->history->pluck('store')->map(function ($store) {
                    return $store->store_no;
                })->implode(', <br /> ');
            })
            ->addColumn('historyCustomerId',function ($item){
                return $item->history->pluck('store')->map(function ($store) {
                    return $store->customer_id;
                })->implode(', <br /> ');
            })
            ->addColumn('historyChannel',function ($item){
                return @$item->history->first()->store->channel;
            })
            ->addColumn('historyaccount',function ($item){
                return $item->history->pluck('store')->map(function ($store) {
                    return $store['account']['name'];
                })->implode(', <br /> ');
            })
            ->addColumn('historyReo',function ($item){
                return @$item->history->first()->store['reo']['user']['name'];
            })
            ->addColumn('storeImplode', function ($item) {
                return $item->store->map(function ($store) {
                    return $store->store_name_1;
                })->implode(', <br /> ');
            })
            ->addColumn('channelImplode', function ($item) {
                return @$item->history->first()->store->channel;
            })
            ->addColumn('accountImplode', function ($item) {
                return $item->store->map(function ($store) {
                    return @$store->account->name;
                })->implode('  <br /> ');
            })
            ->make(true);
    }

    /**
     * Ambil data semua store pada satu BA
     * @param Ba $ba
     * @return Ba|\Illuminate\Database\Eloquent\Builder|Collection|\Illuminate\Database\Eloquent\Model
     */
    public function baStore(Ba $ba)
    {
        return Ba::with('store', 'brand', 'store.sp')->find($ba->id);
    }

    /**
     * Perollingan BA dari sebuah toko ke toko lain, integrated juga ke WIP juga
     *
     * @param RollingBaRequest $rollingRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function rollingBa(RollingBaRequest $rollingRequest)
    {
        $rollingRequest->rolling();

        return response()->json(['berhasil' => true]);
    }

    /**
     * Trigger Summary config table when changing ba brand
     *
     * @param Ba $ba
     * @param  $brandId
     * @return boolean updateData
     */
    public function triggerEditBrand(Ba $ba, $brandId)
    {
        return BaSummary::where('ba_id', $ba->id)
            ->where('brand_id', $ba->brand_id)
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->update(['brand_id' => $brandId]);
    }

    /**
     * Flow ketika REO mengisi resign
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resignBa(Request $request)
    {
        DB::transaction(function () use ($request) {
            $resignReason = $this->decideResignReason($request->alasanResign);
            $ba = Ba::find($request->id);
            $ba->update(['approval_id' => 3, 'resign_reason' => $resignReason, 'resign_at' => $request->efektifResign, 'resign_info' => $request->keteranganResign]);
            if (isset($request->tokoUserId)) {
                $headCount = 1 / count($request->tokoUserId);
                // bikin collection data untuk di input ke table exit form
                $collection = collect($request->tokoUserId)->map(function ($store) use ($request, $resignReason, $headCount, $ba) {
//                    return ['store_id' => $store, 'ba_id' => $request->id, 'status' => 'replacement', 'brand_id' => $request->brandId, 'fullfield' => 'hold', 'reason' => 'BA ' . $request->nama . ' Resign Dengan Alasan ' . $resignReason, 'filling_date' => $request->pengajuanRequest, 'effective_date' => $request->efektifResign,
                    if ($request->takeoutStore) {
                        $this->removeTakeoutFromConfiguration($store, $ba->brand_id, $ba->id);
                        $this->removeForStoreConfiguration($store);
                    }

                    return ['store_id' => $store, 'ba_id' => $request->id, 'status' => 'replacement', 'brand_id' => $request->brandId, 'fullfield' => 'hold', 'reason' => 'Replace BA ' . $request->nama, 'filling_date' => $request->pengajuanRequest, 'effective_date' => $request->efektifResign,
                        'created_at' => Carbon::now('Asia/Jakarta'), 'updated_at' => Carbon::now('Asia/Jakarta'), 'head_count' => $headCount];
                })->toArray();

                $stores = collect($request->tokoUserId)->map(function ($storeId) {
                    return Store::find($storeId)->store_name_1;
                })->toArray();
                ExitForm::create(['ba_id' => $request->id, 'stores' => implode(' , ', $stores), 'join_date' => $request->joinDate, 'alasan' => $resignReason, 'filling_date' => $request->pengajuanRequest, 'effective_date' => $request->efektifResign, 'pending' => 1, 'resign_info' => $request->keteranganResign ]);

                if (!$request->takeoutStore) WIP::insert($collection);
            }
            Notification::create([
                'name' => "{$ba['name']} resign dan butuh approval anda!",
                'message' => 'BA Resign',
                'status' => 'resign',
                'ba_id' => $request->id,
                'role' => 'arina',
                'read' => 0,
                'icon' => 'fa fa-user-times'
            ]);
        });
        return response()->json(['berhasil' => true]);
    }

    /**
     * Flow ketika Ba izin hamil
     *
     * @param Ba $ba
     * @return \Illuminate\Http\JsonResponse
     */
    public function maternityLeave(Ba $ba)
    {
        $brand = strtolower($ba->brand->name);
        $wipCheck = collect($ba->store)
            ->map(function ($item) use ($ba, $brand) {
                return ['store_id' => $item->id, 'ba_id' => $ba->id, 'status' => 'replacement', 'brand_id' => $ba->brand_id, 'fullfield' => 'hold', 'reason' => 'Ba ' . $ba->name . ' Cuti Hamil', 'filling_date' => Carbon::now('Asia/Jakarta'), 'effective_date' => Carbon::now('Asia/Jakarta'), 'created_at' => Carbon::now('Asia/Jakarta'), 'updated_at' => Carbon::now('Asia/Jakarta')];
            })->toArray();
        // Bagian ini kalau memang dibutuhkan kalkulasi vacant atau tidak
//                    ->flatMap(function ($item) use ($ba, $brand) {
//                        return Store::actualBaOneBrand($item->id, $brand, $ba->brand_id)->get();
//                    })->filter(function ($item) use ($brand, $ba) {
//                        return ($item['alokasi_ba_' . $brand] - ($item->ba_count) == 0);
//                    })
//                    ->flatten()
        DB::transaction(function () use ($wipCheck, $ba) {
            $ba->approval_id = 6;
            $ba->save();
            if ($wipCheck != null) {
                WIP::insert($wipCheck);
            }
            Notification::create([
                'name' => 'Maternity Leave for BA your approval',
                'message' => 'Maternity Leave Ba',
                'status' => 'hamil',
                'ba_id' => $ba->id,
                'role' => 'arina',
                'read' => 0
            ]);
        });
        return response()->json(['status' => true]);
    }

    /**
     * Fungsi untuk mengatur ketika ba join kembali dari cuti hamil
     *
     * @param RollingBaRequest|Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinBackBa(RollingBaRequest $request)
    {
        $request->joinBack();

        return response()->json(['status' => true]);
    }

    /**
     * Ambil semua BA pada toko yang ada di Wip berdasarkan store id
     *
     * @param Store $store
     * @param Brand $brand | null
     * @return \Illuminate\Support\Collection
     */
    public function findBaInWip(Store $store, Brand $brand = null)
    {
        $wip = WIP::with('ba', 'store', 'sdf')->where('store_id', $store->id)->where('fullfield', 'hold')
            ->where('status', 'replacement')
            ->orWhere(function ($query) use ($store) {
                return $query->where('store_id', $store->id)
                    ->where('status', 'new store')
                    ->whereNull('ba_id');
            })
//            ->when($brand != null, function ($query) use ($brand) {
//               return  $query->where('brand_id', '!=', $brand->id);
//            })
            ->get();

        return $wip->map(function ($wip) {
            if ($wip->ba_id != null) {
                return [
                    'ba_id' => $wip->ba->id,
                    'ba_name' => $wip->ba->name . ' Pada Brand ' . $wip->brand->name,
                    'wip_id' => $wip->id
                ];
            } else {
                return [
                    'ba_id' => $wip->id,
                    'ba_name' => "Rolling Menuju Toko Baru " . $wip->store->store_name_1 . ' Pada Brand ' . $wip->brand->name,
                    'wip_id' => $wip->id
                ];
            }
        });
    }

    /**
     * Exporting data report konfigurasi menjadi format excel
     *
     * @param ConfigFilter $filters
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(ConfigFilter $filters)
    {
        $excelData = BaSummary::configurationData($filters, $filters->month(), $filters->year());
        if (self::isReo()) {
            $excelData->showForReo(Auth::user()->id);
        }
        if (self::isAro()) {
            $excelData->showForAro(Auth::user()->id);
        }
        Excel::create('Data Konfigurasi BA', function ($excel) use ($filters, $excelData) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Ba', function ($sheet) use ($filters, $excelData) {
                $sheet->setAutoFilter('A1:AC1');
                $sheet->setHeight(1, 25);
                $sheet->fromModel($this->excelHelper->mapForExcel($excelData->get()), null, 'A1', true, true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:AC1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:AC1', 'thin');
            });

        })->export('xlsx');
    }

    /**
     * Tampilin data ba yang mau di approve
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function approval(Request $request, $id = null, $isResign = false)
    {
        $role = Auth::user()->role;
        //Kalo dia reo, then redirect back
        if ($role == 'reo') {
            return redirect()->back();
        }

        /**
         * Note :
         * 0 = waiting arina approval
         * 1 = waiting loreal approval
         * 2 = approved
         * 3 = waiting resign approval (arina)
         * 4 = waiting resign approval (loreal)
         * 5 = resign
         */
        //Tentuin approval ID nya
        $approval_resign_id = ($role == 'arina') ? 3 : 4;
        $approval_newBa_id = ($role == 'arina') ? 0 : 1;
        $approval_baMaternity_id = ($role == 'arina') ? 6 : 7;
        $approval_rejoin_id = ($role == 'arina') ? 9 : 10;

        //Jumlahain total ba yang mau di approve
        $baNew = Ba::where('approval_id', $approval_newBa_id);
        $baResign = Ba::where('approval_id', $approval_resign_id);
        $baMaternity = Ba::where('approval_id', $approval_baMaternity_id);
        $baRejoin = Ba::where('approval_id', $approval_rejoin_id);

        //All Data
        if ($id == null) {
            $baNew = $baNew->count();
            $baResign = $baResign->count();
            $baMaternity = $baMaternity->count();
            $baRejoin = $baRejoin->count();
            return view('master.ba-approval', compact('baRejoin', 'approval_rejoin_id', 'baNew', 'request', 'baMaternity', 'baResign', 'approval_baMaternity_id', 'approval_resign_id', 'approval_newBa_id'));
        }

        //Single Page
        try {
            $bas = Ba::with('store', 'exitForm')->findOrFail($id);
            $isResign = ($isResign == 'resign') ? true : false;
            if ($isResign) $wip = $this->findWIP($bas, $bas->store);
            #return $wip;
        } catch (\Exception $e) {
            //FIXME: Cari wip sesuai ba yang bersangkutan

            #return $e->getMessage() . ' <br> on line ' . $e->getLine();
            return redirect('/master/ba');
        }
        return view('master.ba-approval-wizard', compact('bas', 'isResign', 'wip'));
    }

    /**
     * Ngambil datatable buat approval berdasarkan parameter $approvl_id
     * @param Ba $ba
     * @param $approval
     * @return mixed
     */
    public function datatableApproval(Ba $ba, $approval)
    {
        if ($approval == 'sp') {
            $ba = $ba->where('status_sp', '!=', null)->where('sp_approval', 'pending');
        } else {
            $ba = $ba->where('approval_id', $approval);
        }
        return Datatables::of($ba)
            ->withTrashed()
            ->addColumn('approve', function ($ba) use ($approval) {
                $isResign = ($ba->approval_id == 0 || $ba->approval_id == 1) ? null : 'resign';
                $isSp = ($approval == 'sp') ? " onclick='approveSP($ba->id )' " : " href='/master/ba/approval/$ba->id/$isResign' ";
                if ($approval == '6' || $approval == '7') {
                    return "<a onclick='approveMaternity($ba->id)' class='btn green-meadow'> Approve </a>";
                } elseif ($approval == '9' || $approval == '10') {
                    return "<a href='/master/ba/approval/$ba->id/rejoin' class='btn green-meadow'> Approve </a>";
                }
                return "<a $isSp class='btn green-meadow'> Approve </a>";
            })
            ->editColumn('join_date', function ($ba) {
                return $ba->join_date->format('d/m/Y');
            })
            ->make(true);
    }

    /**
     * Proses approve BA
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approved(Request $request, $id)
    {
        $isChanged = null;
        $isResign = $request->exists('resign');
        $isRejoin = $request->exists('rejoin');

        $role = Auth::user()->role;
        //Tentuin approval ID nya
        $approval_resign_id = ($role == 'arina') ? 5 : 5;
        $approval_newBa_id = ($role == 'arina') ? 2 : 2;
        $approval_rejoin_id = ($role == 'arina') ? 10 : 2;
        $ba = Ba::find($id);
        if (count($request->all())) {
            $isChanged = true;
            $ba->update($request->all());
        }
        if ($isRejoin) {
            $ba->approval_id = $approval_rejoin_id;
        } else {
            $ba->approval_id = ($isResign) ? $approval_resign_id : $approval_newBa_id;
        }
        $ba->save();
        if ($ba->approval_id == 5) {
            $this->saveHistoryToko($ba, $ba->store, 'resign', true);
        }
        self::notify($role, $id, $isChanged);
        if (!$isResign && $approval_newBa_id == 2 && WIP::where('ba_id', $id)->first() == null && $ba->status != 'rotasi') $this->updateWIP($ba->store, $ba);
        return response()->json(['status' => true]);
    }


    /**
     * Rejecting Inputted BA From Aro
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {
        $ba = Ba::find($id);

        if (in_array($ba->approval_id, [0, 1])) {
            $ba->store()->detach();

            $ba->delete();

        } elseif (in_array($ba->approval_id, [3, 4, 6, 7])) {
            WIP::rejectResign($ba)->delete();

            $ba->update(['approval_id' => 2]);

        } elseif (in_array($ba->approval_id, [9, 10])) {
            BaSummary::rejectRejoin($ba)->update(['ba_id' => 0]);

            WIP::rejectRejoin($ba)
                ->update(['ba_id' => null, 'replace_id' => null, 'reason' => ' Ba ' . $ba->name . ' di reject untuk masuk kembali']);

            $ba->store()->detach();

            $ba->update(['approval_id' => 5]);
        }

        Notification::create([
            'name' => "BA $ba->name telah di reject oleh " . ucfirst(Auth::user()->role),
            'message' => "BA telah di reject, silahkan mencari kandidat kembali.",
            'status' => 'info',
            'ba_id' => $ba->id,
            'role' => 'aro',
            'read' => 0,
            'icon' => 'fa fa-user-times'
        ]);

        return response()->json(['status' => true]);
    }

    public function maternity($id)
    {
        $ba = Ba::find($id);
        if (Auth::user()->role == 'arina') {
            $ba->approval_id = 7;
        } else {
            $ba->approval_id = 8;
            $this->saveHistoryToko($ba, $ba->store, 'cuti', true);
        }
        $ba->save();

        return response()->json(['status' => true]);

    }

    public function notify($role, $baId, $isChanged = null)
    {
        Notification::create([
            'name' => 'BA sudah di approve oleh ' . $role,
            'message' => (!empty($isChanged)) ? $role . 'BA sudah di approve dan dirubah oleh ' . $role : '',
            'status' => 'info',
            'ba_id' => $baId,
            'role' => 'reo',
            'read' => 0,
            'icon' => 'fa fa-check-circle-o'
        ]);
    }

    public function findWIP($ba, $store)
    {
        $wip = WIP::where([
            ['ba_id', '=', $ba->id],
            ['store_id', '=', $store->first()->id],
            ['brand_id', '=', $ba->brand_id],
            ['status', '=', 'replacement'],
            ['fullfield', '=', 'hold'],
            ['reason', '!=', null]
        ])->first();

        return $wip;
    }

    /**
     * Update WIP bahwa permintaan sudah ditempati, kira kira seperti itu ( ihavenoidea )
     * @param $store
     * @param $ba
     * @return mixed
     */
    public function updateWIP($store, $ba)
    {
        $replaceData = ['ba_id' => $ba->id, 'description' => '', 'status' => 'Lulus'];
        $replace = Replace::create($replaceData);
        return collect($store)->map(function ($item) use ($ba, $replace) {
            $wip = WIP::where('store_id', $item->id)
                ->where('fullfield', 'hold')
//                        ->whereHas('replacement', function ($query) {
//                            return $query->whereNotNull('candidate')
//                                         ->where('status', 'Lulus');
//                        })
                ->where('brand_id', $ba->brand_id)
                ->firstOrFail();
            return $wip->update([
                'fullfield' => 'fullfield',
                'ba_id' => $ba->id,
                'replace_id' => $replace->id
            ]);
        });
    }

    /**
     * Save History Toko untuk Ba
     *
     * @param $ba
     * @param $stores
     * @param $status
     * @param bool $detach
     * @return \Illuminate\Support\Collection
     */
    public function saveHistoryToko($ba, $stores, $status, $detach = false)
    {
        return collect($stores)->map(function ($store) use ($ba, $status, $detach) {
            BaHistory::create(['ba_id' => $ba->id, 'status' => $status, 'store_id' => $store->id]);
            if ($detach) {
                $ba->store()->detach($store->id);
            }
        });
    }

    /**
     * Tampilan edit BA untuk REO
     *
     * @param Ba $ba
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editBa(Ba $ba)
    {
        $ba = Ba::with('store', 'brand')->find($ba->id);
        return view('master.ba-edit-wizard', compact('ba'));
    }

    public function giveSp(Request $request)
    {
        $checkSp = Ba::where('id', $request->ba)->first();
        $updateStatus = ($request->statusSp) ?: 'SP1';
        $checkSp->update(['status_sp' => $updateStatus, 'tanggal_sp' => Carbon::now('Asia/Jakarta'), 'sp_approval' => 'approve', 'keterangan_sp' => $request->keteranganSp]);
        return json_encode(['status' => true]);
    }

    public function punishSP($id)
    {
        $checkSp = Ba::find($id);
        $checkSp->update(['sp_approval' => 'approve']);
        Notification::create([
            'name' => "Permintaan SP kepada BA $checkSp->name telah disetujui",
            'message' => "BA $checkSp->name telah diberikan SP!",
            'status' => 'sp',
            'role' => 'loreal',
            'ba_id' => $checkSp->id,
            'read' => 0,
            'icon' => 'fa fa-exclamation'
        ]);
        return json_encode(['status' => true]);
    }

    /**
     * Update data ba yang di edit sama Reo
     *
     * @param Request $request
     * @param Ba $ba
     * @return \Illuminate\Http\JsonResponse
     */
    public function edited(Request $request, Ba $ba)
    {
        if (count($request->all())) {
            if ($request->has('brand_id') && $request->brand_id != $ba->brand_id) {
                $baCount = 1 / count($ba->store);
                $ba->store->map(function ($item) use ($request, $ba, $baCount) {
                    $updateBrand = $this->alokasiBa(Brand::find($request->brand_id)->name);
                    $reduceBrand = $this->alokasiBa($ba->brand->name);
                    $updateData = [];
                    $updateData[$updateBrand] = $item->{$updateBrand} += $baCount;
                    $updateData[$reduceBrand] = $item->{$reduceBrand} -= $baCount;
                    Store::find($item->id)->update($updateData);
                });
                $this->triggerEditBrand($ba, $request->brand_id);
            }
            $ba->update($request->all());
            $detail = collect($request->all())->map(function ($value, $key) {
                return 'Merubah ' . strtoupper($key) . ' Menjadi ' . $value;
            })->implode(' , ');
            Notification::create([
                'name' => 'Pengeditan Data Ba',
                'message' => 'Pengeditan Data Ba Atas nama ' . $ba->name . ' Dengan Detail ' . $detail,
                'status' => 'info',
                'ba_id' => $ba->id,
                'role' => 'arina',
                'read' => 0,
                'icon' => 'fa fa-info'
            ]);
        }
        return response()->json(['success' => true]);

    }

    /**
     * Tampilkan data history Ba yang ada di server
     *
     * @param Ba $ba
     * @return mixed
     */
    public function historyBa(Ba $ba)
    {
        return BaHistory::where('ba_id', $ba->id)
            ->with('ba', 'store')
            ->get()
            ->map(function ($item) {
                $item['history_time'] = $this->readableDateFormat($item->created_at);
                return $item;
            })
            ->sortBy('history_time')
            ->groupBy('history_time')->map(function ($item) {
                return $item->groupBy('status');
            });

    }

    /**
     * Get the WIP list of rotation BA if exists
     *
     * @return mixed
     */
    public function rotasiWip()
    {
        return WIP::whereHas('ba', function ($query) {
            return $query->where('bas.status', 'rotasi');
        })->whereHas('store', function ($query) {
            $regionId = Branch_aro::with('arina_branch')->where('user_id', Auth::user()->id)->first()->arina_branch->region_id;
            return $query->where('region_id', $regionId)->where('store_no', 'like', '%rotasi%');
        })->where('fullfield', '!=', 'fullfield')
            ->with('ba')->get();
    }

    /**
     * View buat new BA
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newBaSdf(Request $request)
    {
        return view('master.form.ba');
    }

    /**
     * Exit Form View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function exitForm()
    {
        return view('master.exitForm');
    }

    /**
     * Rolling Page Ba
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rollingView()
    {
        return view('master.rolling');
    }


    /**
     * Rolling Approval Ba
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rollingApprovalPage()
    {
        return view('master.rollingApproval');
    }


    /**
     * Archive Exit Form Ba
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function archiveView()
    {
        return view('master.resignArchive');
    }

    /**
     * Data For ArchiveData form exit form
     *
     * @return mixed
     */
    public function archiveData()
    {
        return Datatables::of(ExitForm::with('ba')->get())
            ->addcolumn('aksi', function ($item) {
                return '<a type="button"  class="btn blue-hoki" href="/downloadArchive/' . $item->id . '">Download Exit Form</a>';
            })->make(true);
    }

    /**
     * Download PDF Exit Form
     *
     * @param ExitForm $exitForm
     * @return \Illuminate\Http\Response
     */
    public function downloadArchive(ExitForm $exitForm)
    {
        $resign = ExitForm::with('ba.brand')->find($exitForm->id);
        $pdfName = 'Exit Form Ba ' . $resign->ba->name . '.pdf';
        $pdf = PDF::loadView('pdf.resign-pdf', compact('resign'));
        return $pdf->download($pdfName);
    }

    /**
     * Showing Sp View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function spView()
    {
        return view('master.sp');
    }

    /**
     * Sp Document View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function documentSp()
    {
        return view('master.spDocument');
    }

    /**
     * Datatable data set for document Sp
     *
     * @param SpFilter $filter
     * @return mixed
     */
    public function documentSpData(SpFilter $filter)
    {
        $data = Ba::spHistory($filter);

        return Datatables::of($data)
            ->editColumn('tanggal_sp', function ($item) {
                return $this->readableDateFormat($item->tanggal_sp);
            })
            ->addcolumn('stores', function ($item) {
                return implode(',', $item->store->pluck('store_name_1')->toArray());
            })
            ->addcolumn('download', function ($item) {
                if ($item->foto_sp != null) {
                    return '<a type="button"  class="btn blue-hoki" href="/downloadSuratSp/' . $item->id . '">Download Surat SP</a>';
                }
                return ' ';
            })
            ->addcolumn('upload', function ($item) {
                if (Auth::user()->role == 'arina' && $item->sp_approval == 'approve') {
                    return '<button type="button"  class="btn red-soft" onclick="uploadClick(' . $item->id . ')">Upload Surat Sp</button>';
                }
            })->make(true);
    }

    public function exportSpData(SpFilter $filter)
    {
        $data = Ba::spHistory($filter);

        return Excel::create('Data SP BA', function ($excel) use ($filter, $data) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Ba', function ($sheet) use ($filter, $data) {
                $sheet->setAutoFilter('A1:I1');
                $sheet->setHeight(1, 25);
                $sheet->fromModel($this->excelHelper->mapForSp($data->get()), null, 'A1', true, true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:I1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:I1', 'thin');
            });

        })->export('xlsx');


    }

    /**
     * Upload Surat SP Ba
     *
     * @param Request $request
     * @param Ba $ba
     * @return bool
     */
    public function uploadSuratSP(Ba $ba, Request $request)
    {
        $foto_sp = $request->file('attachment');
        $foto_sp_orig = time() . '-' . $foto_sp->getClientOriginalName();
        $foto_sp->move('attachment/sp', $foto_sp_orig);
        $ba->update(['foto_sp' => $foto_sp_orig]);
        return response()->json(['success' => true]);
    }

    /**
     * Downloading surat sp
     *
     * @param Ba $ba
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSuratSp(Ba $ba)
    {
        return response()->download(public_path('attachment/sp/' . $ba->foto_sp), $ba->foto_sp);
    }

    /**
     * Showing Turn Over View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function turnoverView()
    {
        return view('master.turnover');
    }

    /**
     * Exporting Turn Over data
     *
     * @param ConfigFilter $filters
     * @return Excel File
     */
    public function exportTurnOver(ConfigFilter $filters)
    {
        $masterData = Ba::masterFilter($filters);
        $onlyActive = ['resign', 'rolling', 'sp'];

        if (self::isReo()) {
            $masterData->showForReo(Auth::user()->id);
        }
        if (self::isAro()) {
            $masterData->showForAro(Auth::user()->id);
        }
        if (in_array($filters->getRequestType(), $onlyActive)) {
            $masterData->onlyActive();
        }
        if ($filters->getRequestType() == 'turnover') {
            $masterData->onlyResign();
        }

        return Excel::create('Data Turn Over BA', function ($excel) use ($filters, $masterData) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Ba', function ($sheet) use ($filters, $masterData) {
                $sheet->setAutoFilter('A1:P1');
                $sheet->setHeight(1, 25);
                $sheet->fromModel($this->excelHelper->mapForTurnOver($masterData->get()), null, 'A1', true, true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:P1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:P1', 'thin');
            });

        })->export('xlsx');


    }

    /**
     * Ambil data SP dari toko yang dipilih
     *
     * @param Ba $ba
     * @return mixed
     * @internal param Request $request
     */
    public function findSpFromBa(Ba $ba)
    {
        return $ba->with('store');
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


    /**
     * Helper to determine the login user is aro or not
     *
     * @return bool
     */
    public function isAro()
    {
        return Auth::user()->role == 'aro';
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
     * Check Whether the store in the brand has already got a mobile Ba by checking the allocation if decimal
     *
     * @param $allocation
     * @return bool
     */
    public function hasMobileBa($allocation)
    {
        return is_numeric($allocation) && floor($allocation) != $allocation;
    }
    public function BAexport()
    {
        $data = BA::with('agency', 'arinaBrand', 'store.city', 'store.reo.user', 'store.region', 'store.account', 'brand')->get();
        return Excel::create("Data BA ".date('Y'), function ($excel) use ($data) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Ba', function ($sheet) use ($data) {
                $sheet->setAutoFilter('A1:R1');
                $sheet->setHeight(1, 25);
                $sheet->fromModel($this->excelHelper->mapAllBAExport($data), null, 'A1', true, true);
                $sheet->row(3, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:R1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:R1', 'thin');
            });

        })->export('xlsx');


    }
}