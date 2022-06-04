<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Coin;

class SyncCoinsCode extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'sync:coins';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

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


        $raw = (new Client)->request('GET','https://api.alogic.com.ua/api/bestchange/currencies')->getBody();

        $currencies = json_decode($raw, true);
        if( $currencies != null )
        {

            foreach( $currencies as $currency )
            {

                $coin = Coin::whereCode($currency['code'])->first();
                if( $coin != null )
                {
                    if( $coin->adress_type == null )
                    {
                        $coin->adress_type = ($currency['type'] == 'crypto') ? 'wallet' : $currency['type'];
                    }
                    $coin->country_code = $currency['bcode'];
                    $coin->currency_type = $currency['type'];

                    $coin->save();
                }

            }

        }


    }
}
