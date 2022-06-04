<?php

namespace App;
use Illuminate\Support\Facades\Cache;



class PurePHP
{


    public static function one2one()
    {
        $pairs = \App\Pair::where('provider_id','=','11')->get();
        $cache = array();

        foreach($pairs as $pair)
        {
            $rate = new \App\ReferenceRate;
            $rate->provider = 'one2one';
            $rate->symbol = $pair->symbol;
            $rate->bid = 1;
            $rate->ask = 1;
            $rate->save();

            $cache[ $rate->symbol ] = array( 'bid'=>1, 'ask'=>1);
        }
        Cache::forever( 'one2one', $cache);
        echo json_encode(array('status'=>'200'));
        exit();
    }

    public static function manual()
    {
        $pairs = \App\Pair::where('provider_id','=','0')->get();
        $cache = array();

        foreach($pairs as $pair)
        {
            $rate = new \App\ReferenceRate;
            $rate->provider = 'manual';
            $rate->symbol = $pair->symbol;
            $rate->bid = $pair->bid_coef;
            $rate->ask = $pair->ask_coef;
            $rate->save();

            $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask);
        }
        Cache::forever( 'manual', $cache);
        echo json_encode(array('status'=>'200'));
        exit();
    }


    public static function import_from_old_site()
    {
        $raw = file_get_contents('https://c1k.world/api/rates/pairs');

        $import = json_decode($raw,true);
        $new = array();


        foreach($import['currencies'] as $currency)
        {
            echo $currency['title'];

            if( $currency['title'] == 'USD' )
            {
                $currency['title'] = 'CASH'.$currency['title'];
            }elseif(  $currency['title'] == 'UAH' ){
                $currency['title'] = 'CASH'.$currency['title'];
            }elseif(  $currency['title'] == 'EUR' ){
                $currency['title'] = 'CASH'.$currency['title'];
            }elseif(  $currency['title'] == 'RUB' ){
                $currency['title'] = 'CASH'.$currency['title'];
            }elseif(  $currency['title'] == 'TRY' ){
                continue;
            }

            $cur = \App\Coin::where('code','=',$currency['title'])->get();
            if($cur->count() > 0)
            {
                echo " ".$cur[0]->id.' '.$cur[0]->active."<br>";
                $new['currencies'][ $currency['id'] ] = $cur[0]->id;
                if( !$cur[0]->active )
                {
                    $currency = $cur[0];
                    $currency->active = 1;
                    $currency->save();
                }
            }else{
                echo "<br>";
            }
        }

        foreach($import['categories'] as $categories)
        {
            if( $categories['id'] == 2 )
            {
                $new['categories'][ $categories['id'] ] = 40;
            }elseif( $categories['id'] == 4 ){
                $new['categories'][ $categories['id'] ] = 22;
            }elseif( $categories['id'] == 5 ){
                $new['categories'][ $categories['id'] ] = 186;
            }elseif( $categories['id'] == 6 ){
                $new['categories'][ $categories['id'] ] = 57;
            }
        }

        foreach($import['pairs'] as $pair)
        {
            echo $pair['category_id']."\n";
            if( array_key_exists( $pair['category_id'], $new['categories']) )
            {
                if( array_key_exists( $pair['pair']['base_id'], $new['currencies'] ) && array_key_exists( $pair['pair']['quote_id'], $new['currencies'] ) )
                {

                    $new_pair = new \App\Pair;
                    $new_pair->active = 1;

                    $symbol = $import['currencies'][ $pair['pair']['base_id']-1 ]['title'];
                    if( $symbol == 'USD' )
                    {
                        $symbol = 'CASH'.$symbol;
                    }elseif(  $symbol == 'UAH' ){
                        $symbol = 'CASH'.$symbol;
                    }elseif(  $symbol == 'EUR' ){
                        $symbol = 'CASH'.$symbol;
                    }elseif(  $symbol == 'RUB' ){
                        $symbol = 'CASH'.$symbol;
                    }
                    $symbol_1 = $symbol;


                    $symbol = $import['currencies'][ $pair['pair']['quote_id']-1 ]['title'];
                    if( $symbol == 'USD' )
                    {
                        $symbol = 'CASH'.$symbol;
                    }elseif(  $symbol == 'UAH' ){
                        $symbol = 'CASH'.$symbol;
                    }elseif(  $symbol == 'EUR' ){
                        $symbol = 'CASH'.$symbol;
                    }elseif(  $symbol == 'RUB' ){
                        $symbol = 'CASH'.$symbol;
                    }
                    $symbol_2 = $symbol;

                    $new_pair->symbol = $symbol_1.$symbol_2;

                    $new_pair->provider_id=10;
                    $new_pair->city_id = $new['categories'][$pair['category_id']];
                    $new_pair->cur1_id = $new['currencies'][$pair['pair']['base_id']];
                    $new_pair->cur2_id = $new['currencies'][$pair['pair']['quote_id']];
                    $new_pair->sub_bid = 1;
                    $new_pair->add_ask = 1;
                    $new_pair->min_bid = $pair['min_amount_base'];
                    $new_pair->max_bid = $pair['max_amount_base'];
                    $new_pair->min_ask = $pair['min_amount_quote'];
                    $new_pair->max_ask = $pair['max_amount_quote'];
                    $new_pair->res_bid = 100000;
                    $new_pair->res_ask = 100000;
                    $new_pair->save();

                    $new['pair'][$pair['id']] = $new_pair;
                }
            }


        }

        return "ok";

    }

    static function get_min_max($array)
    {
        $min_2 = min( array_column( $array, 2 ) );
        $min_3 = min( array_column( $array, 3 ) );
        $max_2 = max( array_column( $array, 2 ) );
        $max_3 = max( array_column( $array, 3 ) );
        return array( $min_2, $max_2, $min_3, $max_3 );
    }
    //
    public static function get_bestchange()
    {

        $best = new \BestChange\BestChange();
        $cache = array();

        $exchangers = $best->getExchangers();
        $c1k_Id = 692;
        $bad_pairs = array(
            'EXMUAHP24UAH',
            'USDTEXMUSD',
            'USDTEPMUSD',
            'USDTADVCUSD',
            'CPTSUSDUSDTERC',
            'CPTSUSDADVCUSD',
            'EXMUSDCPTSUSD',
        );

        $currencies = $best->getCurrencies();
        $best_rates = $best->getRatesInstance();

        $coins = array();
        foreach( $currencies as $cur )
        {
            if( $cur['code'] == 'USDTOMNI' )
            {
                $cur['code'] = 'USDT';
            }
            if( $cur['code'] == 'USDTTRC20' )
            {
                $cur['code'] = 'USDTTRC';
            }
            if( $cur['code'] == 'USDTERC20' )
            {
                $cur['code'] = 'USDTERC';
            }

            $coins[ $cur['code'] ] = $cur;
        }
        $coins[ 'EPMUSD' ]=['id'=>175, 'code'=>'EPMUSD', 'name'=>'ePayments USD'];
        //$coins[ 'USDTOMNI' ]=['id'=>163, 'code'=>'USDTOMNI', 'name'=>'USDTOMNI'];

        //$pairs = \App\Pair::with('base_currency','quote_currency')->get();
        $pairs = \App\Pair::with('base_currency','quote_currency')->where('active','=',1)->get();

        foreach($pairs as $pair)
        {

            $cur1 = $coins[$pair->base_currency->code]['id']??null;
            $cur2 = $coins[$pair->quote_currency->code]['id']??null;
            if( !$cur1 || !$cur2 )
            {
                continue;
            }


            $rate = new \App\Rate;
            $rate->bid_position = 0;
            $rate->ask_position = 0;
            $rate->rate_to_pos_bid = 0;
            $rate->rate_to_pos_ask = 0;

            $rate_bid = array_values($best_rates->filter($cur1,$cur2));
            $rate_ask = array_values($best_rates->filter($cur2,$cur1));
            if( in_array($pair->symbol, $bad_pairs) )
            {
                foreach( $rate_bid as $key=>$val)
                {
                    $tmp = 1/$val['rate_give'];
                    $rate_bid[ $key ]['rate_give'] = 1;
                    $rate_bid[ $key ]['rate_receive'] = $tmp;
                }
            }

            if( isset( $rate_bid[ 0 ] ) )
            {
                if( $rate_bid[0]['exchanger_id'] != 692)
                {
                    $rate->bid = $rate_bid[0]['rate_receive'];
                    if( $rate_bid[0]['rate_give'] != 1.0 )
                    {
                        $rate->bid = $rate_bid[0]['rate_give'];
                    }
                }else{
                    if( isset( $rate_bid[ 1 ] ) )
                    {
                        $rate->bid = $rate_bid[1]['rate_receive'];
                        if( $rate_bid[1]['rate_give'] != 1.0 )
                        {
                            $rate->bid = $rate_bid[1]['rate_give'];
                        }
                    }else{
                        $rate->bid = 0;
                    }
                }
            }else{
                $rate->bid = 0;
            }

            $need_pos = $pair->bid_position-1;
            if( isset( $rate_bid[ $need_pos ] ) )
            {
                if( $rate_bid[ $need_pos ]['exchanger_id'] != 692)
                {
                    $rate->rate_to_pos_bid = $rate_bid[ $need_pos ]['rate_receive'];
                }else{
                    if(isset($rate_bid[ $need_pos + 1 ]))
                    {
                        $rate->rate_to_pos_bid = $rate_bid[ $need_pos + 1 ]['rate_receive'];
                    }else{
                        $rate->rate_to_pos_bid =0 ;
                    }
                }
            }
            $rate->bid_position = 0;
            foreach($rate_bid as $pos=>$val )
            {
                if( $val['exchanger_id'] == 692)
                {
                    $rate->bid_position = $pos+1;
                    break;
                }
            }

            if( isset( $rate_ask[ 0 ] ) )
            {
                if( $rate_ask[0]['exchanger_id'] != 692)
                {
                    $rate->ask = $rate_ask[0]['rate_give'];
                    if( $rate_ask[0]['rate_receive'] != 1.0 )
                    {
                        $rate->ask = 1 / $rate_ask[0]['rate_receive'];
                    }
                }else{
                    if( isset( $rate_ask[ 1 ] ))
                    {

                        $rate->ask = $rate_ask[1]['rate_give'];
                        if( $rate_ask[1]['rate_receive'] != 1.0 )
                        {
                            $rate->ask = 1 / $rate_ask[1]['rate_receive'];
                        }
                    }else{
                        $rate->ask = 0;
                    }
                }
            }else{
                $rate->ask = 0;
            }

            $need_pos = $pair->ask_position-1;
            if( isset( $rate_ask[ $need_pos ] ) )
            {
                if( $rate_ask[ $need_pos ]['exchanger_id'] != 692)
                {
                    $rate->rate_to_pos_ask = $rate_ask[ $need_pos ]['rate_give'];
                    if( $rate_ask[ $need_pos ]['rate_receive'] != 1.0 )
                    {
                        $rate->rate_to_pos_ask = 1 / $rate_ask[ $need_pos ]['rate_receive'];
                    }
                }else{
                    if( isset( $rate_ask[ $need_pos + 1 ]) )
                    {
                        $rate->rate_to_pos_ask = $rate_ask[ $need_pos + 1 ]['rate_give'];
                        if( $rate_ask[ $need_pos +1]['rate_receive'] != 1.0 )
                        {
                            $rate->rate_to_pos_ask = 1 / $rate_ask[ $need_pos + 1]['rate_receive'];
                        }
                    }else{
                        $rate->rate_to_pos_ask = 0;
                    }

                }

            }
            $rate->ask_position = 0;
            foreach($rate_ask as $pos=>$val )
            {
                if( $val['exchanger_id'] == 692)
                {
                    $rate->ask_position = $pos+1;
                    break;
                }
            }



            $rate->provider = 'bestchange';
            $rate->symbol = $pair->symbol;


            //Перевернутая пара
            if($rate->symbol == 'BTCETH')
            {
                //$rate->bid = 1/$rate->bid;
                //$rate->ask = 1/$rate->ask;
                //$rate->rate_to_pos_bid = 1/$rate->rate_to_pos_bid;
                //$rate->rate_to_pos_ask = 1/$rate->rate_to_pos_ask;
            }


            $rate->save();

            $cache[ $rate->symbol ] = array( 'bid'=>$rate->bid, 'ask'=>$rate->ask,
            'bid_position'=>$rate->bid_position,
            'ask_position'=>$rate->ask_position,
            'rate_to_pos_bid'=>$rate->rate_to_pos_bid,
            'rate_to_pos_ask'=>$rate->rate_to_pos_ask,
            'avg_bid'=>1,
            'avg_ask'=>1,
        );


    }
    Cache::forever('bestchange_updated', now());
    Cache::forever('bestchange', $cache);
    if(! \App::runningInConsole())
    {
        $cur1 = $coins['EXMUAH']['id'];
        $cur2 = $coins['P24UAH']['id'];

        $rate_bid = $best_rates->filter($cur1,$cur2);
        $rate_ask = $best_rates->filter($cur2,$cur1);

    }
}

}
