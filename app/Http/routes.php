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
    	Route::post('/add', 'UserManagementController@addBoothUser');
    });
});