<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Rates\CurrentRate;
use App\Pair;

use Illuminate\Support\Facades\Cache;
/**
* @group General
*/
class RatesController
{

    public function rate( $pair, $final_rate, $side, $reserv )
    {
        $data = [];
        $data['category_pair_id'] = $pair->id;
        $data['side'] = $side;

        $data['send'] = ( $side == 'buy' ) ? 1 : number_format($final_rate['ask'], $pair->quote_currency->round,'.','')*1;
        $data['get'] = ( $side == 'buy' ) ? number_format($final_rate['bid'], $pair->quote_currency->round,'.','')*1 : 1;

        $data['round'] = ( $side == 'buy' ) ? $pair->quote_currency->round : $pair->quote_currency->round;
        $data['round_send'] = ( $side == 'buy' ) ? $pair->base_currency->round : $pair->quote_currency->round;
        $data['round_get'] = ( $side == 'buy' ) ? $pair->quote_currency->round : $pair->base_currency->round;

        $data['min_send'] = ( $side == 'buy' ) ? floatval($pair->base_min) : floatval($pair->quote_min);
        $data['max_send'] = ( $side == 'buy' ) ? floatval($pair->base_max) : floatval($pair->quote_max);

        $data['reserve'] = ( $side == 'buy' ) ? $reserv['quote'] : $reserv['base'];

        $data['min_get'] = ($data['send'] != 0) ? $data['min_send'] * $data['get'] / $data['send'] : 0;
        $data['max_get'] = ($data['send'] != 0) ? $data['max_send'] * $data['get'] / $data['send'] : 0;

        $data['reserve'] = number_format($data['reserve'], $data['round_get'],'.','')*1;

        $data['max_get'] = min($data['max_get'], $data['reserve']);

        $data['send'] = number_format($data['send'], $data['round_send'],'.','')*1;
        $data['min_send'] = number_format($data['min_send'], $data['round_send'],'.','')*1;
        $data['max_send'] = number_format($data['max_send'], $data['round_send'],'.','')*1;

        $data['get'] = number_format($data['get'], $data['round_get'],'.','')*1;
        $data['min_get'] = number_format($data['min_get'], $data['round_get'],'.','')*1;
        $data['max_get'] = number_format($data['max_get'], $data['round_get'],'.','')*1;



        $cash_in = ( $side == 'buy' ) ? $pair->base_currency->code : $pair->quote_currency->code;
        $cash_out = ( $side == 'buy' ) ? $pair->quote_currency->code : $pair->base_currency->code;

        $data['cash_in'] = (strpos($cash_in, 'CASH') !== false) ? true : false;
        $data['cash_out'] = (strpos($cash_out, 'CASH') !== false) ? true : false;

        $data['currency_type_in'] = ( $side == 'buy' ) ? $pair->base_currency->adress_type : $pair->quote_currency->adress_type;
        $data['currency_type_out'] = ( $side == 'buy' ) ? $pair->quote_currency->adress_type : $pair->base_currency->adress_type;

        return $data;
    }

