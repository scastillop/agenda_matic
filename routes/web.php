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
//views
Route::get('/', function () {return view('inicio');});
//shedules
Route::resource('schedules', 'ScheduleController');
Route::post('/schedules/cancelById', 'ScheduleController@cancelById');
//rooms
Route::post('/rooms/getByRange', 'RoomController@getByRange');
Route::post('/rooms/getById', 'RoomController@getById');
//users
Route::post('/users/getByRange', 'UserController@getByRange');
Route::post('/users/getFreeTime', 'UserController@getFreeTime');
Route::post('/users/getByScheduleId', 'UserController@getByScheduleId');




