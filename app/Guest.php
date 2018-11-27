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

   public static function rejectById(Request $request){

        $guest = \DB::table('guests')
        ->where('guests.schedule_id', $request["id"])
        ->where ('guests.user_id' , $request["user_id"])
        ->update(['guests.rejected' => 1]);
        
        return $guest;

     }
}
