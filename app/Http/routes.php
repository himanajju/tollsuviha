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
    Route::post('/login', 'AuthorizationController@login');
    Route::post('/registration', 'AuthorizationController@registration');


    Route::group(['prefix' => 'users'], function () {
    	Route::post('/add', 'UserManagementController@addUser');
    	Route::get('/{id}', 'UserManagementController@getUserDetails');
    	Route::post('/update','UserManagementController@userUpdate');
    });

    Route::group(['prefix'=>'toll'],function(){
    	Route::post('/getall','UserManagementController@getAllTollDetails');
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