<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public $sub;
    public $msgHeader;
    public $msgBody;

    public function __construct($subject, $messageHead, $messageBody)
    {
        $this->sub = $subject;
        $this->msgHeader = $messageHead;
        $this->msgBody = $messageBody;
            }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $e_sub = $this -> sub;
        $e_msgHeader = $this -> msgHeader;
        $e_msgeBody = $this -> msgBody;

        return $this->view('mails.sendemail', compact("e_msgHeader", "e_msgeBody"))->subject($e_sub);
    }
}
