<?php

namespace AlogicProjects\Email2fa\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Mail;
use AlogicProjects\Email2fa\Mail\Email2fa;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        $user = $event->user;
        $user->email_2fa = random_int(100000,999999);
        $user->save();


        Mail::to( $user->email )->send( new Email2fa($user->email_2fa) );


    }
}
