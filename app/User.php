<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	protected $table = 'users ';

    public static function getByRange(Request $request){
        $users = \DB::table('users')
        ->leftJoin('guests', function($join) use ($request){
            $join->on('users.id', '=', 'guests.user_id');
           
        })
        ->leftJoin('schedules', function($join) use ($request){
            $join->on('schedules.id', '=', 'guests.schedule_id')
            ->whereBetween('schedules.start', array($request["inicio"], $request["final"]));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->WhereBetween('schedules.end', array($request["inicio"], $request["final"]));  
        })
        ->select('users.id', 'users.name', \DB::raw('count(guests.schedule_id)  AS reuniones'))
        ->groupBy('users.id','users.name')->get();
        return $users;
    }
}
