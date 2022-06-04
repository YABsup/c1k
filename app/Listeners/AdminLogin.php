<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use App\Mail\ToFactorCode;
use Illuminate\Support\Facades\Mail;

class AdminLogin
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
        if($event->user->role != 'user')
        {
            $event->user->to_factor = random_int(100000,999999);
            $event->user->save();
//            Log::error($event->user->to_factor);
            try
            {
                Mail::queue(new ToFactorCode( $event->user ));
            } catch (Exception $e) {
                Log::error( $e->getMessage() );
            }
//            Log::error($event->user);
        }
    }
}
