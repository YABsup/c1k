<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedPairs extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'seed:pairs';

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
        $cities_seed = [ 267, 73, 72, 71, 70, 54, 47, 43, 35, 29, 17, 16, 10, 5];

        $pairs_seed = [
            'CASHEURUSDT',
            'CASHEURUSDTERC',

            'USDTCASHUSD',
            'USDTERCCASHUSD',

            'BTCCASHUSD',
            'BTCCASHEUR',

            'ETHCASHUSD',
            'ETHCASHEUR'
        ];
        exit;

        foreach( $cities_seed as $city_seed)
        {
            $city = \App\City::find($city_seed);
            if( $city != null)
            {
                $city->ref_city_id = 22;
                $city->ref_bid_coef = 0.4;
                $city->ref_ask_coef = 0.4;
                //$city->save();
                exit;
            }
        }


        foreach( $cities_seed as $city_seed)
        {

            foreach( $pairs_seed as $symbol_seed)
            {
                $new_pair = \App\Pair::whereSymbol($symbol_seed)->whereCityId($city_seed)->first();
                if( $new_pair != null )
                {
                    echo $new_pair->city->name." - ",$new_pair->symbol."\n";
                }else{
                    $ref_pair = \App\Pair::whereSymbol($symbol_seed)->whereCityId(22)->first();
                    if( $ref_pair != null)
                    {
                        $new_pair = $ref_pair->replicate();
                        $new_pair->city_id = $city_seed;
                        $new_pair->active = false;
                        //$new_pair->save();
                        exit();
                    }
                }
            }


        }
    }
}
