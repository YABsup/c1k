<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\BitfinexCourse;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class BitfinexCourseController extends Controller
{

  static public function create()
  {
    //
    $client = new \GuzzleHttp\Client();
    try {
      $request = $client->get('https://api-pub.bitfinex.com/v2/tickers?symbols=ALL');
    } catch (\GuzzleHttp\Exception\ServerException $e) {
      return;
    }

    $data = json_decode( $request->getBody(), true);
    $provider = 'bitfinex';

    $exists_pairs = \App\Http\Controllers\Admin\PairController::exists_pairs();
    $cache = array();
    foreach($data as $course)
    {

      if( substr( $course['0'],0,1 ) == 't')
      {
        $symbol = substr( $course['0'],1 );
        $symbol = str_replace('USD','USDT',$symbol);
        $symbol = str_replace('EUR','CASHEUR',$symbol);
        $symbol = str_replace('ETHBTC','BTCETH',$symbol);

        if( in_array($symbol, $exists_pairs) )
        {
          $rate = new \App\ReferenceRate;
          $rate->provider = $provider;
          $rate->bid = $course[1];
          $rate->ask = $course[3];

          if($symbol == 'BTCETH')
          {
            $rate->bid = 1/$rate->bid;
            $rate->ask = 1/$rate->ask;
          }
          $rate->symbol = $symbol;
          $rate->save();
          $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask);
          if( strpos($symbol,'USDT') )
          {
            $symbol = str_replace('USDT','CASHUSD',$symbol);
            $rate = new \App\ReferenceRate;
            $rate->provider = $provider;
            $rate->bid = $course[1];
            $rate->ask = $course[3];
            $rate->symbol = $symbol;
            $rate->save();
            $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask);
          }
        }
      }
    }
    Cache::put($provider, $cache);

    echo json_encode(array('status'=>'200'));
    exit();
  }
}
