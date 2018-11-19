<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function getByRange(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'final' => 'required|date'
        ]);
        return User::getByRange($request);
    }

    public function getFreeTime(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'final' => 'required|date',
            "invitados"    => "required|array|min:1",
            "invitados.*"  => "integer|min:1"
        ]);

        $options[0] =  $this->getAfterTime($request["inicio"],$request["final"],$request["invitados"]);
        $options[1] =  $this->getBeforeTime($request["inicio"],$request["final"],$request["invitados"]);
        return $options;
    }

    public function getAfterTime($start, $end, $guests){
        $users = User::getByRangeAndId($start, $end, $guests);
        $theyAreBusy = false;
        foreach ($users as $user) {
            if($user->reuniones>0){
                $theyAreBusy=true;
            }
        }
        if($theyAreBusy){
            return UserController::getAfterTime(Carbon::parse($start)->addMinutes(30)->format('Ymd H:i'),Carbon::parse($end)->addMinutes(30)->format('Ymd H:i'),$guests);
        }else{
            $result["start"] = $start;
            $result["end"] = $end;
            return $result;
        }
    }

    public function getBeforeTime($start, $end, $guests){
        $users = User::getByRangeAndId($start, $end, $guests);
        $theyAreBusy = false;
        foreach ($users as $user) {
            if($user->reuniones>0){
                $theyAreBusy=true;
            }
        }
        if($theyAreBusy){
            return UserController::getBeforeTime(Carbon::parse($start)->subMinutes(30)->format('Ymd H:i'),Carbon::parse($end)->subMinutes(30)->format('Ymd H:i'),$guests);
        }else{
            $result["start"] = $start;
            $result["end"] = $end;
            return $result;
        }
    }

    public function getByScheduleId(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        return User::getByScheduleId($request["id"]);
    }

    public function getById(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

       return User::getById($request["id"]);
    }

    public function getAll()
    {
       return User::getAll();
    }

}
