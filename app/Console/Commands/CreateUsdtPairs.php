<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUsdtPairs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usdt:seedpairs';

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
        $symbols = ['USDTCASHUSD'=>14,'USDTERCCASHUSD'=>184,'USDTTRCCASHUSD'=>186];

        foreach( $symbols as $symbol=>$coin_id )
        {
            $ref_pairs = \App\Pair::whereSymbol($symbol)->get();
            foreach( $ref_pairs as $ref_pair )
            {
                foreach( $symbols as $test_symbol => $code )
                {
                    $test_pair = \App\Pair::whereSymbol($test_symbol)->whereCityId($ref_pair->city_id)->first();
                    if( $test_pair == null )
                    {
                        $test_pair = $ref_pair->replicate();
                        $test_pair->base_currency_id = $code;
                        $test_pair->symbol = $test_symbol;
                        $test_pair->save();
                    }
                }
            }
        }

        $symbols = ['USDTCASHRUB'=>14,'USDTERCCASHRUB'=>184,'USDTTRCCASHRUB'=>186];

        foreach( $symbols as $symbol=>$coin_id )
        {
            $ref_pairs = \App\Pair::whereSymbol($symbol)->get();
            foreach( $ref_pairs as $ref_pair )
            {
                foreach( $symbols as $test_symbol => $code )
                {
                    $test_pair = \App\Pair::whereSymbol($test_symbol)->whereCityId($ref_pair->city_id)->first();
                    if( $test_pair == null )
                    {
                        $test_pair = $ref_pair->replicate();
                        $test_pair->base_currency_id = $code;
                        $test_pair->symbol = $test_symbol;
                        $test_pair->save();
                    }
                }
            }
        }
        
        // $symbols = ['USDTCASHUAH'=>14,'USDTERCCASHUAH'=>184,'USDTTRCCASHUAH'=>186];
        // foreach( $symbols as $symbol=>$coin_id )
        // {
        //     $ref_pairs = \App\Pair::whereSymbol($symbol)->get();
        //     foreach( $ref_pairs as $ref_pair )
        //     {
        //         foreach( $symbols as $test_symbol => $code )
        //         {
        //             $test_pair = \App\Pair::whereSymbol($test_symbol)->whereCityId($ref_pair->city_id)->first();
        //             if( $test_pair == null )
        //             {
        //                 $test_pair = $ref_pair->replicate();
        //                 $test_pair->base_currency_id = $code;
        //                 $test_pair->symbol = $test_symbol;
        //                 $test_pair->save();
        //             }
        //         }
        //     }
        // }

        $symbols = ['CASHEURUSDT'=>14,'CASHEURUSDTERC'=>184,'CASHEURUSDTTRC'=>186];

        foreach( $symbols as $symbol=>$coin_id )
        {
            $ref_pairs = \App\Pair::whereSymbol($symbol)->get();
            foreach( $ref_pairs as $ref_pair )
            {
                foreach( $symbols as $test_symbol => $code)
                {
                    $test_pair = \App\Pair::whereSymbol($test_symbol)->whereCityId($ref_pair->city_id)->first();
                    if( $test_pair == null )
                    {
                        $test_pair = $ref_pair->replicate();
                        $test_pair->quote_currency_id = $code;
                        $test_pair->symbol = $test_symbol;
                        $test_pair->save();
                    }
                }
            }
        }
    }
}
