<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::guest()) {
        return redirect('login');
    } else {
        return view('welcome');
    }
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('cmd', 'CmdController@run');
});

Auth::routes();
