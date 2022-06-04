<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Pair;
use App\Coin;
use App\City;
use App\PriceProvider;

use Illuminate\Http\Request;

class PairController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //s
        if(isset($_GET['city_id']) )
        {
            $city_id = $_GET['city_id'];
            $pairs = Pair::where('city_id','=',$city_id)->orderBy('symbol','ASC')->with('base_currency','quote_currency','provider','city','city.ref_city');
        }elseif(isset($_GET['coin_id']) ){
            $coin_id = explode('_',$_GET['coin_id']);
            $city_id = null;
            $pairs = Pair::whereIn('base_currency_id',$coin_id)->orWhereIn('quote_currency_id',$coin_id)->orderBy('symbol','ASC')->with('base_currency','quote_currency','provider','city','city.ref_city');
        }else{
            $city_id = null;
            $coin_id = null;
            $pairs = Pair::orderBy('symbol','ASC')->with('base_currency','quote_currency','provider','city','city.ref_city');
        }
        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            $pairs = $pairs->where('symbol','like','%CNY%');
        }


        $pairs = $pairs->get();


        return view('admin.pairs.index', compact('pairs','city_id','coin_id'));
    }

    public function favorites(Request $request)
    {

        $city_id = $request->city_id;
        $coin_id = $request->coin_id;

        $pairs = Pair::orderBy('symbol','ASC')
        ->with('base_currency','quote_currency','provider','city','city.ref_city');

        if( $request->city_id )
        {
            $pairs = $pairs->where('city_id','=',$city_id);
        }

        $pairs = $pairs->where('favorite','=',true)
        ->get();

        return view('admin.pairs.index', compact('pairs','city_id','coin_id'));
    }


    public function index_by_city($city_id)
    {
        $pairs = Pair::where('city_id','=',$city_id)->orderBy('symbol','ASC')->with('base_currency','quote_currency','provider','city')->get();
        return view('admin.pairs.index', compact('pairs','city_id'));
    }

    public function index_by_currency($city_id)
    {
        $pairs = Pair::where('city_id','=',$city_id)->orderBy('symbol','ASC')->with('base_currency','quote_currency','provider','city')->get();
        return view('admin.pairs.index', compact('pairs','city_id'));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create( )
    {
        //
        if(isset($_GET['city_id']) )
        {
            $city_id = $_GET['city_id'];
        }else{
            $city_id = null;
        }

        $coins = \App\Coin::where('active','=','1');

        if(isset($_GET['coin_id']) )
        {
            $coins_id = explode('_',$_GET['coin_id']);
            $coins = \App\Coin::whereIn('id',$coins_id);
        }

        $coins = $coins->orderBy('name','ASC')->get();


        $exchanges = \App\City::where('active','=','1')->orderBy('name','ASC')->get();
        $price_providers = \App\PriceProvider::get();
        return view('admin.pairs.create',compact('coins','exchanges','price_providers','city_id'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
        if($request->get('base_currency_id') == $request->get('quote_currency_id'))
        {
            session()->flash('warning', "Ошибка: Валюты должны различаться");
            return back();
        }

        $pair = new Pair;
        $pair->fill( $request->except(['lang']) );
        $pair->active = 1;
        $pair->symbol = $pair->base_currency->code.$pair->quote_currency->code;
        $pair->save();

        session()->flash('success', "Валютная пара ".$pair->symbol." успешно создана");

        return redirect()->route('admin.pairs.index',['city_id'=>$pair->city_id]);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Pair  $pair
    * @return \Illuminate\Http\Response
    */
    public function show(Pair $pair)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Pair  $pair
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, Pair $pair)
    {
        //
        //REF TO AJAX
        if( isset($_GET['toggle'] ))
        {
            $pair->active = $request->toggle;
            $pair->save();
            session()->flash('success', "Активность переключена");

            $log = new \App\Logging;
            $log->user_id = \Auth::user()->id;
            $log->event = 'change_pair';
            $log->desc = json_encode( array('pair_id'=>$pair->id, 'enable'=>$_GET['toggle']) ); //TODO detailed
            $log->save();


            $pair->symbol =  $pair->base_currency->code.$pair->quote_currency->code;

            if( $pair->city->ref_city_id )
            {
                $ref_name = $pair->city->ref_city->name;
                $ref_pair = Pair::where('symbol','=',$pair->symbol)->where('city_id','=',$pair->city->ref_city_id)->first();
                if( $ref_pair != null )
                {
                    $pair->provider_id = $ref_pair->provider_id;

                    $pair->bid_coef = $ref_pair->bid_coef - ($pair->city->ref_bid_coef/100 * $ref_pair->bid_coef );
                    $pair->ask_coef = $ref_pair->ask_coef + ($pair->city->ref_ask_coef/100 * $ref_pair->ask_coef);

                    $pair->base_min = $ref_pair->base_min;
                    $pair->base_max = $ref_pair->base_max;
                    $pair->quote_min = $ref_pair->quote_min;
                    $pair->quote_max = $ref_pair->quote_max;

                    $pair->bid_position = $ref_pair->bid_position;
                    $pair->ask_position = $ref_pair->ask_position;

                    //$pair->buy_enable = $ref_pair->buy_enable;
                    //$pair->sell_enable = $ref_pair->buy_enable;

                    $pair->bid_step = $ref_pair->bid_step;
                    $pair->ask_step = $ref_pair->ask_step;
                    $pair->save();
                    $city_name = $ref_pair->city->name;
                    session()->flash('warning', "Пара ".$pair->symbol." успешно синхронизирована с городом ".$city_name);
                    return back();

                }

            }


            return back();
            //return redirect('/admin/pairs');
        }elseif( $request->has('favorite') )
        {
            $pair->favorite = $request->favorite;
            $pair->save();
            session()->flash('success', ( $pair->favorite ? "Добавлена в избранные" : "Удалена из избранных" ) );
            return back();
        }else{
            if( $pair->city->ref_city_id )
            {
                $ref_name = $pair->city->ref_city->name;
                session()->flash('warning', "Пара ".$pair->symbol." привязана к курсу в городе ".$ref_name." и не может быть изменена напрямую");
            }

            $coins = \App\Coin::orderBy('name','ASC')->get();
            $exchanges = \App\City::orderBy('name','ASC')->get();
            $price_providers = \App\PriceProvider::get();
            return view('admin.pairs.edit', compact('pair','coins','exchanges','price_providers'));
        }

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Pair  $pair
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Pair $pair)
    {
        //
        $req = $request->all();
        unset($req['_method']);
        unset($req['_token']);
        unset($req['lang']);
        $diff = array_diff($req, $pair->toArray());

        if( $diff != array())
        {
            $log = new \App\Logging;
            $log->user_id = \Auth::user()->id;
            $log->event = 'change_pair';
            $log->desc = json_encode( array('pair_id'=>$pair->id, 'diff'=>$diff) ); //TODO detailed
            $log->save();
        }

        $pair->symbol =  $pair->base_currency->code.$pair->quote_currency->code;

        if( $pair->city->ref_city_id != null )
        {
            $ref_name = $pair->city->ref_city->name;
            $ref_pair = Pair::where('symbol','=',$pair->symbol)->where('city_id','=',$pair->city->ref_city_id)->first();
            if( $ref_pair != null )
            {
                $pair->provider_id = $ref_pair->provider_id;

                $pair->bid_coef = $ref_pair->bid_coef - ($pair->city->ref_bid_coef/100 * $ref_pair->bid_coef );
                $pair->ask_coef = $ref_pair->ask_coef + ($pair->city->ref_ask_coef/100 * $ref_pair->ask_coef);

                $pair->base_min = $request->base_min;
                $pair->base_max = $request->base_max;
                $pair->quote_min = $request->quote_min;
                $pair->quote_max = $request->quote_max;

                $pair->bid_position = $ref_pair->bid_position;
                $pair->ask_position = $ref_pair->ask_position;

                $pair->buy_enable = $request->buy_enable;
                $pair->sell_enable = $request->buy_enable;

                $pair->bid_step = $ref_pair->bid_step;
                $pair->ask_step = $ref_pair->ask_step;
                $pair->save();

            }

            session()->flash('warning', "Пара ".$pair->symbol." привязана к курсу в городе ".$ref_name." и не может быть изменена напрямую");
            session()->flash('success', "Пара ".$pair->symbol." успешно обновлена");
            return back();
        }else{
            $pair->update($request->except(['lang']));
            Pair::update_paired($pair);
            // $ref_cities = \App\City::where('ref_city_id','=',$pair->city_id )->get();
            // if( $ref_cities != null )
            // {
            //     foreach($ref_cities as $ref_city)
            //     {
            //         $ref_pair = \App\Pair::where('symbol','=',$pair->symbol)->where('city_id','=',$ref_city->id)->first();
            //         if( $ref_pair != null )
            //         {
            //
            //             $ref_pair->provider_id = $pair->provider_id;
            //
            //             $ref_pair->bid_coef = $pair->bid_coef - ($ref_city->ref_bid_coef/100 * $pair->bid_coef );
            //             $ref_pair->ask_coef = $pair->ask_coef + ($ref_city->ref_ask_coef/100 * $pair->ask_coef);
            //
            //             //$ref_pair->base_min = $pair->base_min;
            //             //$ref_pair->base_max = $pair->base_max;
            //             //$ref_pair->quote_min = $pair->quote_min;
            //             //$ref_pair->quote_max = $pair->quote_max;
            //
            //             $ref_pair->bid_position = $pair->bid_position;
            //             $ref_pair->ask_position = $pair->ask_position;
            //
            //             //$ref_pair->buy_enable = $pair->buy_enable;
            //             //$ref_pair->sell_enable = $pair->buy_enable;
            //
            //             $ref_pair->bid_step = $pair->bid_step;
            //             $ref_pair->ask_step = $pair->ask_step;
            //             $ref_pair->save();
            //         }
            //     }
            // }
        }

        if( $pair->id == 16767 )
        {
            $ref_ada_pairs = Pair::whereIn( 'id', [ 17420, 17421, 17425, 17426, 17423, 17424 ] )->get();

            foreach( $ref_ada_pairs as $ref_ada_pair )
            {
                $ref_ada_pair->bid_coef = $pair->bid_coef - 0.002;
                $ref_ada_pair->ask_coef = $pair->ask_coef + 0.002;
                $ref_ada_pair->save();
            }
        }

        session()->flash('success', "Пара ".$pair->symbol." успешно обновлена");

        return back();

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Pair  $pair
    * @return \Illuminate\Http\Response
    */
    public function destroy(Pair $pair)
    {

        if($pair->active)
        {
            session()->flash('warning', "Ошибка удаления пары: пара активна");
            return back();
        }
        //
        if(\App\Exchange::where('category_pair_id','=',$pair->id)->count() > 0)
        {
            session()->flash('warning', "Ошибка удаления пары: по текущему направлению есть обмены, пару можно только отключить");
            return back();
        }

        try {
            $pair->delete();
        } catch (\Exception $e) {
            session()->flash('warning', "Ошибка при удалении пары");

            return back();
        }

        session()->flash('success', "Валютная пара удалена");

        return back();

    }

    public static function exists_pairs()
    {
        $exists_pairs_raw = \DB::select("SELECT DISTINCT `symbol` FROM `pairs`");
        $exists_pairs = array();
        foreach($exists_pairs_raw as $epr)
        {
            $exists_pairs[] = $epr->symbol;
        }

        return $exists_pairs;
    }

    //TODO Вынести в другой класс
    public static function api_to_front()
    {
        header("Content-Type: text/json");

        $result = array('pairs'=>array(),'categories'=>array(),'currencies'=>array());
        $coins = Coin::get();
        $exchanges = City::orderBy('name')->get();
        $price_providers = PriceProvider::get();

        $pairs_active = Pair::where('active','=',1)->orderBy('id','DESC')->get();

        foreach ($pairs_active as $pair) {

            $rate = \App\Http\Controllers\Rates\CurrentRate::get_rate($pair->id);

            $result['pairs'][]=array(
                "id"=>$pair->id,
                "category_id"=>$pair->city_id,
                "pair"=> array(
                    "base_id"=>$pair->cur1_id,
                    "quote_id"=>$pair->cur2_id,
                    "buy_fee_amount"=>0,
                    "sell_fee_amount"=>0
                ),
                //"sell_price"=>$pair->add_ask * $rate['ask'],
                //"buy_price"=>$pair->sub_bid * $rate['bid'],
                "sell_price"=>$rate['rate_to_pos_ask'],
                "buy_price"=>$rate['rate_to_pos_bid'],
                "is_top"=>true,
                "order"=>100,
                "min_amount_base"=>floatval($pair->min_bid),
                "min_amount_quote"=>floatval($pair->min_ask),
                "max_amount_quote"=>floatval($pair->max_ask),
                "max_amount_base"=>floatval($pair->max_bid)
            );
            // code...
        }


        $exchanges_active = \DB::select("SELECT DISTINCT `city_id` FROM `pairs` WHERE `active` = 1  ORDER BY `id` DESC");
        foreach ($exchanges_active as $key => $value) {
            //if($key != 0)
            //{
            $result['categories'][] = array(
                "id"=>$value->city_id,
                "parent_id"=>null,
                "title"=>$exchanges->find($value->city_id)->name,
                "order"=>($key+1)
            );
            //}
        }

        $coins_active = \DB::select("
        SELECT DISTINCT `cur1_id` as `cur_id` FROM `pairs`  WHERE `active`=1 UNION
        SELECT DISTINCT `cur2_id` FROM `pairs`  WHERE `active`=1
        ");
        foreach ($coins_active as $key => $value)
        {
            $result['currencies'][] = array(
                "id"=>$value->cur_id,
                "title"=>$coins->find($value->cur_id)->code,
                "decimal_places"=>8,
                "allias"=>$coins->find($value->cur_id)->name
            );
        }
        echo json_encode($result);
        exit();

    }

    public static function api_to_front_test()
    {
        header("Content-Type: text/json");

        $result = array('pairs'=>array(),'categories'=>array(),'currencies'=>array());
        $pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','provider','city','reserv')->get();

        foreach ($pairs as $pair) {

            $rate = \App\Http\Controllers\Rates\CurrentRate::get_rate($pair->id);

            $result['pairs'][]=array(
                "id"=>$pair->id,
                "category_id"=>$pair->city_id,
                "pair"=> array(
                    "base_id"=>$pair->base_currency_id,
                    "quote_id"=>$pair->quote_currency_id,
                    "buy_fee_amount"=>0,
                    "sell_fee_amount"=>0
                ),
                "sell_price"=>$rate['rate_to_pos_ask'],
                "buy_price"=>$rate['rate_to_pos_bid'],
                "is_top"=>true,
                "order"=>100,
                "min_amount_base"=>floatval($pair->base_min),
                "min_amount_quote"=>floatval($pair->quote_min),
                "max_amount_quote"=>floatval($pair->quote_max),
                "max_amount_base"=>floatval($pair->base_max)
            );
            // code...
        }


        $exchanges_active = \DB::select("SELECT DISTINCT `city_id` FROM `pairs` WHERE `active` = 1  ORDER BY `id` DESC");
        foreach ($exchanges_active as $key => $value) {
            //if($key != 0)
            //{
            $result['categories'][] = array(
                "id"=>$value->city_id,
                "parent_id"=>null,
                "title"=>$exchanges->find($value->city_id)->name,
                "order"=>($key+1)
            );
            //}
        }

        $coins_active = \DB::select("
        SELECT DISTINCT `cur1_id` as `cur_id` FROM `pairs`  WHERE `active`=1 UNION
        SELECT DISTINCT `cur2_id` FROM `pairs`  WHERE `active`=1
        ");
        foreach ($coins_active as $key => $value)
        {
            $result['currencies'][] = array(
                "id"=>$value->cur_id,
                "title"=>$coins->find($value->cur_id)->code,
                "decimal_places"=>8,
                "allias"=>$coins->find($value->cur_id)->name
            );
        }
        echo json_encode($result);
        exit();

    }


}
