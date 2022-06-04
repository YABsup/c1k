<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Rates\ReferenceRates;

class ParseHoubi extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'parse:huobi';

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
        $full_cache = [];
        $provider = 'huobi-otc';
        $coinId = 1;
        $ask_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=$coinId&currency=1&tradeType=sell&currPage=1&payMethod=0&acceptOrder=0&country=&blockType=block&online=1&range=0&amount=";
        $bid_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=$coinId&currency=1&tradeType=buy&currPage=1&payMethod=0&acceptOrder=-1&country=&blockType=block&online=1&range=0&amount=";

        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = $client->get($bid_url);
        $data_raw = $request->getBody();
        $data =  json_decode($data_raw, true);
        if( $data == null)
        {
            //сенд админ нотифи
            return 1;
        }
        if( !array_key_exists('data',$data) )
        {
            //сенд админ нотифи
            return 1;
        }
        if( count($data['data']) == 0 )
        {
            //сенд админ нотифи
            return 1;
        }
        $bid = $data['data'][0]['price'];

        sleep(3);
        $request = $client->get($ask_url);
        $data_raw = $request->getBody();
        $data =  json_decode($data_raw, true);
        if( $data == null)
        {
            //сенд админ нотифи
            return 1;
        }
        if( !array_key_exists('data',$data) )
        {
            //сенд админ нотифи
            return 1;
        }
        if( count($data['data']) == 0 )
        {
            //сенд админ нотифи
            return 1;
        }
        $ask = $data['data'][0]['price'];

        $cache = [];

        foreach( ReferenceRates::cny_mapped as $pair)
        {
            $ref_rate = new \App\ReferenceRate;
            $ref_rate->provider = $provider;
            $ref_rate->bid = $bid;
            $ref_rate->ask = $ask;
            $ref_rate->symbol = 'BTC'.$pair;
            $ref_rate->save();
            $cache[ $ref_rate->symbol ] = array( 'bid'=>$ref_rate->bid, 'ask'=>$ref_rate->ask);
        }

        $full_cache = array_merge($full_cache, $cache );

        sleep(3);
        $coinId = 2;
        $ask_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=$coinId&currency=1&tradeType=sell&currPage=1&payMethod=0&acceptOrder=0&country=&blockType=block&online=1&range=0&amount=";
        $bid_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=$coinId&currency=1&tradeType=buy&currPage=1&payMethod=0&acceptOrder=-1&country=&blockType=block&online=1&range=0&amount=";

        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = $client->get($bid_url);
        $data_raw = $request->getBody();
        $data =  json_decode($data_raw, true);
        if( $data == null)
        {
            //сенд админ нотифи
            return 1;
        }
        if( !array_key_exists('data',$data) )
        {
            //сенд админ нотифи
            return 1;
        }
        if( count($data['data']) == 0 )
        {
            //сенд админ нотифи
            return 1;
        }
        $bid = $data['data'][0]['price'];

        sleep(3);
        $request = $client->get($ask_url);
        $data_raw = $request->getBody();
        $data =  json_decode($data_raw, true);
        if( $data == null)
        {
            //сенд админ нотифи
            return 1;
        }
        if( !array_key_exists('data',$data) )
        {
            //сенд админ нотифи
            return 1;
        }
        if( count($data['data']) == 0 )
        {
            //сенд админ нотифи
            return 1;
        }
        $ask = $data['data'][0]['price'];

        $cache = [];

        foreach(ReferenceRates::usd_mapped as $pair_a )
        {
            foreach( ReferenceRates::cny_mapped as $pair_b)
            {
                $ref_rate = new \App\ReferenceRate;
                $ref_rate->provider = $provider;
                $ref_rate->bid = $bid;
                $ref_rate->ask = $ask;
                $ref_rate->symbol = $pair_a.$pair_b;
                $ref_rate->save();
                $cache[ $ref_rate->symbol ] = array( 'bid'=>$ref_rate->bid, 'ask'=>$ref_rate->ask);
            }
        }

        $full_cache = array_merge($full_cache, $cache );

        sleep(3);
        $provider = 'huobi-otc';
        $coinId = 3;
        $ask_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=$coinId&currency=1&tradeType=sell&currPage=1&payMethod=0&acceptOrder=0&country=&blockType=block&online=1&range=0&amount=";
        $bid_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=$coinId&currency=1&tradeType=buy&currPage=1&payMethod=0&acceptOrder=-1&country=&blockType=block&online=1&range=0&amount=";

        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = $client->get($bid_url);
        $data_raw = $request->getBody();
        $data =  json_decode($data_raw, true);
        if( $data == null)
        {
            //сенд админ нотифи
            return 1;
        }
        if( !array_key_exists('data',$data) )
        {
            //сенд админ нотифи
            return 1;
        }
        if( count($data['data']) == 0 )
        {
            //сенд админ нотифи
            return 1;
        }
        $bid = $data['data'][0]['price'];

        sleep(3);
        $request = $client->get($ask_url);
        $data_raw = $request->getBody();
        $data =  json_decode($data_raw, true);
        if( $data == null)
        {
            //сенд админ нотифи
            return 1;
        }
        if( !array_key_exists('data',$data) )
        {
            //сенд админ нотифи
            return 1;
        }
        if( count($data['data']) == 0 )
        {
            //сенд админ нотифи
            return 1;
        }
        $ask = $data['data'][0]['price'];

        $cache = [];

        foreach( ReferenceRates::cny_mapped as $pair)
        {
            $ref_rate = new \App\ReferenceRate;
            $ref_rate->provider = $provider;
            $ref_rate->bid = $bid;
            $ref_rate->ask = $ask;
            $ref_rate->symbol = 'ETH'.$pair;
            $ref_rate->save();
            $cache[ $ref_rate->symbol ] = array( 'bid'=>$ref_rate->bid, 'ask'=>$ref_rate->ask);
        }

        $full_cache = array_merge($full_cache, $cache );
        Cache::forever( $provider, $full_cache);

        //sleep(3);
    }
}
