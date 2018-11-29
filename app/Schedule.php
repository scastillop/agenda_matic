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
        ->select(\DB::raw('schedules.title, schedules.id, schedules.created_at, schedules.updated_at, schedules.owner_id, schedules.type, schedules.status, schedules.rejectable, schedules.start, schedules."end", schedules.registered_assistance, CONVERT( VARCHAR(MAX), schedules.details) as details, schedules.all_day, schedules.room_id'))
        ->leftJoin('guests', 'schedules.id', '=', 'guests.schedule_id')
        ->where('schedules.status', "=", "scheduled")
        ->where('schedules.owner_id', "=", $user_id)
        ->orWhere('schedules.status', "=", "scheduled")
        ->where('guests.user_id', "=", $user_id)
        ->where('guests.rejected', "=", false)
        ->groupBy(\DB::raw('schedules.id, schedules.title, schedules.created_at, schedules.updated_at, schedules.owner_id, schedules.type, schedules.status, schedules.rejectable, schedules.start, schedules."end", schedules.registered_assistance, CONVERT( VARCHAR(MAX), schedules.details), schedules.all_day, schedules.room_id'))
        ->get();
        return $schedules;
     }
  
    public static function getById($id){

         $schedules = \DB::table('schedules')
         ->where('id', '=' ,$id)->get();

         return $schedules; 
   }


   public static function getAll(){

         $schedule = \DB::table('schedule')->get();
        
       return $schedule; 
   }

    public static function setRegisteredAssistanceById($id){
        $schedules = \DB::table('schedules')
        ->where('id', "=", $id)
        ->update(['registered_assistance' => true]);
    }

    public static function cancelById($id){
        $schedules = \DB::table('schedules')
        ->where('id', "=", $id)
        ->update(['status' => "canceled"]);
    }
}
