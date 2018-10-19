<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Room extends Model
{
    protected $table = 'rooms';


    public static function getRoomsByRange(Request $request){

    	 $rooms = \DB::table('rooms')
            ->leftJoin('schedules', function($join) use ($request){
                $join->on('rooms.id', '=', 'schedules.room_id')
                ->whereBetween('schedules.start', array($request["inicio"], $request["final"]));
                $join->orOn('rooms.id', '=', 'schedules.room_id')
                ->WhereBetween('schedules.end', array($request["inicio"], $request["final"]));
            })
            ->select('rooms.id', 'rooms.name', \DB::raw('count(schedules.id)  AS reuniones'))
            ->groupBy('rooms.name','rooms.id')->get();
        return $rooms;

   }

}