    /**
    * Rates v2
    *
    * Текущие курсы v2
    *
    *
    */
    public function index()
    {


        $site_mode = Cache::get( 'site_mode', null );

        if($site_mode != null)
        {
            if($site_mode == 'off')
            {
                return response()->json([]);
            }
        }



        $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        $userIp = \App\UserIp::where('user_ip','=',$user_ip)->where('created_at','>',now()->subMinutes(10) )->first();
        if( $userIp == null )
        {
            \App\UserIp::create(['user_ip'=>$user_ip]);
        }

        $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        $user_geo = strtolower(geoip_country_code_by_name($user_ip)??'UA');

        $result = array();

        //$pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','base_reserv','quote_reserv','provider', 'city', 'city.country')->orderBy('city_id','ASC')->get();

        $pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','base_reserv','quote_reserv','provider', 'city', 'city.country');
        $cash_mode = Cache::get( 'cash_mode', null );
        if($cash_mode != null)
        {
            if($cash_mode == 'off')
            {
                $pairs = $pairs->whereNotIn( 'base_currency_id', [178,179,180,181,182,183,191] )->whereNotIn( 'quote_currency_id', [178,179,180,181,182,183,191] );
            }
        }
        $pairs = $pairs->get();


        $pairs = $pairs->sort(
            function ($a, $b) use ( $user_geo ){

                if( $a->city->country->code == $b->city->country->code)
                {
                    if( ($a->city->code == 'KIEV') || ($a->city->code == 'MSK' ) )
                    {
                        return -1;
                    }elseif(  ($b->city->code == 'KIEV') || ($b->city->code == 'MSK' ) ){
                        return 1;
                    }
                    return 0;
                }

                if( $a->city->country->code == $user_geo )
                {
                    return -1;
                }elseif( $b->city->country->code == $user_geo ){
                    return 1;
                }
                return 0;
            });

            //[
            //fn ($a, $b) => $a->city->country->code <=> 'UA'
            //]);

            $tmp_coins = array();
            $tmp_countries = array();

            foreach ($pairs as $pair)
            {
                $best_rate = CurrentRate::get_rate_best($pair);
                if($best_rate == null)
                {
                    continue;
                }
                $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
                if($limit_rate == null)
                {
                    continue;
                }
                $final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
                if($final_rate == null)
                {
                    continue;
                }
                if( $pair->id == 17150)
                {
                    logger( json_encode($best_rate) );
                    logger( json_encode($limit_rate) );
                    logger( json_encode($final_rate) );
                }


                if($pair->quote_reserv != null ){
                    $reserv_quote = number_format($pair->quote_reserv->amount, $pair->quote_currency->round,'.','');
                }else{
                    $reserv_quote = 0;
                }
                if($reserv_quote < 0)
                {
                    $reserv_quote = 0;
                }

                if($pair->base_reserv != null ){
                    $reserv_base = number_format($pair->base_reserv->amount, $pair->base_currency->round,'.','');
                }else{
                    $reserv_base = 0;
                }
                if($reserv_base < 0)
                {
                    $reserv_base = 0;
                }

                $reserv = ['base'=>$reserv_base*1, 'quote'=>$reserv_quote*1 ];

                $cashless = $pair->city->name;

                $tmp_coins[ $pair->base_currency->code] = [ 'name'=>$pair->base_currency->name, 'type'=>$pair->base_currency->adress_type];
                $tmp_coins[ $pair->quote_currency->code] = [ 'name'=>$pair->quote_currency->name, 'type'=>$pair->quote_currency->adress_type];

                if( $cashless == 'Cashless' )
                {
                    if( $pair->buy_enable )
                    {
                        $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$cashless]['code']='none';
                        $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$cashless]['pairs'][$cashless]=$this->rate($pair, $final_rate, 'buy', $reserv);
                    }
                    if( $pair->sell_enable )
                    {
                        $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$cashless]['code']='none';
                        $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$cashless]['pairs'][$cashless] = $this->rate($pair, $final_rate, 'sell', $reserv);
                    }
                }else{
                    if( $pair->buy_enable )
                    {
                        $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$pair->city->country->name]['code']=$pair->city->country->code;
                        $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$pair->city->country->name]['pairs'][$cashless]=$this->rate($pair, $final_rate, 'buy', $reserv);
                    }
                    if( $pair->sell_enable )
                    {
                        $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$pair->city->country->name]['code']=$pair->city->country->code;
                        $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$pair->city->country->name]['pairs'][$cashless]=$this->rate($pair, $final_rate, 'sell', $reserv);
                    }
                }

            }
            //ksort($tmp_coins);

            uasort($tmp_coins, function( $a, $b){

                if ($a == $b) {
                    return 0;
                }

                if( ( $a == 'cash' ) && ( $b == 'card' )  )
                {
                    return 1;
                }
                if( ( $a == 'card' ) && ( $b == 'wallet') )
                {
                    return 1;
                }
                return -1;
            });


            $result['Coins'] = [];
            foreach( $tmp_coins as $key=>$tmp_coin )
            {
                $result['Coins'][$key] = $tmp_coin['name'];
            }

            //$result['geo'] = $user_geo;


            return response()->json($result);

        }
        /**
        * Export
        *
        * Export to json
        *
        *
        */
        public function export( Request $request )
        {
            if( $request->token != 'Aa123321@$' )
            {
                return [];
            }

            $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

            $userIp = \App\UserIp::where('user_ip','=',$user_ip)->where('created_at','>',now()->subMinutes(10) )->first();
            if( $userIp == null )
            {
                \App\UserIp::create(['user_ip'=>$user_ip]);
            }

            $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $user_geo = strtolower(geoip_country_code_by_name($user_ip)??'UA');

            $result = array();

            $pairs = Pair::whereIn('city_id',['186','22'])->where('active','=',1)->with('base_currency','quote_currency','base_reserv','quote_reserv','provider', 'city', 'city.country')->get();


            foreach ($pairs as $pair)
            {
                $best_rate = CurrentRate::get_rate_best($pair);
                if($best_rate == null)
                {
                    continue;
                }
                $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
                if($limit_rate == null)
                {
                    continue;
                }
                $final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
                if($final_rate == null)
                {
                    continue;
                }

                if($pair->quote_reserv != null ){
                    $reserv_quote = number_format($pair->quote_reserv->amount, $pair->quote_currency->round,'.','');
                }else{
                    $reserv_quote = 0;
                }
                if($reserv_quote < 0)
                {
                    $reserv_quote = 0;
                }

                if($pair->base_reserv != null ){
                    $reserv_base = number_format($pair->base_reserv->amount, $pair->base_currency->round,'.','');
                }else{
                    $reserv_base = 0;
                }
                if($reserv_base < 0)
                {
                    $reserv_base = 0;
                }

                $reserv = ['base'=>$reserv_base*1, 'quote'=>$reserv_quote*1 ];

                $result[] = [
                    'from' => $pair->base_currency->code,
                    'to' =>  $pair->quote_currency->code,
                    'buy' => $pair->buy_enable,
                    'sell' => $pair->sell_enable,
                    'rate'=>$final_rate,
                    'reserv'=>$reserv,
                ];

            }

            return response()->json($result);

        }
        /**
        * Rates
        *
        * Текущие курсы
        *
        *
        */
        public static function index_old()
        {

            $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

            $userIp = \App\UserIp::where('user_ip','=',$user_ip)->where('created_at','>',now()->subMinutes(10) )->first();
            if( $userIp == null )
            {
                \App\UserIp::create(['user_ip'=>$user_ip]);
            }

            $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $user_geo = strtolower(geoip_country_code_by_name($user_ip)??'UA');

            $result = array();

            //$pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','base_reserv','quote_reserv','provider', 'city', 'city.country')->orderBy('city_id','ASC')->get();
            $pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','base_reserv','quote_reserv','provider', 'city', 'city.country')->get();

            $pairs = $pairs->sort(
                function ($a, $b) use ( $user_geo ){

                    if( $a->city->country->code == $b->city->country->code)
                    {
                        if( ($a->city->code == 'KIEV') || ($a->city->code == 'MSK' ) )
                        {
                            return -1;
                        }elseif(  ($b->city->code == 'KIEV') || ($b->city->code == 'MSK' ) ){
                            return 1;
                        }
                        return 0;
                    }

                    if( $a->city->country->code == $user_geo )
                    {
                        return -1;
                    }elseif( $b->city->country->code == $user_geo ){
                        return 1;
                    }
                    return 0;
                });

                //[
                //fn ($a, $b) => $a->city->country->code <=> 'UA'
                //]);

                $tmp_coins = array();
                $tmp_countries = array();

                foreach ($pairs as $pair)
                {
                    $best_rate = CurrentRate::get_rate_best($pair);
                    if($best_rate == null)
                    {
                        continue;
                    }
                    $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
                    if($limit_rate == null)
                    {
                        continue;
                    }
                    $final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
                    if($final_rate == null)
                    {
                        continue;
                    }
                    if( $pair->id == 17150)
                    {
                        logger( json_encode($best_rate) );
                        logger( json_encode($limit_rate) );
                        logger( json_encode($final_rate) );
                    }


                    if($pair->quote_reserv != null ){
                        $reserv_quote = number_format($pair->quote_reserv->amount, $pair->quote_currency->round,'.','');
                    }else{
                        $reserv_quote = 0;
                    }
                    if($reserv_quote < 0)
                    {
                        $reserv_quote = 0;
                    }

                    if($pair->base_reserv != null ){
                        $reserv_base = number_format($pair->base_reserv->amount, $pair->base_currency->round,'.','');
                    }else{
                        $reserv_base = 0;
                    }
                    if($reserv_base < 0)
                    {
                        $reserv_base = 0;
                    }


                    $cashless = $pair->city->name;

                    $tmp_coins[ $pair->base_currency->code] = [ 'name'=>$pair->base_currency->name, 'type'=>$pair->base_currency->adress_type];
                    $tmp_coins[ $pair->quote_currency->code] = [ 'name'=>$pair->quote_currency->name, 'type'=>$pair->quote_currency->adress_type];

                    if( $cashless == 'Cashless' )
                    {
                        if( $pair->buy_enable )
                        {
                            $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$cashless]['code']='none';
                            $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$cashless]['pairs'][$cashless]=array(
                                'get'=>number_format($final_rate['bid'], $pair->quote_currency->round,'.','')*1,
                                'send'=>1,
                                'category_pair_id'=>$pair->id,
                                'side'=>'buy',
                                'round'=>$pair->quote_currency->round,

                                "min_send"=>floatval($pair->base_min),
                                "max_send"=>floatval($pair->base_max),

                                "min_get"=>floatval($pair->base_min) * number_format($final_rate['bid'], $pair->quote_currency->round,'.','')*1,

                                "max_get"=>min(floatval($pair->quote_max), $reserv_quote*1),
                                "reserve"=>$reserv_quote*1,
                                "cash_in"=>(strpos($pair->base_currency->code, 'CASH') !== false) ? true : false,
                                "cash_out"=>(strpos($pair->quote_currency->code, 'CASH') !== false) ? true : false,

                                "currency_type_in"=>$pair->base_currency->adress_type,
                                "currency_type_out"=>$pair->quote_currency->adress_type,
                            );
                        }
                        if( $pair->sell_enable )
                        {
                            $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$cashless]['code']='none';
                            $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$cashless]['pairs'][$cashless]=array(
                                'send'=>number_format($final_rate['ask'], $pair->quote_currency->round,'.','')*1,
                                'get'=>1,
                                'side'=>'sell',
                                'category_pair_id'=>$pair->id,
                                'round'=>$pair->quote_currency->round,

                                "min_send"=>floatval($pair->quote_min),
                                "max_send"=>floatval($pair->quote_max),

                                "min_get"=>0,
                                "max_get"=>min( floatval($pair->base_max), $reserv_base*1),

                                "reserve"=>$reserv_base*1,
                                "cash_out"=>(strpos($pair->base_currency->code, 'CASH') !== false) ? true : false,
                                "cash_in"=>(strpos($pair->quote_currency->code, 'CASH') !== false) ? true : false,

                                "currency_type_out"=>$pair->base_currency->adress_type,
                                "currency_type_in"=>$pair->quote_currency->adress_type,

                                // "from"=>$pair->quote_currency->code,
                                // "to"=>$pair->base_currency->code,
                                // "type"=>$pair->quote_currency->adress_type.'-to-'.$pair->quote_currency->adress_type,
                            );
                        }
                    }else{
                        if( $pair->buy_enable )
                        {
                            $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$pair->city->country->name]['code']=$pair->city->country->code;
                            $result['rates'][ $pair->base_currency->code ][ $pair->quote_currency->code ][$pair->city->country->name]['pairs'][$cashless]=array(
                                'get'=>number_format($final_rate['bid'], $pair->quote_currency->round,'.','')*1,
                                'send'=>1,
                                'side'=>'buy',
                                'category_pair_id'=>$pair->id,
                                'round'=>$pair->quote_currency->round,

                                "min_send"=>floatval($pair->base_min),
                                "max_send"=>floatval($pair->base_max),

                                "min_get"=>0,
                                "max_get"=>min(floatval($pair->quote_max), $reserv_quote*1),

                                "reserve"=>$reserv_quote*1,
                                "cash_in"=>(strpos($pair->base_currency->code, 'CASH') !== false) ? true : false,
                                "cash_out"=>(strpos($pair->quote_currency->code, 'CASH') !== false) ? true : false,

                                "currency_type_in"=>$pair->base_currency->adress_type,
                                "currency_type_out"=>$pair->quote_currency->adress_type,
                                // "from"=>$pair->base_currency->code,
                                // "to"=>$pair->quote_currency->code,
                                // "type"=>$pair->base_currency->adress_type.'-to-'.$pair->quote_currency->adress_type,
                            );
                        }
                        if( $pair->sell_enable )
                        {
                            $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$pair->city->country->name]['code']=$pair->city->country->code;
                            $result['rates'][ $pair->quote_currency->code ][ $pair->base_currency->code ][$pair->city->country->name]['pairs'][$cashless]=array(
                                'send'=>number_format($final_rate['ask'], $pair->quote_currency->round,'.','')*1,
                                'get'=>1,
                                'side'=>'sell',
                                'category_pair_id'=>$pair->id,
                                'round'=>$pair->quote_currency->round,

                                "min_send"=>floatval($pair->quote_min),
                                "max_send"=>floatval($pair->quote_max),

                                "min_get"=>0,
                                "max_get"=>min( floatval($pair->base_max), $reserv_base*1),

                                "reserve"=>$reserv_base*1,

                                "cash_out"=>(strpos($pair->base_currency->code, 'CASH') !== false) ? true : false,
                                "cash_in"=>(strpos($pair->quote_currency->code, 'CASH') !== false) ? true : false,

                                "currency_type_out"=>$pair->base_currency->adress_type,
                                "currency_type_in"=>$pair->quote_currency->adress_type,
                                // "from"=>$pair->quote_currency->code,
                                // "to"=>$pair->base_currency->code,
                                // "type"=>$pair->quote_currency->adress_type.'-to-'.$pair->quote_currency->adress_type,
                            );
                        }
                    }

                }
                //ksort($tmp_coins);

                uasort($tmp_coins, function( $a, $b){

                    if ($a == $b) {
                        return 0;
                    }

                    if( ( $a == 'cash' ) && ( $b == 'card' )  )
                    {
                        return 1;
                    }
                    if( ( $a == 'card' ) && ( $b == 'wallet') )
                    {
                        return 1;
                    }
                    return -1;
                });


                $result['Coins'] = [];
                foreach( $tmp_coins as $key=>$tmp_coin )
                {
                    $result['Coins'][$key] = $tmp_coin['name'];
                }

                //$result['geo'] = $user_geo;


                return response()->json($result);

            }

        }
