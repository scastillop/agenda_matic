<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class User extends BaseAuthenticatable
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
            ->where('schedules.status', '=', 'scheduled')
            ->whereBetween('schedules.start', array($request["inicio"], $request["final"]));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->WhereBetween('schedules.end', array($request["inicio"], $request["final"]));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->whereRaw("? between [schedules].[start] and [schedules].[end]", $request["inicio"]);
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->whereRaw("? between [schedules].[start] and [schedules].[end]", $request["final"]); 
        })
        ->select('users.id', 'users.name', \DB::raw('count(schedules.id) AS reuniones'))
        ->groupBy('users.id','users.name')->get();
        return $users;
    }

    public static function getByRangeAvoidId(Request $request){
        $users = \DB::table('users')
        ->leftJoin('guests', function($join) use ($request){
            $join->on('users.id', '=', 'guests.user_id');
        })
        ->leftJoin('schedules', function($join) use ($request){
            $join->on('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->where('schedules.id', '!=', $request["id"])
            ->whereBetween('schedules.start', array($request["inicio"], $request["final"]));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->where('schedules.id', '!=', $request["id"])
            ->WhereBetween('schedules.end', array($request["inicio"], $request["final"]));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->where('schedules.id', '!=', $request["id"])
            ->whereRaw("? between [schedules].[start] and [schedules].[end]", $request["inicio"]);
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->where('schedules.id', '!=', $request["id"])
            ->whereRaw("? between [schedules].[start] and [schedules].[end]", $request["final"]); 
        })
        ->select('users.id', 'users.name', \DB::raw('count(schedules.id) AS reuniones'))
        ->groupBy('users.id','users.name')->get();
        return $users;
    }

    public static function getByRangeAndId($start, $end, $guests){
        $users = \DB::table('users')
        ->leftJoin('guests', function($join) use ($start, $end, $guests){
            $join->on('users.id', '=', 'guests.user_id');
        })
        ->leftJoin('schedules', function($join) use ($start, $end, $guests){
            $join->on('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->whereBetween('schedules.start', array($start, $end));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->WhereBetween('schedules.end', array($start, $end));
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->whereRaw("? between [schedules].[start] and [schedules].[end]", $start);
            $join->orOn('schedules.id', '=', 'guests.schedule_id')
            ->where('schedules.status', '=', 'scheduled')
            ->whereRaw("? between [schedules].[start] and [schedules].[end]", $end); 
        })
        ->whereIn('users.id', $guests)
        ->select('users.id', 'users.name', \DB::raw('count(schedules.id) AS reuniones'))
        ->groupBy('users.id','users.name')->get();
        return $users;
    }

    public static function getByScheduleId($id){
        $users = \DB::table('users')
        ->join('guests', 'users.id', '=', 'guests.user_id')
        ->join('schedules', 'schedules.id', '=', 'guests.schedule_id')
        ->where('schedules.id', "=", $id)
        ->select('users.id', 'users.name', 'users.email', 'guests.concurred')
        ->get();
        return $users;
    }
    
    public static function getById($id){

         $users = \DB::table('users')
         ->where('id', '=' ,$id)->get();
        
       return $users; 
   }

   public static function getAll(){

         $users = \DB::table('users')->get();
        
       return $users; 
   }
}
