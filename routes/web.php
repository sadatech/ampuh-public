<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/aaa', function () {

    $allAro = \App\Branch_aro::with('user')->groupBy('user_id')->get();

    $map = $allAro->map(function ($item) {
        return
            ['wip' => \App\WIP::NotifyAro($item['user_id'])->get(), 'aro' => $item];
    })->map(function ($data) {
        return [
            'data' => collect($data['wip'])->map(function ($item) {
                return [
                    'reason' => $item['reason'],
                    'effective_date' => $item->effective_date,
                    'status' => $item->status,
                    'head_count' => $item->head_count,
                    'store_name' => $item->store->store_name_1,
                ];
            }),
            'aro' => $data['aro']
        ];
    });

    return $map;

//    $allAro = \App\Branch_aro::groupBy('user_id')->get();
//
//    return $allAro->map(function ($item) {
//        return App\Store::with('inWip2', 'city.aro')->whereHas('inWip2', function ($query) use ($item) {
//            return $query->whereNull('replace_id')
//                        ->orWhereHas('replacement', function ($query) {
//                            return $query->where('status', 'Tidak Lulus');
//                        });
//        })->whereHas('city.aro', function ($query) use ($item) {
//            return $query->where('branch_aros.user_id', $item['user_id']);
//        })->get();

//    });


//    $aroGroup = $groupByBranch->map(function ($item, $key) {
//        dd($item['store']);
//        $a =  collect($item['store']['city']['aro'])->groupBy(function ($item) {
//           dd($item);
//        });
//    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index');

    Route::post('/sdf/exists', 'SdfController@exists');
    Route::get('/sdf/exists', 'SdfController@exists');
    Route::get('/sdf/attachment/{sdf}', 'SdfController@downloadAttachment');
    Route::get('/sdf/detail/{sdf}', 'SdfController@show');

    Route::group(['middleware' => 'role:reo'], function () {
        //Jika role reos
    });

    Route::get('wip', 'Configuration\WipController@index');

    Route::get('sdf', 'SdfController@index');
    Route::delete('sdf/{id}/delete', 'SdfController@destroy');
    Route::post('sdf/{id}/restore', 'SdfController@restore');
    Route::post('sdf/{id}/hold', 'SdfController@hold');
    Route::post('sdf/{id}/unhold', 'SdfController@unhold');
    Route::post('sdf/datatable', 'SdfController@datatable');
    Route::post('sdf/new/ba', 'SdfController@create');
    Route::get('sdf/export', 'SdfController@export')->name('sdfExport');
    Route::put('sdf/{id}/{brandId}/stay', 'SdfController@stay');
    Route::get('store/data', function (Request $request) {
        return App\Store::where('store_name_1', 'like', '%' . $request->q . '%')->get();
    });
    Route::get('aroperformance', 'AroController@index');
});


Route::post('storeReoFilter', 'Utilities\UsersController@storeReoFilter');


Route::get('testAja', 'Master\BaController@testAja');

// Punya Config BA
Route::post('baDataTable', 'Master\BaController@index');
Route::get('ba/{ba}', 'Master\BaController@find');
Route::get('findBaInWip/{store}/{brand}', 'Master\BaController@findBaInWip');
Route::get('findBaInWip/{store}', 'Master\BaController@findBaInWip');
Route::get('findSpFromBa/{ba}', 'Master\BaController@findSpFromBa');
Route::get('baStore/{ba}', 'Master\BaController@baStore');
// TODO nanti dipindahin ke prefix ba aja jangan lp di js nya juga tambah prefixnya
Route::post('rollingBa', 'Master\BaController@rollingBa');
Route::post('resignBa', 'Master\BaController@resignBa');
Route::post('joinBackBa', 'Master\BaController@joinBackBa');
Route::post('masterBa', 'Master\BaController@masterData');
Route::get('maternityLeave/{ba}', 'Master\BaController@maternityLeave');
Route::get('newBaSdf', 'Master\BaController@newBaSdf')->name('newBaSdf');
Route::get('newSdf', 'SdfController@newSdf')->name('newSdf');
Route::post('baSdf', 'SdfController@newBaSdf');
Route::get('exportBa', 'Master\BaController@export');
Route::get('exportTurnOver', 'Master\BaController@exportTurnOver');
Route::get('exportSpData', 'Master\BaController@exportSpData');
Route::get('exportStore', 'Master\StoreController@export');
Route::post('baFilter', 'Master\BaController@filterBa')->name('baFilter');
Route::post('storeFilter', 'Master\StoreController@filter');
Route::post('reoFilter', 'UserController@reoFilter');

Route::post('provinceFilter', 'Master\CityController@filter');
Route::post('provinceFilter/allStore', 'Master\CityController@allStore');
Route::post('provinceFilter/getStore', 'Master\CityController@getStore');

Route::post('branchFilter', 'Master\BranchController@filter');
Route::post('cityFilter', 'Master\CityController@filterCity');
Route::post('configFilter', 'Master\BaController@filterBa');
Route::post('accountFilter', 'Master\AcountController@filterAccount');
Route::get('account/{account}', 'Master\AcountController@find');
Route::post('reoFilter', 'Master\StoreController@reoFilter');
Route::post('areaFilter', 'Master\AreaController@filter');
Route::get('checkStoreBaAllocation/{store}', 'Master\StoreController@baAllocation');
Route::get('allocationInStore/{id}', 'Master\StoreController@allocationInStore');
Route::get('historyBa/{ba}', 'Master\baController@historyBa');
Route::post('hasMobileBaCheck', 'SdfController@hasMobileBaCheck');
Route::get('cityRegion/{city}', 'Master\CityController@cityRegion');
Route::get('cityName/{city}', 'Master\CityController@cityName');
Route::get('findReo/{store}', 'Master\StoreController@findReo');
Route::get('findRollingApproval', 'Utilities\NotificationController@findRollingApproval');
Route::get('rolling/approval', 'Master\BaController@rollingApprovalPage');
Route::post('approvalRolling', 'Utilities\NotificationController@approvalRolling');
Route::get('approvalRolling/{wip}', 'Configuration\WipController@approveRolling');
Route::post('archiveResign', 'Master\BaController@archiveData');
Route::get('downloadArchive/{exitForm}', 'Master\BaController@downloadArchive');
Route::post('documentSpData', 'Master\BaController@documentSpData');
Route::get('documentSp', 'Master\BaController@documentSp');
Route::post('uploadSuratSP/{ba}', 'Master\BaController@uploadSuratSP');
Route::get('downloadSuratSp/{ba}', 'Master\BaController@downloadSuratSp');
Auth::routes();


Route::get('exportStoreExcel', 'ExportController@exportToko');
Route::get('exportBaExcel', 'ExportController@exportBa');
Route::get('datanotif', 'Utilities\NotificationController@datanotif');
Route::get('store/available', 'Master\StoreController@available');


Route::get('/sdf/available', 'SdfController@available')->name('availSDF');
Route::get('sdf/{sdf}', 'SdfController@find');

Route::get('/sdf/update/{sdf}', 'SdfController@update');
Route::post('/sdf/tambah/', 'SdfController@tambahSDF')->name('tambahTokoDiSDF');
Route::post('/sdf/kurang/', 'SdfController@removeStore')->name('kurangTokoDiSDF');

Route::get('wip/{storeId}', 'Configuration\WipController@find');




