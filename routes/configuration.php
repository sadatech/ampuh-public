<?php

/*
|--------------------------------------------------------------------------
| Configuration Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are related to Configuraton methods
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'configuration'], function () {
        Route::get('ba', 'Master\BaController@configuration')->name('configBa');
        Route::post('ba', 'Master\BaController@create');
        Route::get('wip', 'Configuration\WipController@index');
        Route::get('storeData', 'Master\StoreController@configuration');
        Route::get('store', 'Master\StoreController@showConfig');
        Route::post('wip/datatable', 'Configuration\WipController@datatable');
        Route::get('wip/export', 'Configuration\WipController@export')->name('wipExport');
        Route::get('exitForm', 'Master\BaController@exitForm');
        Route::post('exitFormDatatable', 'Master\BaController@exitFormDatatable');
        Route::post('wip/update', 'Configuration\WipController@edit');
        Route::get('exitForm/archive', 'Master\BaController@archiveView');
        Route::get('sp', 'Master\BaController@spView');
        Route::get('turnover', 'Master\BaController@turnoverView');

        Route::group(['prefix' => 'headaccount'], function () {
            Route::get('allData', 'Configuration\HeadAccountController@index')->name('hcAll');
            Route::get('allData/datatable', 'Configuration\HeadAccountController@dtAllData')->name('dtAll');
            Route::get('allData/export', 'Configuration\HeadAccountController@allD ataExport')->name('hcAlldataExport');
            Route::get('inOutCPD', 'Configuration\HeadAccountController@cpd')->name('hcCPD');
            Route::get('inOutCPD/datatable', 'Configuration\HeadAccountController@dtCPD')->name('dtCPD');
        });
        Route::get('rolling', 'Master\BaController@rollingView');
    });
});


