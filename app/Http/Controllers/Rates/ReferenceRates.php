<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ReferenceRates extends Controller
{
  const usd_mapped = array(
    'USD',
    'USDT',
    'CASHUSD',
    'CARDUSD',
    'CPTSUSD',
    'ADVCUSD',
    'EPMUSD',
    'EXMUSD',
    'EPSVUSD',
    'USDTERC',
    'USDERC',
    'PMUSD',
    'PPUSD',
    'PRUSD',
    'PNRUSD',
    'SKLUSD',
    'NTLRUSD',
    'USDTTRC',
    'EPAYUSD',
    'USDC',
    'BUSD',
    'USDTBEP20',
    'USDTBEP',
    'USDBEP20',
    'PMVUSD',
  );
  const euro_mapped = array(
    'QWEUR',
    'PMEUR',
    'PPEUR',
    'ADVCEUR',
    'PREUR',
    'SKLEUR',
    'NTLREUR',
    'PSCEUR',
    'EPMEUR',
    'NIXEUR',
    'EPAYEUR',
    'TRDEUR',
    'EXMEUR',
    'WIREEUR',
    'SEPAEUR',
    'WUEUR',
    'MGEUR',
    'RMTFEUR',
    'CASHEUR',
    'CARDEUR',
  );
  const uah_mapped = array(
    'UAH',
    'EXMUAH',
    'CASHUAH',
    'ADVCUAH',
    'CARDUAH',
    'P24UAH',
    'MONOBUAH',
  );
  const rub_mapped = array(
    'RUB',
    'ADVRUB',
    'EXMRUB',
    'ADVCRUB',
    'CASHRUB',
    'CARDRUB',
    'SBERRUB',
    'ACRUB',
    'YAMRUB',
    'PRRUB',
  );

  const kzt_mapped = array(
    'KZT',
    'KSPBDKZT',
    'KSPBCKZT',
    'KSPBGKZT',
    'KSPGKZT',
    'KSPBKZT',
    'ADVCKZT',
    'CASHKZT',
  );

  const cny_mapped = array(
    'WIRECNY',
    'ALPCNY',
    'CARDCNY',
  );
  const aed_mapped = array(
    'CASHAED',
    'AED',
  );

  static public function add_rate($provider, $symbol, $bid, $ask)
  {
    $ref_rate = new \App\ReferenceRate;
    $ref_rate->provider = $provider;
    $ref_rate->symbol = $symbol;
    $ref_rate->bid = $bid;
    $ref_rate->ask = $ask;
    $ref_rate->save();
    return array( 'bid'=>$bid, 'ask'=>$ask);
  }


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
    $usd_mapped = ReferenceRates::usd_mapped;
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
        //        $ref_rate = new \App\ReferenceRate;
        //        $ref_rate->provider = $params['price_provider'];
        //        $ref_rate->symbol = $course['ccy'].$course['base_ccy'];
        //        $ref_rate->bid = $course['buy'];
        //        $ref_rate->ask = $course['sale'];
        //        $ref_rate->save();

        if( $course['ccy'] == 'USD')
        {
          foreach($usd_mapped as $usd)
          {
            $symbol = $usd.$course['base_ccy'];
            $cache[ $symbol ] = ReferenceRates::add_rate($params['price_provider'], $symbol, $course['buy'], $course['sale']);
          }
        }else{
          $symbol = $course['ccy'].$course['base_ccy'];
          $cache[ $symbol ] = ReferenceRates::add_rate($params['price_provider'], $symbol, $course['buy'], $course['sale']);
        }
      }
      Cache::forever( $params['price_provider'], $cache);
    }
    echo json_encode(array('status'=>'200'));
    //exit();
  }

  static public function get_forex()
  {
    echo json_encode( Cache::get('forex') );
    exit();
  }

  static public function get_unexbank()
  {
    echo json_encode( Cache::get('unexbank') );
    exit();
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

  static public function update_huobi()
  {
    $provider = 'huobi';
    $ask_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=1&currency=1&tradeType=sell&currPage=1&payMethod=0&acceptOrder=0&country=&blockType=block&online=1&range=0&amount=";
    $bid_url = "https://otc-api-hk.eiijo.cn/v1/data/trade-market?coinId=1&currency=1&tradeType=buy&currPage=1&payMethod=0&acceptOrder=-1&country=&blockType=block&online=1&range=0&amount=";


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
