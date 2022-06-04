<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ExmoCourseController extends Controller
{

  static public function create()
  {
    //
    $client = new \GuzzleHttp\Client();
    $request = $client->get('https://api.exmo.com/v1/ticker/');
    $data = json_decode( $request->getBody(), true);
    $provider = 'exmo';

    $privat24cash = Cache::get( 'privat24cash' );
    $privat24card = Cache::get( 'privat24card' );

    $exists_pairs = \App\Http\Controllers\Admin\PairController::exists_pairs();

    $cache = array();

    foreach($data as $symbol=>$course)
    {
      $symbol = str_replace('_','', $symbol);
      $bid = $course['buy_price'];
      $ask = $course['sell_price'];

      if( in_array($symbol, $exists_pairs) )
      {
        $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
      }
    }
    Cache::put($provider, $cache);

    echo json_encode(array('status'=>'200'));
    exit();
  }

}
