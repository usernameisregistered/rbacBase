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
    Route::any('/admin/login/login','LoginController@login');
    Route::any('/admin/login/logout','LoginController@logout');
    Route::apiResource('/admin/manager', 'ManagerController');
    Route::apiResource('/admin/managerGroup', 'ManagerGroupController');
});
