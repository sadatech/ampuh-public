<?php

namespace App\Http\Controllers\Configuration;

use App\Filter\WIPFilter;
use App\Helper\ExcelHelper;
use App\Notification;
use App\Reo;
use App\Replace;
use App\SDF;
use App\WIP;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Facades\Datatables;

class WipController extends Controller
{
    protected $excelHelper;

    public function __construct(ExcelHelper $excelHelper)
    {
        $this->excelHelper = $excelHelper;
    }

    public function index(WIP $wip)
    {
        $role = Auth::user()->role;
        return view('configuration.wip', compact('role'));
    }

    public function find($storeId)
    {
        return WIP::where('store_id', $storeId)->where('fullfield', 'not like', 'fullfield')->first();
    }


    /**
     * Tampilin WIP liwat JSON
     *
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request, WIPFilter $filter)
    {
        $wip = WIP::filter($filter, $request);
        return Datatables::of($wip)
            ->editColumn('filling_date', function ($item) {
                return $this->excelHelper->readableDateFormat($item['filling_date']);
            })
            ->editColumn('effective_date', function ($item) {
                return $this->excelHelper->readableDateFormat($item['effective_date']);
            })
            ->editColumn('ba.name', function ($item) {
                return ($item->fullfield == 'fullfield' && $item->ba != null) ? $item->ba->name : 'vacant';
            })
            ->editColumn('replacement.created_at', function ($item) {
                return ($item->status == 'replacement' || $item['replacement'] == null) ? ' ' : $this->excelHelper->readableDateFormat($item['replacement']['created_at']);
            })
            ->editColumn('status', function ($item) {
                return ($item->status == 'new store' ) ? 'New Allocation' : $item->reason;
            })
            ->addColumn('candidate', function ($item) {
                if (isset($item->replacement->ba_id)) {
                    return ($item->replacement->baReplace->name);
                } elseif (isset($item->replacement->candidate)) {
                    return $item->replacement->candidate;
                }
            })
            ->addColumn('hc', function ($item) {
                return number_format($item->head_count, 4) + 0;
            })
            ->addColumn('edit', function ($item) {
                if ($item->fullfield == 'fullfield') {
                    return '<button class="btn green-meadow" disabled>Edit  </button>';
                }
                return '<a href="javascript:;" class="btn green-meadow edit">Edit  </a>';
            })
            ->editColumn('replacement.updated_at', function ($item) {
                return $this->excelHelper->readableDateFormat($item['replacement.updated_at']);
            })
            ->editColumn('replacement.interview_date', function ($item) {
                return $this->excelHelper->readableDateFormat($item['replacement']['interview_date']);
            })
            ->make(true);
    }

    public function export()
    {

        $wip = WIP::with('store.city', 'store.account', 'brand', 'sdf', 'replacement.baReplace')
            ->whereHas('ba', function ($query) {
                return $query->whereNotIn('approval_id', [3, 4]);
            })
            ->orWhere('ba_id', null)
            ->get();

        #return $wip;
        Excel::create(date('dmY') . ' Work In Progress', function ($excel) use ($wip) {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('WIP', function ($sheet) use ($wip) {
                $sheet->freezeFirstRow();
                $sheet->setAutoFilter('A2:O2');
                $sheet->setHeight(2, 25);
                $sheet->fromModel($this->excelHelper->mapWIPtoExcel($wip), null, 'A2', true, true);
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('J1:O1');
                $sheet->cells('A1:I1', function ($cells) {
                    $cells->setBackground('#ff0000');
                });
                $sheet->cells('J1:O1', function ($cells) {
                    $cells->setBackground('#92d050');
                });
                $sheet->row(2, function ($row) {
                    $row->setBackground('#82abde');
                });

                $sheet->cell('J1', function ($cell) {
                    // manipulate the cell
                    $cell->setValue('Kolom yang harus diisi jika BA sudah terisi');
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(15);

                });

                $sheet->cells('A2:N2', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:N1', 'thin');
            });

        })->export('xlsx');
    }

    public function getReoID()
    {
        $userid = Auth::id();
        $reo = Reo::where('user_id', $userid)->select('id')->first();
        return $reo->id;
    }

    public function edit(Request $request)
    {
        // FIXME: kalau ga diedit di restore aja data sebelumnya, biar ga kosong
        $interview_date = $request->tanggal_interview;
        if ($interview_date == '' or $interview_date == ' ') $interview_date = null;
        $wip = WIP::with('store', 'sdf')->find($request->id);
        $replace = Replace::create([
            'interview_date' => $interview_date,
            'candidate' => $request->nama_kandidat,
            'description' => $request->keterangan,
            'status' => $request->status_interview
        ]);
        $wip->replace_id = $replace->id;
        $wip->save();
        //Also change related sdf
        $tokenSDF = $wip->sdf->token;
         SDF::with('wip')->where('token', $tokenSDF)->get()->map(function ($item) use ($wip){
            $replaceid = $wip->replace_id;
            WIP::find($item->wip[0]->id)->update(['replace_id' => $replaceid]);
        });

        if (Auth::user()->role == 'arina' || Auth::user()->role == 'aro') {
            if ($request->exists('nama_kandidat')) {
                Notification::create([
                    'name' => "Ada kandidat baru pada toko {$wip->store->store_name_1}",
                    'message' => "Aro telah menambahkan kandidat $replace->candidate pada toko {$wip->store->store_name_1}",
                    'status' => 'info',
                    'role' => 'reo',
                    'read' => 0,
                    'wip_id' => $wip->id,
                    'icon' => 'fa fa-exclamation'
                ]);
            }
        } else if (Auth::user()->role == 'reo') {
            if ($replace->status == 'Lulus') {
                Notification::create([
                    'name' => 'BA telah lulus interview',
                    'message' => "Ba atas nama $replace->candidate telah lulus interview",
                    'status' => 'info',
                    'role' => 'aro',
                    'read' => 0,
                    'wip_id' => $wip->id,
                    'icon' => 'fa fa-check'
                ]);
            } else {
                Notification::create([
                    'name' => 'BA tidak lulus interview',
                    'message' => "BA atas nama $replace->candidate tidak lulus interview",
                    'status' => 'info',
                    'role' => 'aro',
                    'read' => 0,
                    'wip_id' => $wip->id,
                    'icon' => 'fa fa-times'

                ]);
            }
        }

        $wip->load('replacement');
        $progress = 'wip';
        if( $request->exists('nama_kandidat') AND $replace->status == 'Lulus') $progress = 'done';
        return [
            'progress' => $progress,
            'data' => $wip
        ];
    }

    /**
     * Approve Rolling by destination store reo
     *
     * @param WIP $wip
     * @return mixed
     */
    public function approveRolling(WIP $wip)
    {
        return $wip->where('ba_id', $wip->ba_id)
            ->where('brand_id', $wip->brand_id)
            ->where('pending', 1)
            ->update(['pending' => 0]);
    }
}
