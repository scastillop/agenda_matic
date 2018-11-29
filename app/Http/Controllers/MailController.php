<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use App\User;
use App\Schedule;
use App\Guest;
use Carbon\Carbon;


class MailController extends Controller
{


    public static function sendScheduleMsg($ownerId, $guests, $schedule){


        $owner = new User ;
        $owner = User::getById($ownerId);

        $start = Carbon::parse($schedule->start);
        $start = $start->format('d-m-Y');

        foreach ($guests as $user) {
            $guest = new User();
            $guest = User::getById($user);

            

            $messagehead = "Estimado nameUser: ";
            $messageScheduleBody = "El usuario ownerUser, le ha agendado una reunion para el dia dateSchedule.";


            $messagehead = str_replace('nameUser', $guest[0]->name, $messagehead);
            $messageScheduleBody = str_replace('ownerUser', $owner[0]->name, $messageScheduleBody);
            $messageScheduleBody = str_replace('dateSchedule', $start , $messageScheduleBody);

            $to_subject = "Agendamiento reunion";
            $to_email = $guest[0]->email;
            $to_msg_header = $messagehead;
            $to_msg_body = $messageScheduleBody;
            
            Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));

        }
    }


    public static function sendCancelMsg($ownerId, $scheduleId){


        $schedule = new Schedule();
        $schedule = Schedule::getById($scheduleId);       

        $start = Carbon::parse($schedule[0]->start);
        $start = $start->format('d-m-Y');

        $owner = new User();
        $owner = User::getById($ownerId);

        $guests = new Guest();
        $guests = Guest::getBySchedule($scheduleId);

        foreach ($guests as $user) {

            $guest = new User();
            $guest = User::getById($user->user_id);
            var_dump($guest);

            $messagehead = "Estimado nameGuest: ";
            $messageScheduleBody = "La reunion agendada el dia dateSchedule, ha sido cancelada por el usuario nameOwner.";


            $messagehead = str_replace('nameGuest', $guest[0]->name, $messagehead);
            $messageScheduleBody = str_replace('nameOwner', $owner[0]->name, $messageScheduleBody);
            $messageScheduleBody = str_replace('dateSchedule', $start, $messageScheduleBody);

            $to_subject = "Se cancela reunion";
            $to_email = $guest[0]->email;
            $to_msg_header = $messagehead;
            $to_msg_body = $messageScheduleBody;
            
            Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));
            
        }
    }


    public static function sendRejectMsg($guestId, $request){

        $schedule = new Schedule();
        $schedule = Schedule::getById($request['id']);  

        $guest = new User();
        $guest = User::getById($guestId);

        $owner = new User();
        $owner = User::getById($schedule[0]->owner_id);

        var_dump($owner);

        $start = Carbon::parse($request['start']);
        $start = $start->format('d-m-Y');

        $messagehead = "Estimado nameOwner: ";
        $messageScheduleBody = "El invitado nameGuest, ha rechazado la invitacion a la reunion agendada para el dia dateSchedule.";


        $messagehead = str_replace('nameOwner', $guest[0]->name, $messagehead);
        $messageScheduleBody = str_replace('nameGuest',  $owner[0]->name, $messageScheduleBody);
        $messageScheduleBody = str_replace('dateSchedule', $start, $messageScheduleBody);

        $to_subject = "Rechazo de asistencia";
        $to_email = $owner[0]->email;
        $to_msg_header = $messagehead;
        $to_msg_body = $messageScheduleBody;
        
        Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));

    }
}