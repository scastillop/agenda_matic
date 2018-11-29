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
	Route::post('/schedules/cancelIdMail', 'ScheduleController@cancelIdMail');
	Route::post('/schedules/edit', 'ScheduleController@edit');
	Route::post('/schedules/storeOff', 'ScheduleController@storeOff');
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
	Route::post('/users/statistics', 'UserController@statistics');
	//mail
	Route::post('/mail/send', 'MailController@sendSchedule');
	//guest
	Route::post('/guests/rejectById', 'GuestController@rejectById');
	Route::post('/guests/setAssistance', 'GuestController@setAssistance');
});
//login
Auth::routes();
