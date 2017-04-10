<?php

/*
|--------------------------------------------------------------------------
| Master Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are related to master methods
|
*/

Route::group(['middleware' => ['auth']], function () {
    /**
     * Prefix Master
     */
    Route::group(['prefix' => 'master'], function () {
        Route::get('ba', 'Master\BaController@master')->name('masterBa');
        Route::get('/ba/add/{param}', 'Master\BaController@AddBa');
        Route::post('/ba/add/', 'Master\BaController@create');
        Route::get('/ba/approval/', 'Master\BaController@approval');
        Route::get('/ba/approval/{baid}', 'Master\BaController@approval');
        Route::post('/ba/approval/datatable/{baid}', 'Master\BaController@datatableApproval');
        Route::get('/ba/approval/{baid}/{isResign}', 'Master\BaController@approval');
        Route::post('/ba/approved/{baid}', 'Master\BaController@approved', ['middleware' => 'role:reo']);
        Route::post('/ba/approved/{baid}', 'Master\BaController@approved', ['middleware' => 'role:arina']);
        Route::post('/ba/reject/{baid}', 'Master\BaController@reject');
        Route::get('/ba/edit/{ba}', 'Master\BaController@editBa');
        Route::post('/ba/{ba}/sp', 'Master\BaController@punishSP');
        Route::post('/ba/{ba}/maternity', 'Master\BaController@maternity');
        Route::put('/ba/editData/{ba}', 'Master\BaController@edited');
        Route::post('/spBa', 'Master\BaController@giveSp');
        Route::get('/ba/rotasiWip', 'Master\BaController@rotasiWip');
        Route::get('/ba/availableBrand/{ba}', 'Master\BaController@availableBrand');
        Route::get('/ba/export/', 'Master\BaController@BAexport')->name('baExport');

        Route::get('area', 'Master\AreaController@index')->name('area');
        Route::post('addarea', 'Master\AreaController@create');
        Route::get('/getAreaById/{id?}', 'Master\AreaController@getById');
        Route::put('/editArea/{id?}', 'Master\AreaController@editArea');
        Route::delete('/deleteArea/{id?}', 'Master\AreaController@deleteArea');

        Route::get('position', 'Master\PositionController@index')->name('position');
        Route::post('/addposition', 'Master\PositionController@create');
        Route::get('/position/{id?}', 'Master\PositionController@getById');
        Route::put('/position/{id?}', 'Master\PositionController@edit');
        Route::delete('/position/{id?}', 'Master\PositionController@delete');

        Route::get('agency', 'Master\AgencyController@index')->name('agency');

        Route::get('account', 'Master\AcountController@index')->name('masterAccount');
        Route::post('addacount', 'Master\AcountController@create');
        Route::put('acount/{id?}', 'Master\AcountController@saveEditAcount');
        Route::get('acount/{id?}', 'Master\AcountController@editById');
        Route::delete('acount/{id?}', 'Master\AcountController@delete');

        Route::get('store', 'Master\StoreController@index')->name('masterStore');
        Route::get('store/{store}', 'Master\StoreController@find');
        Route::get('store/available', 'Master\StoreController@available');
        Route::post('store/brand/sdf', 'Master\StoreController@availBrand');
        Route::post('store', 'Master\StoreController@store');
        Route::put('store', 'Master\StoreController@update');
        Route::post('store/datatable', 'Master\StoreController@datatable');

        Route::get('store/create/store', 'Master\StoreController@create')->name('createStore');

        Route::get('store/{id}/edit', 'Master\StoreController@edit');
        Route::get('store/{id}/hold', 'Master\StoreController@formHold');
        Route::post('store/{id}/hold', 'Master\StoreController@hold');
        Route::get('store/sdf/reo', 'Master\StoreController@storeReo');
        Route::get('store/account/{account}/{region}', 'Master\StoreController@findStoreAccount');

        Route::delete('store/{id}/delete', 'Master\StoreController@destroy');
        Route::post('store/{id}/restore', 'Master\StoreController@restore');

    });
});

Route::get('store/account/{account}/{region}', 'Master\StoreController@findStoreAccount');


