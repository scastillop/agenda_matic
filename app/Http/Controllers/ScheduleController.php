<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Guest;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shedules = Schedule::getValidById(Auth::id());
        return $shedules;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'rechazable' => 'boolean',
            'inicio' => 'required|date',
            'final' => 'required|date',
            'titulo' => 'required|string',
            'todo_el_dia' => 'boolean',
            'ubicacion' => 'integer|min:0',
            "invitados"    => "required|array|min:1",
            "invitados.*"  => "integer|min:1"
        ]);

        $schedule = new Schedule();

        if($request["todo_el_dia"]){
            $start = Carbon::parse($request['inicio']);
            $start->hour = 0;
            $start->minute = 0;
            $schedule ->start = $start->format('Ymd H:i');
            $end = Carbon::parse($request['final']);
            $end->addDay();
            $end->hour = 0;
            $end->minute = 0;
            $schedule ->end = $end->format('Ymd H:i');
        }else{
            $schedule ->start = $request['inicio'];
            $schedule ->end = $request['final'];
        }
        
        $schedule ->owner_id = Auth::id();
        $schedule ->type = 'meeting';
        $schedule ->status = 'scheduled';
        $schedule ->rejectable = $request['rechazable'];        
        $schedule ->details = isset($request['detalles'])? $request['detalles'] : "";
        $schedule ->title = $request['titulo'];
        $schedule ->all_day = $request['todo_el_dia'];
        if($request['ubicacion']){
            $schedule ->room_id = $request['ubicacion'];    
        }
        $schedule_id = Schedule::saveSchedule($schedule);

        foreach ($request['invitados'] as $user_id) {
            $guest = new Guest();
            $guest->user_id = $user_id;
            $guest->schedule_id = $schedule_id;
            $guest->concurred = 0;
            $guest->rejected = 0;
            Guest::saveGuest($guest);
        }
        return 1;
    }

    public function edit(Request $request)
    {   
        $this->cancelById($request);
        $this->store($request);
        return 1;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    public function cancelById(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);
        Schedule::cancelById($request["id"]);

        return "1";
    }
    
    public function getById(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

       return Schedule::getById($request["id"]);
    }

    public function getAll()
    {
       return Schedule::getAll();
    }


    public function storeOff(Request $request)
    {
        $request->validate([
            'id_guest' => 'required', 
            'inicio' => 'required|date',
            'final' => 'required|date',
            'titulo' => 'required|string',
            'todo_el_dia' => 'boolean'
        ]);

        $schedule = new Schedule();

        if($request["todo_el_dia"]){
            $start = Carbon::parse($request['inicio']);
            $start->hour = 0;
            $start->minute = 0;
            $schedule ->start = $start->format('Ymd H:i');
            $end = Carbon::parse($request['final']);
            $end->addDay();
            $end->hour = 0;
            $end->minute = 0;
            $schedule ->end = $end->format('Ymd H:i');
        }else{
            $schedule ->start = $request['inicio'];
            $schedule ->end = $request['final'];
        }
        
        $schedule ->owner_id = 10;
        $schedule ->rejectable = 1;
        $schedule ->type = 'off';
        $schedule ->status = 'scheduled';       
        $schedule ->details = isset($request['detalles'])? $request['detalles'] : "";
        $schedule ->title = $request['titulo'];
        $schedule ->all_day = $request['todo_el_dia'];
        $schedule ->room_id = null;

        $quantity = User::getByRangeAndID($request["inicio"],$request["final"],$request["id_guest"]);

        if($quantity[0]->reuniones == 0){
            Schedule::saveSchedule($schedule);
            return 1;
        }else{
            return 0;
        }
        return $quantity;
    }

}
