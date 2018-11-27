<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
 
class MailController extends Controller
{
    public function sendSchedule(Request $request)
    {

    	$messagehead = "Estimado nameUser: ";
    	$messageScheduleBody = "El usuario ownerUser, le ha agendado una reunion para el dia dateSchedule.";

    	$request->validate([
    		'nameUser' => 'required',
            'subject' => 'required|string', 
            'to_email' => 'required|string',
            'ownerUser' => 'required|string',
            'dateSchedule' => 'required'

        ]);


        $messagehead = str_replace('nameUser', $request['nameUser'], $messagehead);
        $messageScheduleBody = str_replace('ownerUser', $request['ownerUser'], $messageScheduleBody);
        $messageScheduleBody = str_replace('dateSchedule', $request['dateSchedule'], $messageScheduleBody);

	    $to_subject = $request['subject'];
		$to_email = $request['to_email'];
		$to_msg_header = $messagehead;
		$to_msg_body = $messageScheduleBody;
	    
	    Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg_header, $to_msg_body ));
		
    }
}