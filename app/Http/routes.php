<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    // Authorization Starts
    Route::post('/web-login', 'AuthorizationController@webLogin');
    Route::post('/app-login', 'AuthorizationController@androidLogin');
    Route::post('/registration', 'AuthorizationController@registration');


    Route::group(['prefix' => 'users'], function () {
    	Route::post('/add', 'UserManagementController@addUser');
    	Route::get('/{id}', 'UserManagementController@getUserDetails');
        Route::post('/update','UserManagementController@userUpdate');
    	Route::get('/pay-history/{userId}','TxnManagementController@payHistory');
        Route::post('/change-password','UserManagementController@chnagePassword');
        Route::post('/toll-list','UserManagementController@tollList');

    });
    Route::group(['prefix'=>'admin'],function(){
        Route::get('/block/{userEmail}/{contactNo}/{adminId}', 'UserManagementController@blockUser');
        Route::get('/unblock/{userEmail}/{contactNo}/{adminId}', 'UserManagementController@unblockUser');
        Route::get('/get-all-users/{adminId}','UserManagementController@getAllUsers');
    });

    Route::group(['prefix'=>'toll'],function(){
    	Route::post('/getall','UserManagementController@getAllTollDetails');
    	Route::post('/pay','TxnManagementController@tollPayment');
    });

	Route::group(['prefix'=>'wallet'],function(){
	    Route::post('/add','TxnManagementController@addTxnDetails');
	});


    Route::group(['prefix'=>'vipuser'],function(){
    	Route::post('/add','UserManagementController@addVIPusers');
    });

    Route::group(['prefix'=>'vehicle'],function(){
    	Route::get('/get-details/{vechile_no}/{userId}/{tollId}','UserManagementController@getVechileDetails');

    });
});