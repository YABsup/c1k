<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
// use App\BittrexCourse;
// use Illuminate\Http\Request;
// use App\Rates;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ForexCourseController extends Controller
{
    static public function create()
    {
        $pairs = array(1,18,152,2148,2186,2208,1691,1709,9304,9530,940802,2111,940809);
        $client = new \GuzzleHttp\Client();
        $request = $client->get('https://ru.widgets.investing.com/live-currency-cross-rates?theme=darkTheme&cols=bid,ask&pairs='.implode(',',$pairs));
        $data = $request->getBody();

        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);

        libxml_use_internal_errors(false);
        $provider = 'forex';
        $usd_mapped = ReferenceRates::usd_mapped;

        $rub_mapped = ReferenceRates::rub_mapped;
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
            $curr = explode('/',$pair);


            if( ($curr[0] == 'UAH') && ($curr[1] == 'RUB'))
            {
                $uah_mapped = ReferenceRates::uah_mapped;
                $rub_mapped = ReferenceRates::rub_mapped;
                $symbol = 'UAHRUB';
                $cache[ $curr[0].$curr[1] ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $rate = $cache['UAHRUB'];
                foreach($rub_mapped as $rub)
                {
                    foreach( $uah_mapped as $uah ){
                        $symbol = $uah.$rub;
                        $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
                    }
                }
            }
            if( ($curr[0] == 'CNY') && ($curr[1] == 'RUB'))
            {
                $cny_mapped = ReferenceRates::cny_mapped;
                $rub_mapped = ReferenceRates::rub_mapped;
                $symbol = 'CNYRUB';
                $cache[ $curr[0].$curr[1] ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                $rate = $cache['CNYRUB'];
                foreach($cny_mapped as $cny)
                {
                    foreach( $rub_mapped as $rub ){
                        $symbol = $cny.$rub;
                        $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
                    }
                }
            }


            foreach($usd_mapped as $usd)
            {
                if($curr[0] == 'USD')
                {
                    $symbol = $usd.'CASH'.$curr[1];
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    $symbol = $usd.'CARD'.$curr[1];
                    $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    if($curr[1] == 'UAH')
                    {
                        foreach( ReferenceRates::uah_mapped as $seed )
                        {
                            $symbol = $usd.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }elseif($curr[1] == 'RUB')
                    {
                        foreach( ReferenceRates::rub_mapped as $seed )
                        {
                            $symbol = $usd.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }elseif($curr[1] == 'KZT')
                    {
                        foreach( ReferenceRates::kzt_mapped as $seed )
                        {
                            $symbol = $usd.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }elseif($curr[1] == 'CNY')
                    {
                        foreach( ReferenceRates::cny_mapped as $seed )
                        {
                            $symbol = $usd.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }elseif($curr[1] == 'AED')
                    {
                        foreach( ReferenceRates::aed_mapped as $seed )
                        {
                            $symbol = $usd.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }
                }else if($curr[0] == 'EUR'){
                    if($curr[1] == 'UAH')
                    {
                        foreach( ReferenceRates::uah_mapped as $seed )
                        {
                            $symbol = 'EUR'.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }elseif($curr[1] == 'RUB'){
                        foreach( ReferenceRates::rub_mapped as $seed )
                        {
                            $symbol = 'EURCASH'.$seed;
                            $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                        }
                    }elseif($curr[1] == 'USD'){
                        $symbol = 'CASHEURCASHUSD';
                        $cache[ 'CASHEURCASHUSD' ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    }
                }else if($curr[0] == 'AED'){
                    if($curr[1] == 'USD'){
                        $symbol = 'CASHAEDCASHUSD';
                        $cache[ 'CASHAEDCASHUSD' ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    }
                }else if($curr[0] == 'UAH'){
                    if($curr[1] == 'RUB'){
                        $symbol = 'CASHUAH'.'CASHRUB';
                        $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $bid, $ask);
                    }
                }

            }

        }

        $euro_mapped = ReferenceRates::euro_mapped;
        $usd_mapped = ReferenceRates::usd_mapped;
        $rub_mapped = ReferenceRates::rub_mapped;
        $aed_mapped = ReferenceRates::aed_mapped;

        $rate = $cache['CASHEURCASHUSD'];
        foreach($euro_mapped as $euro)
        {
            foreach( $usd_mapped as $usd ){
                $symbol = $euro.$usd;
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
            }
        }
        $rate = $cache['CASHAEDCASHUSD'];
        foreach($aed_mapped as $aed)
        {
            foreach( $usd_mapped as $usd ){
                $symbol = $aed.$usd;
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
            }
        }
        $rate = $cache['CASHUAHCASHRUB'];
        foreach($uah_mapped as $uah)
        {
            foreach( $rub_mapped as $rub ){
                $symbol = $uah.$rub;
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
            }
        }
        $rate = $cache['USDTCASHRUB'];
        foreach($usd_mapped as $usd)
        {
            foreach( $rub_mapped as $rub ){
                $symbol = $usd.$rub;
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
            }
        }
        $rate = $cache['EURCASHEXMRUB'];
        foreach($euro_mapped as $euro)
        {
            foreach( $rub_mapped as $rub ){
                $symbol = $euro.$rub;
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
            }
        }

        $euro_mapped = ReferenceRates::euro_mapped;
        $rate = $cache['EURCASHUAH'];
        foreach($euro_mapped as $euro)
        {
            foreach( ReferenceRates::uah_mapped as $seed )
            {
                $symbol = $euro.$seed;
                $cache[ $symbol ] = ReferenceRates::add_rate($provider, $symbol, $rate['bid'], $rate['ask']);
            }
        }
        Cache::forever( $provider, $cache);
        echo json_encode(array('status'=>'200'));
        //exit();
    }
}
