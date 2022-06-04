<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewAnketa extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $to_email;
    public $code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($to, $user,$code)
    {
        //
        $this->user = $user;
        $this->to_email = $to;
        $this->code= $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->to_email)
                    ->subject(__('anketa.submit.h4'))
                    ->view('mail.anketa-new');
    }
}
