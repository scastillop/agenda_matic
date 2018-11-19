<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends BaseModel
{
    protected $table = 'guests';

    public static function saveGuest(Guest $guest){
        
        return $guest->save();
     }

    public static function getById($id){

         $guest = \DB::table('guest')
         ->where('id', '=' ,$id)->get();
        
       return $guest; 
   }

   public static function getAll(){

         $guest = \DB::table('guest')->get();
        
       return $guest; 
   }
}
