<?php

namespace App\Http\Controllers;

use App\Schedule;
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
        $schedule = new Schedule();
        $schedule ->created_at = $request['created_at'];
        $schedule ->updated_at = $request['updated_at'];
        $schedule ->owner_id = $request['owner_id'];
        $schedule ->type = $request['type'];
        $schedule ->status = $request['status'];
        $schedule ->rejectable = $request['rejectable'];
        $schedule ->start = $request['start'];
        $schedule ->end = $request['end'];
        $schedule ->details = $request['details'];
        $schedule ->title = $request['title'];
        $schedule ->all_day = $request['all_day'];
        $schedule ->room_id = $request['room_id'];

        //$schedule = Schedule::create($request->all());

        return Schedule::saveSchedule($schedule);
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
