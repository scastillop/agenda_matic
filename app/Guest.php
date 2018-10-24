<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends BaseModel
{
    protected $table = 'guests';

    public static function saveGuest(Guest $guest){
        
        return $guest->save();
     }
}
