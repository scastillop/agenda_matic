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

    public static function statisticsAssistance(Request $request){
        //para los alias considerar la siguiente sintaxis
        //t = total de reuniones, a = total asistencias, b = total dias bloqueados
        //de esta forma tenemos que:
        //tg = tabla guest de uso solo para obtener el total de reuniones
        //ts = tabla schedules de uso solo para obtener el total de reuniones
        // y asi sucesivamente...
        $users = \DB::table('users')
        ->leftJoin('guests as tg', function($join) use ($request){
            $join->on('users.id', '=', 'tg.user_id');
        })
        ->leftJoin('schedules as ts', function($join) use ($request){
            $join->on('ts.id', '=', 'tg.schedule_id')
            ->where('ts.status', '=', 'scheduled')
            ->where('ts.type', '=', 'meeting')
            ->whereBetween('ts.start', array($request["inicio"], $request["final"]));
            $join->orOn('ts.id', '=', 'tg.schedule_id')
            ->where('ts.status', '=', 'scheduled')
            ->where('ts.type', '=', 'meeting')
            ->WhereBetween('ts.end', array($request["inicio"], $request["final"]));
            $join->orOn('ts.id', '=', 'tg.schedule_id')
            ->where('ts.status', '=', 'scheduled')
            ->where('ts.type', '=', 'meeting')
            ->whereRaw("? between [ts].[start] and [ts].[end]", $request["inicio"]);
            $join->orOn('ts.id', '=', 'tg.schedule_id')
            ->where('ts.status', '=', 'scheduled')
            ->where('ts.type', '=', 'meeting')
            ->whereRaw("? between [ts].[start] and [ts].[end]", $request["final"]); 
        })
        ->leftJoin('guests as sg', function($join) use ($request){
            $join->on('users.id', '=', 'sg.user_id')
            ->where('sg.concurred', '=', true);
        })
        ->leftJoin('schedules as ss', function($join) use ($request){
            $join->on('ss.id', '=', 'sg.schedule_id')
            ->where('ss.status', '=', 'scheduled')
            ->where('ss.type', '=', 'meeting')
            ->whereBetween('ss.start', array($request["inicio"], $request["final"]));
            $join->orOn('ss.id', '=', 'sg.schedule_id')
            ->where('ss.status', '=', 'scheduled')
            ->where('ss.type', '=', 'meeting')
            ->WhereBetween('ss.end', array($request["inicio"], $request["final"]));
            $join->orOn('ss.id', '=', 'sg.schedule_id')
            ->where('ss.status', '=', 'scheduled')
            ->where('ss.type', '=', 'meeting')
            ->whereRaw("? between [ss].[start] and [ss].[end]", $request["inicio"]);
            $join->orOn('ss.id', '=', 'sg.schedule_id')
            ->where('ss.status', '=', 'scheduled')
            ->where('ss.type', '=', 'meeting')
            ->whereRaw("? between [ss].[start] and [ss].[end]", $request["final"]); 
        })
        ->select('users.id', 'users.name', \DB::raw('count(distinct ts.id) AS reuniones'), \DB::raw('count(distinct ss.id) AS asistencia'))
        ->groupBy('users.id','users.name')->get();
        return $users;
    }

    public static function statisticsBlocks(Request $request){
        $users = \DB::table('users')
        ->leftJoin('schedules as bs', function($join) use ($request){
            $join->on('users.id', '=', 'bs.owner_id')
            ->where('bs.status', '=', 'scheduled')
            ->where('bs.type', '=', 'off')
            ->whereBetween('bs.start', array($request["inicio"], $request["final"]));
            $join->on('users.id', '=', 'bs.owner_id')
            ->where('bs.status', '=', 'scheduled')
            ->where('bs.type', '=', 'off')
            ->WhereBetween('bs.end', array($request["inicio"], $request["final"]));
            $join->orOn('users.id', '=', 'bs.owner_id')
            ->where('bs.status', '=', 'scheduled')
            ->where('bs.type', '=', 'off')
            ->whereRaw("? between [bs].[start] and [bs].[end]", $request["inicio"]);
             $join->orOn('users.id', '=', 'bs.owner_id')
            ->where('bs.status', '=', 'scheduled')
            ->where('bs.type', '=', 'off')
            ->whereRaw("? between [bs].[start] and [bs].[end]", $request["final"]);
        })
        ->select('users.id', 'users.name', \DB::raw('count(distinct bs.id) AS bloqueos'), \DB::raw('COALESCE(SUM(DATEDIFF(day, [bs].[start], [bs].[end])),0) AS bloqueados'))
        ->groupBy('users.id','users.name')->get();
        return $users;
    }
}
