<?php

namespace App\Http\Controllers;

use App\Guest;
use App\Schedule;
use Illuminate\Http\Request;
use Auth;

class GuestController extends Controller
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
     * @param  \App\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function edit(Guest $guest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guest $guest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guest  $guest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guest $guest)
    {
        //
    }

    public function getById(Request $request)
    {

        $request->validate([
            'id' => 'required|integer'
        ]);

       return Guest::getById($request["id"]);
    }

    public function getAll()
    {
       return Guest::getAll();
    }

    public function rejectById(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        Guest::rejectById($request , Auth::id());

        MailController::sendRejectMsg(Auth::id() ,$request);

        return "1";
    }

    public function setAssistance(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            "asistentes"    => "array",
            "asistentes.*"  => "integer|min:1"
        ]);
        Guest::setAllNoConcurredById($request["id"]);
        if($request["asistentes"]){
            foreach ($request["asistentes"] as $idInvitado){
                Guest::setConcurredById($idInvitado, $request["id"]);
            }
        }
        
        Schedule::setRegisteredAssistanceById($request["id"]);
        return 1;
    }
}
