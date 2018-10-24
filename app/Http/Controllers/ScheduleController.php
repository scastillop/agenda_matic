<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Guest;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shedules = Schedule::get();
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
            'ubicacion' => 'integer|min:1',
            "invitados"    => "required|array|min:1",
            "invitados.*"  => "integer|min:1"
        ]);

        $schedule = new Schedule();
        $schedule ->owner_id = '1';
        $schedule ->type = 'meeting';
        $schedule ->status = 'scheduled';
        $schedule ->rejectable = $request['rechazable'];
        $schedule ->start = $request['inicio'];
        $schedule ->end = $request['final'];
        $schedule ->details = isset($request['detalles'])? "" : $request['detalles'];
        $schedule ->title = $request['titulo'];
        $schedule ->all_day = $request['todo_el_dia'];
        $schedule ->room_id = $request['ubicacion'];

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
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
}
