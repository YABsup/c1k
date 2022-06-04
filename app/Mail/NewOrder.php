<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $exchange;
    public $to_email;
    public $confirm;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($to, $user, $exchange)
    {
        //
        $this->confirm = hash('sha256', 'confirm'.$exchange->uuid.$exchange->id.$exchange->user_ip);
        $this->user = $user;
        $this->exchange = $exchange;
        $this->to_email = $to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->to_email)
                    ->subject(__('New exchange'))
                    ->view('mail.order.new2');
    }
}
