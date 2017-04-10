<?php

/*
|--------------------------------------------------------------------------
| Utilities Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are related to Utilities methods
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'utilities'], function () {
        Route::get('notification', 'Utilities\NotificationController@index')->name('notif');
        Route::post('notification/datatable', 'Utilities\NotificationController@datatable');
        Route::put('notification/{id?}', 'Utilities\NotificationController@openNotif');
        Route::get('datanotif', 'Utilities\NotificationController@datanotif');
        Route::get('support', 'Utilities\SupportController@index')->name('support');
        Route::get('support_staff', 'Utilities\SupportController@staff')->name('staff');
        Route::post('openticket', 'Utilities\SupportController@store')->name('store');
        Route::post('openticket/datatable', 'Utilities\SupportController@datatable');
        Route::get('detail/{id?}', 'Utilities\SupportController@detail')->name('detail');
        Route::post('replyTicket/{id?}', 'Utilities\SupportController@replyMessage')->name('reply');
        Route::put('editReplyTicket/{id?}/{idticket?}', 'Utilities\SupportController@editReplyMessage')->name('editreply');
        Route::get('dataReplyTicket/{id?}', 'Utilities\SupportController@dataReplyMessage')->name('datareply');
        Route::delete('deleteReplyTicket/{id?}', 'Utilities\SupportController@deleteReplyMessage')->name('editreply');
        Route::post('openticket/datatablestaff', 'Utilities\SupportController@datatablestaff');
        Route::put('reopen/{id?}', 'Utilities\SupportController@reOPenTicket');
        Route::get('emailSend', 'Utilities\SendMailController@sendEmailReminder');
        Route::delete('ticket/{id}/delete', 'Utilities\SupportController@destroy');
        Route::post('ticket/{id}/restore', 'Utilities\SupportController@restore');
        Route::get('notification', 'Utilities\NotificationController@index')->name('notif');
        Route::get('users', 'Utilities\UsersController@index')->name('users');
        Route::post('notification/datatable', 'Utilities\NotificationController@datatable');
        Route::post('users/datatable', 'Utilities\UsersController@datatable');
        Route::get('users/create', 'Utilities\UsersController@add');
        Route::post('users/addusers', 'Utilities\UsersController@addUsers');
        Route::post('users/editusers', 'Utilities\UsersController@editUsers');
        Route::get('user/{id}/edit', 'Utilities\UsersController@edit');
        Route::post('user/{id}/edit', 'Utilities\UsersController@update');
        Route::post('users/showtoko', 'Utilities\UsersController@showtoko');
        Route::post('users/addstore', 'Utilities\UsersController@addstore');
        Route::post('users/deletestore', 'Utilities\UsersController@deletestore');
        Route::get('user/{id}/delete', 'Utilities\UsersController@deleteusers');
        Route::get('users/email_invitation', 'Utilities\UsersController@emailInvitation');
    });

});