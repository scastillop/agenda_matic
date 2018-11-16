<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
 
class MailController extends Controller
{
    public function send()
    {
	    //$to_name = 'Jota';
	    $to_subject = 'Test';
		$to_email = 'jonathan.arce.93@gmail.com';
		$to_msg = 'Holaaaaaaaaaaaaaaaaa';
		//$data = array('name'=>"Oe wea", "body" => "Test mail");
	    
	    Mail::to($to_email)->send(new SendMail($to_subject ,$to_msg ));

		//Mail::send('mails.mail', $data, function($message) use ($to_name, $to_email) {
//		    $message->to($to_email, $to_name)
//		            ->subject('Artisans Web Testing Mail');
//		    $message->from('agendamiento.info@gmail.com','Artisans Web');
		//});

		
    }
}