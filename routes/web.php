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
    return view('inicio');
});

Route::resource('schedules', 'ScheduleController');

Route::post('/rooms/getByRange', 'RoomController@getByRange');

Route::post('/users/getByRange', 'UserController@getByRange');

Route::post('/schedules/store', 'ScheduleController@store');
