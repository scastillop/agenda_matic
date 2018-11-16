<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Room extends Model
{
    protected $table = 'rooms';


    public static function getByRange(Request $request){

    	 $rooms = \DB::table('rooms')
            ->leftJoin('schedules', function($join) use ($request){
                $join->on('rooms.id', '=', 'schedules.room_id')
                ->where('schedules.status', '=', 'scheduled')
                ->whereBetween('schedules.start', array($request["inicio"], $request["final"]));
                $join->orOn('rooms.id', '=', 'schedules.room_id')
                ->where('schedules.status', '=', 'scheduled')
                ->WhereBetween('schedules.end', array($request["inicio"], $request["final"]));
                $join->orOn('rooms.id', '=', 'schedules.room_id')
                ->where('schedules.status', '=', 'scheduled')
                ->whereRaw("? between [schedules].[start] and [schedules].[end]", $request["inicio"]);
                $join->orOn('rooms.id', '=', 'schedules.room_id')
                ->where('schedules.status', '=', 'scheduled')
                ->whereRaw("? between [schedules].[start] and [schedules].[end]", $request["final"]); 
            })
            ->select('rooms.id', 'rooms.name', \DB::raw('count(schedules.id)  AS reuniones'))
            ->groupBy('rooms.name','rooms.id')->get();
        return $rooms;

   }

   public static function getById($id){

         $rooms = \DB::table('rooms')
         ->where('id', '=' ,$id)->get();
        
       return $rooms; 
   }

   public static function getAll(){

         $rooms = \DB::table('rooms')->get();
        
       return $rooms; 
   }

}
