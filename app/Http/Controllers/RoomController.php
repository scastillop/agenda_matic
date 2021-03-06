<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }

    public function getByRange(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'final' => 'required|date'
        ]);
       return Room::getByRange($request);
    }

    public function getByRangeAvoidId(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'final' => 'required|date',
            'id' => 'required|integer'
        ]);
       return Room::getByRangeAvoidId($request);
    }

    public function getById(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);
       return Room::getById($request["id"]);
    }

    public function getAll()
    {
       return Room::getAll();
    }
}
