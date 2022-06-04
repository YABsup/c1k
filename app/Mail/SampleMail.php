<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SampleMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $to_email;
    public $subject;
    public $sample_mail_text;
    public $view;
    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct($to, $subject, $sample_mail_text, $view = '')
    {
        //
        $this->to_email = $to;
        $this->subject = $subject;
        $this->sample_mail_text = $sample_mail_text;
        $this->view = $view;
    }

    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        if($this->view == '')
        {
            return $this->to($this->to_email)
            ->subject($this->subject)
            ->view('mail.sample');
        }else{
            return $this->to($this->to_email)
            ->subject($this->subject)
            ->view($this->view);
        }
    }
}
