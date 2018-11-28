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

Route::group(['middleware' => ['auth']], function() {
	//views
    Route::get('/', function () {return view('inicio');});
    Route::get('/home', function () {return view('inicio');});
    //shedules
	Route::resource('schedules', 'ScheduleController');
	Route::post('/schedules/cancelById', 'ScheduleController@cancelById');
	Route::post('/schedules/edit', 'ScheduleController@edit');
	//rooms
	Route::post('/rooms/getByRange', 'RoomController@getByRange');
	Route::post('/rooms/getByRangeAvoidId', 'RoomController@getByRangeAvoidId');
	Route::post('/rooms/getById', 'RoomController@getById');
	Route::post('/rooms/getAll', 'RoomController@getAll');
	//users
	Route::post('/users/getByRange', 'UserController@getByRange');
	Route::post('/users/getByRangeAvoidId', 'UserController@getByRangeAvoidId');
	Route::post('/users/getFreeTime', 'UserController@getFreeTime');
	Route::post('/users/getByScheduleId', 'UserController@getByScheduleId');
	//mail
	Route::post('/mail/send', 'MailController@send');
});
//login
Auth::routes();

