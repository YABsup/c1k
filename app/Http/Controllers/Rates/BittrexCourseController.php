<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use App\BittrexCourse;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class BittrexCourseController extends Controller
{
  private $pairs = array(
    'USDT-BTC',
    'USDT-BCH',
    'USDT-ETH',
    'BTC-ETH',
    'BTC-BCH',
  );
  public function create()
  {
    //
    $client = new \GuzzleHttp\Client();
    $provider = 'bittrex';
    $exists_pairs = \App\Http\Controllers\Admin\PairController::exists_pairs();
    $cache = array();
    foreach($this->pairs as $pair)
    {
      $arr = explode('-', $pair);
      if($arr[0] == 'USDT')//временный хак
      {
        $symbol = $arr[1].$arr[0];
      }else{
        $symbol = $arr[0].$arr[1];
      }

      if( in_array($symbol, $exists_pairs ) )
      {
        $request = $client->get('https://api.bittrex.com/api/v1.1/public/getticker?market='.$pair);
        if($request->getStatusCode() != 200)
        {
          exit();
        }
        $data = json_decode( $request->getBody(), true);

        $rate = new \App\ReferenceRate;
        $rate->provider = $provider;
        $rate->symbol = $symbol;

        $rate->bid = $data['result']['Bid'];
        $rate->ask = $data['result']['Ask'];

        if($symbol == 'BTCETH')
        {
          $rate->bid = 1/$rate->bid;
          $rate->ask = 1/$rate->ask;
        }

        $rate->save();
        $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask);

        if( strpos($symbol,'USDT') )
        {
          $symbol = str_replace('USDT','CASHUSD',$symbol);
          $rate = new \App\ReferenceRate;
          $rate->provider = $provider;
          $rate->bid = $data['result']['Bid'];
          $rate->ask = $data['result']['Ask'];
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
