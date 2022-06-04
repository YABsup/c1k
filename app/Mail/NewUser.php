<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $to_email;
    public $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($to, $user, $password='')
    {
        //
        $this->user = $user;
        $this->to_email = $to;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->to_email)
                    ->subject('Информация для доступа в аккаунт')
                    ->view('mail.user.new');
    }
}
