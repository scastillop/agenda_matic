<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use App\User;
use App\Schedule;
use App\Guest;
use Carbon\Carbon;
use Auth;

class MailController extends Controller
{


    public static function sendScheduleMsg($ownerId, $guests, $schedule){

        $start = Carbon::parse($schedule->start);
        $start = $start->format('d-m-Y');

        $guests = User::getByScheduleId($schedule->id);
        $to_email= array();
        foreach ($guests as $user){
            array_push($to_email, $user->email);         
        }
        $messageScheduleBody = "El usuario ownerUser, le ha agendado una reunion para el dia dateSchedule.";
        $messagehead = "Estimado(a): ";
        $messageScheduleBody = str_replace('ownerUser',  Auth::user()->name, $messageScheduleBody);
        $messageScheduleBody = str_replace('dateSchedule', $start , $messageScheduleBody);
        $to_subject = "Agendamiento reunion";
        $to_msg_header = $messagehead;
        $to_msg_body = $messageScheduleBody;
        
        Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));
    }


    public static function sendCancelMsg($ownerId, $scheduleId){


        $schedule = new Schedule();
        $schedule = Schedule::getById($scheduleId);       

        $start = Carbon::parse($schedule[0]->start);
        $start = $start->format('d-m-Y');

        $owner = new User();
        $owner = User::getById($ownerId);

        $guests = User::getByScheduleId($scheduleId);

        $to_email= array();
        foreach ($guests as $user){
            array_push($to_email, $user->email);         
        }
        $messagehead = "Estimado(a):";
        $messageScheduleBody = "La reunion agendada el dia dateSchedule, ha sido cancelada por el usuario nameOwner.";

        $messageScheduleBody = str_replace('nameOwner', $owner[0]->name, $messageScheduleBody);
        $messageScheduleBody = str_replace('dateSchedule', $start, $messageScheduleBody);

        $to_subject = "Se cancela reunion";
        $to_msg_header = $messagehead;
        $to_msg_body = $messageScheduleBody;
        
        Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));
    }


    public static function sendRejectMsg($guestId, $request){

        $schedule = Schedule::getById($request['id']);  

        $owner = User::getById($schedule[0]->owner_id);


        $start = Carbon::parse($request['start']);
        $start = $start->format('d-m-Y');

        $messagehead = "Estimado nameOwner: ";
        $messageScheduleBody = "El invitado nameGuest, ha rechazado la invitacion a la reunion agendada para el dia dateSchedule.";


        $messagehead = str_replace('nameOwner', $owner[0]->name, $messagehead);
        $messageScheduleBody = str_replace('nameGuest',  Auth::user()->name, $messageScheduleBody);
        $messageScheduleBody = str_replace('dateSchedule', $start, $messageScheduleBody);

        $to_subject = "Rechazo de asistencia";
        $to_email = $owner[0]->email;
        $to_msg_header = $messagehead;
        $to_msg_body = $messageScheduleBody;
        
        Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));

    }
}