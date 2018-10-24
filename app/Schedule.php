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

}
