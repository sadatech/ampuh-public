<?php

namespace App\Http\Controllers\Utilities;

use App\Notification;
use App\WIP;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Matcher\Not;
use Symfony\Component\VarDumper\Cloner\Data;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class NotificationController
 * @package App\Http\Controllers\Utilities
 */
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('utilities.notification');
    }

    /**
     * Nampilin data pake datatable json
     *
     * @return json datatable
     */
    public function datatable()
    {

        if (@Auth::user()->role == 'reo') {
            $notifications = DB::table('notifications')
                ->leftjoin('wips', 'notifications.wip_id', '=', 'wips.id')
                ->leftjoin('ba_store', 'notifications.ba_id', '=', 'ba_store.ba_id')
                ->leftjoin('stores', function ($join) {
                    $join->on('ba_store.store_id', 'stores.id');
                    $join->orOn('wips.store_id', 'stores.id');
                })->join('reos', 'stores.reo_id', '=', 'reos.id')
                ->where([
                    ['user_id', Auth::id()],
                    ['read', '=', 0],
                    ['role', '=', 'reo']
                ])
                ->select('notifications.*')->get();
        } else if (@Auth::user()->role == 'aro') {

            $notifications = DB::table('notifications')
                ->leftjoin('notification_sdfs', 'notifications.id', 'notification_sdfs.notification_id')
                ->leftjoin('wips', 'notifications.wip_id', '=', 'wips.id')
                ->leftjoin('stores', 'stores.id', '=', 'wips.store_id')
                ->join('cities', function ($join) {
                    $join->on('notification_sdfs.city_id', '=', 'cities.id');
                    $join->orOn('stores.city_id', '=', 'cities.id');
                })->join('branch_aros', 'cities.branch_id', 'cities.branch_id')
                ->where([
                    ['user_id', Auth::id()],
                    ['read', '=', 0],
                    ['role', '=', 'aro']
                ])->select('notifications.*')->get();
        } else if (@Auth::user()->role == 'loreal') {
            $notifications = Notification::where([
                ['read', '=', 0],
                ['role', '=', 'loreal']
            ])->get();
        } else {

            $notifications = Notification::where([
                ['read', '=', 0],
                ['role', '=', 'arina']
            ])->get();
        }


        $datatable = Datatables::of($notifications)
            ->addColumn('detail', function ($notif) {
                return "<a href='#' id='$notif->id' onclick='Sdf($notif->id, \"$notif->role\",\"$notif->status\")' class=\"btn green-meadow\">Open  </a>";
            })
            ->make(true);
        return $datatable;
    }

    public function openNotif(Request $request, $id)
    {
        $notifications = Notification::find($id);
        $notifications->read = $request->read;
        $notifications->save();
        return response()->json($notifications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function datanotif()
    {
        if (@Auth::user()->role == 'arina' || @Auth::user()->role == "loreal") {
            $notifications = Notification::where('read', 0)->where('role', @Auth::user()->role);
        } else if (@Auth::user()->role == 'reo') {
            $notifications = DB::table('notifications')
                ->leftjoin('wips', 'notifications.wip_id', '=', 'wips.id')
                ->leftjoin('ba_store', 'notifications.ba_id', '=', 'ba_store.ba_id')
                ->leftjoin('stores', function ($join) {
                    $join->on('ba_store.store_id', 'stores.id');
                    $join->orOn('wips.store_id', 'stores.id');
                })->join('reos', 'stores.reo_id', '=', 'reos.id')
                ->where([
                    ['user_id', Auth::id()],
                    ['read', '=', 0],
                    ['role', '=', 'reo']
                ])
                ->select('notifications.*')->groupBy('notifications.id');
//            dd($notifications->toSql());

        } else {
            $notifications = DB::table('notifications')
                ->leftjoin('notification_sdfs', 'notifications.id', 'notification_sdfs.notification_id')
                ->leftjoin('wips', 'notifications.wip_id', '=', 'wips.id')
                ->leftjoin('stores', 'stores.id', '=', 'wips.store_id')
                ->join('cities', function ($join) {
                    $join->on('notification_sdfs.city_id', '=', 'cities.id');
                    $join->orOn('stores.city_id', '=', 'cities.id');
                })->join('branch_aros', 'cities.branch_id', 'cities.branch_id')
                ->where([
                    ['user_id', Auth::id()],
                    ['read', '=', 0],
                    ['role', '=', 'aro']
                ])->select('notifications.*')->groupBy('notifications.id');

        }

        return ['count' => $notifications->get()->groupBy('id')->count(), 'data' => $notifications->get()];
    }


    /**
     * Find WIp which needs to be approved by reo
     *
     * @return mixed
     */
    public function findRollingApproval()
    {
        $notificationRolling = Notification::whereNotNull('wip_id')->where('status', 'rolling')
            ->where('isReplacing', 0)
            ->with('wip.store', 'wip.ba.store.reo')
            ->whereHas('wip.store.reo', function ($query) {
                return $query->where('reos.user_id', Auth::user()->id)
                    ->where('wips.pending', 1);
            })->get();
        if (count($notificationRolling) == 0) {
            return Notification::whereNotNull('wip_id')->where('status', 'rolling')
                ->where('isReplacing', 1)
                ->with('wip.store', 'wip.ba.store.reo')
                ->whereHas('wip.ba.store.reo', function ($query) {
                    return $query->where('reos.user_id', Auth::user()->id)
                        ->where('wips.pending', 1);
                })->get()->filter(function ($item) {
                    return collect($item->ba->store)->filter(function ($store) use ($item) {
                            $explodeStart = explode('menuju', $item->wip->reason);
                            $explodeFinish = explode('dengan', $explodeStart[1]);
                            return str_contains(trim($explodeFinish[0]), $store->store_name_1);
                        })->count() == 1;
                });
        }
        return $notificationRolling;
    }

    /**
     * Datable Set for Approval Rolling
     *
     * @return mixed
     */
    public function approvalRolling()
    {
        return Datatables::of($this->findRollingApproval())
            ->editcolumn('wip.store.store_name_1', function ($item) {
                if ($item->isReplacing == 0) {
                    return $item->wip->store->store_name_1;
                }
                $explodeStart = explode('menuju', $item->wip->reason);
                $explodeFinish = explode('dengan', $explodeStart[1]);
                return $explodeFinish[0];
            })
            ->addcolumn('aksi', function ($item) {
                return '<button type="button"  class="btn blue-hoki" onclick="approveRolling(' . $item->wip->id . ')" data-dismiss="modal" >Approve </button>';
            })->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}