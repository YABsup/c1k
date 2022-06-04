<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TokensRegen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:regen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regen users tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $users = \App\User::get();

        foreach( $users as $user)
        {
            $changed = false;
            if($user->api_token == null)
            {
                $user->api_token = bin2hex(random_bytes(32));
                $changed = true;
            }
            if($user->api_secret == null)
            {
                $user->api_secret = bin2hex(random_bytes(32));
                $changed = true;
            }
            if( $changed )
            {
                $user->save();
            }
        }
    }
}
