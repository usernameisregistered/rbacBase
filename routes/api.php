<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Admin')->group(function () {
    Route::post('/admin/login/login','LoginController@login');
    Route::post('/admin/login/logout','LoginController@logout');
    Route::post('/admin/manager/index','ManagerController@index');
    Route::post('/admin/manager/create','ManagerController@store');
    Route::post('/admin/manager/show','ManagerController@show');
    Route::post('/admin/manager/update','ManagerController@update');
    Route::post('/admin/manager/destroy','ManagerController@destroy');
    Route::post('/admin/managerGroup/index','ManagerGroupController@index');
    Route::post('/admin/managerGroup/create','ManagerGroupController@store');
    Route::post('/admin/managerGroup/show','ManagerGroupController@show');
    Route::post('/admin/managerGroup/update','ManagerGroupController@update');
    Route::post('/admin/managerGroup/destroy','ManagerGroupController@destroy');
});
