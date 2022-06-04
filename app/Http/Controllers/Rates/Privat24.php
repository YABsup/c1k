<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class Privat24 extends Controller
{

  static public function index()
  {
    echo json_encode(array('status'=>'405'));

    exit();
  }
  static public function show($reference_rate){

    $this_class_name = get_called_class();
    if( in_array($reference_rate, get_class_methods( $this_class_name) ))
    {
      call_user_func($this_class_name.'::'.$reference_rate);
    }else{
      echo json_encode(array('status'=>'405'));
    }
    //exit();
  }
  static public function get_privat24()
  {
    echo json_encode(array('privat24cash'=>Cache::get('privat24cash'),'privat24card'=>Cache::get('privat24card')));
    exit();
  }
  static public function update_privat24()
  {
    $coure_type = array(
      5=> array( 'price_provider'=>'privat24cash', 'base_currency'=>'CASHUAH'),
      11=>array( 'price_provider'=>'privat24card', 'base_currency'=>'P24UAH'),
    );

    $client = new \GuzzleHttp\Client();
    foreach($coure_type as $type=>$params)
    {
      $cache = array();
      $request = $client->get('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid='.$type);
      $data = json_decode( $request->getBody(), true);
      foreach($data as $course)
      {
        if( $course['ccy'] == 'RUR')
        {
          $course['ccy']='RUB';
        }
        if( $course['base_ccy'] == 'UAH')
        {
          $course['base_ccy']= $params['base_currency'];
        }
        $ref_rate = new \App\ReferenceRate;
        $ref_rate->provider = $params['price_provider'];
        $ref_rate->symbol = $course['ccy'].$course['base_ccy'];
        $ref_rate->bid = $course['buy'];
        $ref_rate->ask = $course['sale'];
        $ref_rate->save();

        echo $params['price_provider'].' '.$ref_rate->symbol.' '.$ref_rate->bid.' '.$ref_rate->ask."\n<br />";
        $cache[ $ref_rate->symbol ] = array( 'bid'=>$ref_rate->bid, 'ask'=>$ref_rate->ask);
      }

      Cache::forever( $params['price_provider'], $cache);
    }
    echo json_encode(array('status'=>'200'));
    exit();
  }

  static public function get_forex()
  {
    echo json_encode( Cache::get('forex') );
    exit();
  }
  static public function update_forex()
  {
    $pairs = array(18,152,2124,2186,2208);
    $client = new \GuzzleHttp\Client();
    $request = $client->get('https://ru.widgets.investing.com/live-currency-cross-rates?theme=darkTheme&cols=bid,ask&pairs='.implode(',',$pairs));
    $data = $request->getBody();

    $dom = new \DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($data);

    libxml_use_internal_errors(false);
    $provider = 'forex';
    $cache = array();
    foreach($pairs as $pair)
    {
      $try = $dom->getElementById('pair_'.$pair);
      if($try->firstChild->nodeName == 'div' )
      {
        $pair = str_replace(chr( 194 ) . chr( 160 ),'',$try->childNodes[0]->nodeValue);
        $bid = str_replace(',', '.', $try->childNodes[2]->nodeValue);
        $ask = str_replace(',', '.', $try->childNodes[4]->nodeValue);
      }else{
        $pair = str_replace(chr( 194 ) . chr( 160 ),'',$try->childNodes[1]->nodeValue);
        $bid = str_replace(',', '.', $try->childNodes[3]->nodeValue);
        $ask = str_replace(',', '.', $try->childNodes[5]->nodeValue);
      }
      $pair = str_replace('/','',$pair);
      $ref_rate = new \App\ReferenceRate;
      $ref_rate->provider = $provider;
      $ref_rate->bid = $bid;
      $ref_rate->ask = $ask;
      $ref_rate->symbol = $pair;
      $ref_rate->save();
      $cache[ $ref_rate->symbol ] = array( 'bid'=>$ref_rate->bid, 'ask'=>$ref_rate->ask);
    }
    Cache::forever( $provider, $cache);
    echo json_encode(array('status'=>'200'));
    //exit();
  }
  static public function get_unexbank()
  {
    echo json_encode( Cache::get('unexbank') );
    //exit();
  }
  static public function update_unexbank()
  {
    $pairs = array('USD', 'EUR', 'RUB', 'GBP', 'CHF', 'PLN','CZK');
    $provider = 'unexbank';
    $cache = array();
    $client = new \GuzzleHttp\Client(['verify' => false]);
    $request = $client->get('https://unexbank.ua/site/valute_drag2.php?lang=UA&the_date='.date('d-m-Y',time()).'&num_kurs=44');
    $data = $request->getBody();

    $doc = new \DOMDocument();
    $doc->loadHTML($data);
    $tdx = $doc->getElementsByTagName('td');

    for($i=0; $i < $tdx->length;$i++)
    {
      if( in_array( $tdx[$i]->nodeValue, $pairs) )
      {
        $ref_rate = new \App\ReferenceRate;
        $ref_rate->provider = $provider;
        if($tdx[$i+2]->nodeValue == '-')
        {
          echo json_encode(array('status'=>'200'));
          exit();
        }
        $ref_rate->bid = $tdx[$i+2]->nodeValue;
        $ref_rate->ask = $tdx[$i+3]->nodeValue;
        $ref_rate->symbol = $tdx[$i]->nodeValue.'UAH';
        $ref_rate->save();
        $cache[ $ref_rate->symbol ] = array( 'bid'=>$ref_rate->bid, 'ask'=>$ref_rate->ask);
      }
    }
    Cache::forever( $provider, $cache);
    echo json_encode(array('status'=>'200'));
    //exit();
  }
  static public function clear_old_rates()
  {
    $ref_rate = \App\ReferenceRate::where( 'created_at', '<', Carbon::now()->subHour(3) )->delete();
    $rate = \App\Rate::where( 'created_at', '<', Carbon::now()->subHour(3) )->delete();
    echo json_encode(array('status'=>'200'));
  }
}
