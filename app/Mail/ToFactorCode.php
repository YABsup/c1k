<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToFactorCode extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $to_factor;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $user )
    {
        //
        $this->user = $user;
        $this->to_factor = $user->to_factor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
        ->markdown('mail.to_factor')
        ->with(['user'=>$this->user,'to_factor'=>$this->to_factor]);
    }
}
