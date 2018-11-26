<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Schedule extends BaseModel
{
    protected $table = 'schedules';


     public static function saveSchedule(Schedule $schedule){
        
        $schedule->save();

        return $schedule->id;
     }

     public static function getValid()
     {
        $schedules = \DB::table('schedules')
        ->where('status', "=", "scheduled")
        ->get();

        return $schedules;
     }

     public static function getValidById($user_id)
     {
        $schedules = \DB::table('schedules')
        ->leftJoin('guests', 'schedules.id', '=', 'guests.schedule_id')
        ->where('schedules.status', "=", "scheduled")
        ->where('schedules.owner_id', "=", $user_id)
        ->orWhere('schedules.status', "=", "scheduled")
        ->where('guests.user_id', "=", $user_id)
        ->groupBy('schedules.id')
        ->get();
        return $schedules;
     }

     public static function cancelById($id){
        $schedules = \DB::table('schedules')
        ->where('id', "=", $id)
        ->update(['status' => "canceled"]);
     }
     
     public static function getById($id){

         $schedule = \DB::table('schedule')
         ->where('id', '=' ,$id)->get();
        
       return $schedule; 
   }

   public static function getAll(){

         $schedule = \DB::table('schedule')->get();
        
       return $schedule; 
   }

}
