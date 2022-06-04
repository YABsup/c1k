<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
// use App\BinanceCourse;
// use Illuminate\Http\Request;

// use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class BinanceCourseController extends Controller
{

    static public function create()
    {
        //
        $client = new \GuzzleHttp\Client();
        $request = $client->get('https://api.binance.com/api/v3/ticker/bookTicker');
        $data = json_decode( $request->getBody(), true);
        $provider = 'binance';

        $privat24cash = Cache::get( 'privat24cash' );
        $privat24card = Cache::get( 'privat24card' );
        $usd_mapped = ReferenceRates::usd_mapped;
        $rub_mapped = ReferenceRates::rub_mapped;
        $euro_mapped = ReferenceRates::euro_mapped;
        $uah_mapped = ReferenceRates::uah_mapped;
        $kzt_mapped = ReferenceRates::kzt_mapped;
        $aed_mapped = ReferenceRates::aed_mapped;

        $forex = Cache::get( 'forex' );

        $exists_pairs = \App\Http\Controllers\Admin\PairController::exists_pairs();

        $cache = array();

        foreach($data as $course)
        {
            $symbol = $course['symbol'];
            $symbol = str_replace('BCHABC','BCH',$symbol);

            $bid = $course['bidPrice'];
            $ask = $course['askPrice'];

            if( in_array($symbol, $exists_pairs) )
            {
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                if( strpos($symbol,'USDT') )
                {
                    foreach($usd_mapped as $usd)
                    {
                        $symbol2 = str_replace('USDT',$usd,$symbol);
                        $cache[ $symbol2 ] = ReferenceRates::add_rate($provider, $symbol2, $bid, $ask);
                    }
                    foreach($euro_mapped as $euro)
                    {
                        $symbol2 = str_replace('USDT',$euro,$symbol);
                        $cache[ $symbol2 ] = ReferenceRates::add_rate($provider, $symbol2, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                    }

                    foreach($rub_mapped as $rub)
                    {
                        $symbol2 = str_replace('USDT',$rub,$symbol);
                        $cache[ $symbol2 ] = ReferenceRates::add_rate($provider, $symbol2, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                    }
                    foreach($aed_mapped as $aed)
                    {
                        $symbol2 = str_replace('USDT',$aed,$symbol);
                        $cache[ $symbol2 ] = ReferenceRates::add_rate($provider, $symbol2, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                    }
                    foreach($uah_mapped as $seed)
                    {
                        $symbol2 = str_replace('USDT',$seed,$symbol);
                        $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24card['USDP24UAH']['bid'], $ask * $privat24card['USDP24UAH']['ask']);
                    }
                    foreach($kzt_mapped as $seed)
                    {
                        $symbol2 = str_replace('USDT',$seed,$symbol);
                        $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                    }

                    //TODO refrefref
                    $symbol = str_replace('USDT','CASHEUR',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                    $symbol = str_replace('CASHEUR','EXMRUB',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);

                    $symbol = str_replace('EXMRUB','CASHRUB',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);

                    $symbol = str_replace('CASHRUB','SBERRUB',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                    $symbol = str_replace('SBERRUB','ACRUB',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);


                    $symbol = str_replace('ACRUB','ADVCRUB',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);

                    $symbol = str_replace('ADVCRUB','CARDEUR',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                    $symbol = str_replace('CARDEUR','ADVCEUR',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                    $symbol = str_replace('ADVCEUR','PPEUR',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);


                    $symbol = str_replace('PPEUR','CASHUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('CASHUSD','CARDUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                    $symbol = str_replace('CARDUSD','CASHUAH',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24card['USDP24UAH']['bid'], $ask * $privat24card['USDP24UAH']['ask']);

                    $symbol = str_replace('CASHUAH','MONOBUAH',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24card['USDP24UAH']['bid'], $ask * $privat24card['USDP24UAH']['ask']);

                    $symbol = str_replace('MONOBUAH','ADVCUAH',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24card['USDP24UAH']['bid'], $ask * $privat24card['USDP24UAH']['ask']);

                    $symbol = str_replace('ADVCUAH','P24UAH',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24card['USDP24UAH']['bid'], $ask * $privat24card['USDP24UAH']['ask']);

                    $symbol = str_replace('P24UAH','EPMUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('EPMUSD','EXMUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('EXMUSD','ADVCUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('ADVCUSD','USDTERC',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('USDTERC','PMUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('PMUSD','PPUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = str_replace('PPUSD','NTLRUSD',$symbol);
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                }

            }elseif( $symbol == 'ETHBTC' ){
                $symbol = 'BTCETH';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, 1/$bid, 1/$ask);
            }elseif( $symbol == 'BCHUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'BCH'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'BCH'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'BCH'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'BCHCASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'BCHCARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'BCHUSDTERC';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'BCHCASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'BCHCARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'BCHCASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'BCHP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'BCHMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'BCHMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'BCHADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'ETHUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'ETH'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }

                foreach($euro_mapped as $euro)
                {
                    $symbol = 'ETH'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'ETH'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }

                foreach($rub_mapped as $rub)
                {
                    $symbol = 'ETH'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'ETH'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }

                //\Log::error( json_encode($privat24cash) );
                $symbol = 'ETHCASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ETHCARDUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ETHADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ETHMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ETHMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'ETHP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'LTCUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'LTC'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'LTC'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'LTC'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'LTC'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'LTC'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'LTC'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'LTCCARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'LTCCASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'LTCPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'LTCPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'LTCCARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'LTCCASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'LTCCASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'LTCADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'LTCMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'LTCMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'LTCP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'ADAUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'ADA'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'ADA'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'ADA'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'ADA'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'ADA'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'ADA'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'ADACARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'ADACASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'ADAPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'ADAPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'ADACARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'ADACASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'ADACASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ADAADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ADAMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'ADAMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'ADAP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'DOGEUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'DOGE'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'DOGE'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'DOGE'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'DOGE'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'DOGE'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'DOGE'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'DOGECARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'DOGECASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'DOGEPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'DOGEPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'DOGECARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'DOGECASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'DOGECASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'DOGEADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'DOGEMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'DOGEMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'DOGEP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'DOTUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'DOT'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'DOT'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'DOT'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'DOT'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'DOT'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'DOT'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'DOTCARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'DOTCASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'DOTPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'DOTPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'DOTCARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'DOTCASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'DOTCASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'DOTADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'DOTMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'DOTMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'DOTP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'LUNAUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'LUNA'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'LUNA'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'LUNA'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'LUNA'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'LUNA'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'LUNA'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'LUNACARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'LUNACASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'LUNAPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'LUNAPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'LUNACARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'LUNACASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'LUNACASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'LUNAADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'LUNAMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'LUNAMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'LUNAP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'SOLUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'SOL'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'SOL'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'SOL'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'SOL'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'SOL'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'SOL'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'SOLCARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'SOLCASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'SOLPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'SOLPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'SOLCARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'SOLCASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'SOLCASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'SOLADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'SOLMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'SOLMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'SOLP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }elseif( $symbol == 'XRPUSDT' ){
                foreach($usd_mapped as $usd)
                {
                    $symbol = 'XRP'.$usd;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                }
                foreach($euro_mapped as $euro)
                {
                    $symbol = 'XRP'.$euro;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'XRP'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHAEDCASHUSD']['bid'], $ask / $forex['CASHAEDCASHUSD']['ask']);
                }
                foreach($rub_mapped as $rub)
                {
                    $symbol = 'XRP'.$rub;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHRUB']['bid'], $ask * $forex['USDCASHRUB']['ask']);
                }
                foreach($aed_mapped as $aed)
                {
                    $symbol = 'XRP'.$aed;
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid * $forex['USDCASHAED']['bid'], $ask * $forex['USDCASHAED']['ask']);
                }
                foreach($kzt_mapped as $seed)
                {
                    $symbol2 = 'XRP'.$seed;
                    $cache[ $symbol2 ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $forex['USDCASHKZT']['bid'], $ask * $forex['USDCASHKZT']['ask']);
                }


                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'XRPCARDEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'XRPCASHEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);
                $symbol = 'XRPPPEUR';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid / $forex['CASHEURCASHUSD']['bid'], $ask / $forex['CASHEURCASHUSD']['ask']);

                $symbol = 'XRPPPUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'XRPCARDUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $symbol = 'XRPCASHUSD';
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);

                $symbol = 'XRPCASHUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'XRPADVCUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'XRPMONOUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
                $symbol = 'XRPMONOBUAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);

                $symbol = 'XRPP24UAH';
                $cache[ $symbol ] = ReferenceRates::add_rate( $provider, $symbol, $bid * $privat24cash['USDCASHUAH']['bid'], $ask * $privat24cash['USDCASHUAH']['ask']);
            }

        }
        Cache::put($provider, $cache);

        echo json_encode(array('status'=>'200'));
        exit();
    }

}
