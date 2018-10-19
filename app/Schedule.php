<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Schedule extends Model
{
    protected $table = 'schedules';


     public static function saveSchedule(Schedule $schedule){

         $v = Validator::make($schedule->all(), [
            'created_at' => 'required',
            'updated_at' => 'required',
            'owner_id' => 'required',
            'type' => 'required',
            'status' => 'required',
            'rejectable' => 'required',
            'start' => 'required',
            'end' => 'required',
            'details' => 'required',
            'title' => 'required',
            'all_day' => 'required',
            'room_id' => 'required'
        ]);

         if ($v->fails())
        {
            return false;
        }
        
        $schedule->save();

        return true;
     }

}
