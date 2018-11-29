<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Guest extends BaseModel
{
  protected $table = 'guests';

  public static function saveGuest(Guest $guest){
        
        return $guest->save();
  }

  public static function getById($id){

         $guest = \DB::table('guests')
         ->where('id', '=' ,$id)->get();
        
       return $guest; 
  }

  public static function getAll(){

         $guest = \DB::table('guests')->get();
        
       return $guest; 
  }

   public static function getBySchedule($scheduleId){

         $guest = \DB::table('guests')
         ->where('schedule_id', '=' ,$scheduleId)->get();
        
       return $guest; 
   }

   public static function rejectById(Request $request,$userId){

        $guest = \DB::table('guests')
        ->where('guests.schedule_id', $request['id'])
        ->where ('guests.user_id' , $userId)
        ->update(['guests.rejected' => 1]);
        
        return $guest;

  }

  public static function setAllNoConcurredById($id){
        $guest = \DB::table('guests')
        ->where('guests.schedule_id',$id)
        ->update(['guests.concurred' => false]);
        return $guest;
  }
  
  public static function setConcurredById($user_id, $schedule_id){
        $guest = \DB::table('guests')
        ->where('guests.schedule_id',$schedule_id)
        ->where ('guests.user_id' , $user_id)
        ->update(['guests.concurred' => true]);
        return $guest;
  }
}
