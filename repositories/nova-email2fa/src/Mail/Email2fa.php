<?php

namespace AlogicProjects\Email2fa\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email2fa extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email_2fa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_2fa)
    {
        //
        $this->email_2fa = $email_2fa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('alogicemail2fa::email2fa');
    }
}
