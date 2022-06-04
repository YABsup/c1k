<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\BitstampCourse;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class BitstampCourseController extends Controller
{
  private $pairs = array(
    'btcusd', 'btceur', 'eurusd', 'xrpusd',
    'xrpeur', 'xrpbtc', 'ltcusd', 'ltceur',
    'ltcbtc', 'ethusd', 'etheur', 'ethbtc',
    'bchusd', 'bcheur', 'bchbtc'
  );

  public function create()
  {
    //
    $client = new \GuzzleHttp\Client();
    $provider = 'bitstamp';

    $exists_pairs = \App\Http\Controllers\Admin\PairController::exists_pairs();
    $cache = array();
    foreach($this->pairs as $pair)
    {
      $symbol = strtoupper($pair);
      $symbol = str_replace('USD','USDT',$symbol);
      $symbol = str_replace('EUR','CASHEUR',$symbol);

      if( in_array($symbol, $exists_pairs) )
      {
        $request = $client->get('https://www.bitstamp.net/api/v2/ticker/'.$pair.'/');
        $course = json_decode( $request->getBody(), true);
        $rate = new \App\ReferenceRate;
        $rate->provider = $provider;
        $rate->bid = $course['bid'];
        $rate->ask = $course['ask'];
        $rate->symbol = $symbol;
        $rate->save();
        $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask);
        if( strpos($symbol,'USDT') )
        {
          $symbol = str_replace('USDT','CASHUSD',$symbol);
          $rate = new \App\ReferenceRate;
          $rate->provider = $provider;
          $rate->bid = $course['bid'];
          $rate->ask = $course['ask'];
          $rate->symbol = $symbol;
          $rate->save();
          $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask);
        }
      }
    }

    Cache::put($provider, $cache);

    echo json_encode(array('status'=>'200'));
    exit();
  }
}
