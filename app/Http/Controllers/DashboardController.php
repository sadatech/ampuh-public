<?php

namespace App\Http\Controllers;

use App\Activities;
use App\Ba;
use App\Notification;
use App\Store;
use App\WIP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalba = Ba::count();
        $totalstore = Store::count();
        $baIn = Ba::where('approval_id', 2)->whereYear('updated_at', date('Y'))->whereMonth('updated_at', date('m'))->count();
        $baOut = Ba::where('approval_id', 5)->whereYear('updated_at', date('Y'))->whereMonth('updated_at', date('m'))->count();
        $activities = Notification::whereYear('updated_at', date('Y'))->whereMonth('updated_at', date('m'))->latest()->get();

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
                })
                ->leftjoin('branch_aros', 'cities.branch_id', 'cities.branch_id')
                ->where([
                    ['user_id', Auth::id()],
                    ['read', '=', 0],
                    ['role', '=', 'aro']
                ])->select('notifications.*')->groupBy('id');

        }
        $activities = Activities::take(15)->latest()->where('user_id', Auth::id())->get();

        $todos = [];
        // 3/4
        $approveid = (Auth::user()['role'] == 'arina') ? [0] : [1];
        $approveid2 = (Auth::user()['role'] == 'arina') ? [3] : [4];
        $approveid3 = (Auth::user()['role'] == 'arina') ? [9] : [10];
        $pending = Ba::whereIn('approval_id' , $approveid)->count();
        $pending2 = Ba::whereIn('approval_id' , $approveid2)->count();
        $pending3 = Ba::whereIn('approval_id' , $approveid3)->count();
        if ($pending > 0) {
            $todo['title'] = "You have {$pending} pending BA(s) approval,
                            <a href='/master/ba/approval/' class=\"label label-sm label-info\"> Take action<i class=\"fa fa-share\"></i></a>";
            if(Auth::user()->role == 'aro') {
                $todo['title'] = "You have {$pending} pending BA(s) approval,
                            <a href='javascript:void(0);' class=\"label label-sm label-info\"> Waiting approval<i class=\"fa fa-share\"></i></a>";
            }
            $todo['icon'] = 'fa fa-bell-o';
            array_push($todos, $todo);
        }
        if($pending2 > 0) {
            $todo['title'] = "You have {$pending2} pending resign approvals, 
                                <a href='/master/ba/approval/' class=\"label label-sm label-info\"> Take action<i class=\"fa fa-share\"></i></a>";
            if(Auth::user()->role == 'aro') {
                $todo['title'] = "You have {$pending2} pending resign approvals, 
                                <a href='javascript:void(0);' class=\"label label-sm label-info\"> Waiting approval<i class=\"fa fa-share\"></i></a>";
            }
            $todo['icon'] = 'fa fa-exclamation';
            array_push($todos, $todo);
        }
        if ($pending3 > 0) {
            $todo['title'] = "You have {$pending3} pending ba rejoin approvals, 
                                <a href='/master/ba/approval/' class=\"label label-sm label-info\"> Take action<i class=\"fa fa-share\"></i></a>";
            $todo['icon'] = 'fa fa-exclamation';
            array_push($todos, $todo);
        }
        $wips = WIP::where('fullfield', 'hold')->count();
        if($wips > 0) {
            $todo['title'] = "You have {$wips} unfullfield WIP(s)";
            $todo['icon'] = 'fa fa-bolt';
            array_push($todos, $todo);
        }
        return view('dashboard', compact('totalba', 'totalstore', 'activities', 'baIn', 'baOut', 'todos'));
    }
}

    