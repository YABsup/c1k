<?php

namespace App\Http\Controllers;

use App\Pair;
use App\City;
use App\Coin;
use App\Exchange;
use App\PriceProvider;
use App\Reserv;
use App\UserIp;
use App\User;
use App\Http\Controllers\Rates\CurrentRate;
use App\Mail\NewOrder;
use App\Mail\NewUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;



class ExchangeController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index( Request $request )
    {
        //
        //        $orders = Exchange::where('status_id','!=', '4')->orderBy('id', 'ASC')->orderBy('status_id', 'ASC')->orderBy('updated_at', 'DESC')->with('pair','user','user.referer','pair.city','status','pair.base_currency','pair.quote_currency')->get();
        //$orders = Exchange::whereConfirm(true)->where('status_id','!=', '4')->orderBy('status_id', 'ASC')->orderBy('id', 'DESC')->with('pair','user','user.referer','pair.city','status','pair.base_currency','pair.quote_currency')->get();

        //where('status_id','!=', '4')->



        $orders = Exchange::orderBy('status_id', 'ASC')->orderBy('id', 'DESC')->with('pair','user','user.referer','pair.city','status','pair.base_currency','pair.quote_currency');
        if( Auth::user()->email == 'inkovalexey@gmail.com')
        {

            $cny_pairs = Pair::where('base_currency_id', 'like', "%CNY%")->get()->pluck('id')->toArray();

            $orders = $orders->whereIn('category_pair_id', $cny_pairs);
            $counts = [];
            $counts[1] = Exchange::whereStatusId(1)->whereIn('category_pair_id', $cny_pairs)->count();
            $counts[2] = Exchange::whereStatusId(2)->whereIn('category_pair_id', $cny_pairs)->count();
            $counts[3] = Exchange::whereStatusId(3)->whereIn('category_pair_id', $cny_pairs)->count();
            $counts[4] = Exchange::whereStatusId(4)->whereIn('category_pair_id', $cny_pairs)->count();
        }else{
            $counts = [];
            $counts[1] = Exchange::whereStatusId(1)->count();
            $counts[2] = Exchange::whereStatusId(2)->count();
            $counts[3] = Exchange::whereStatusId(3)->count();
            $counts[4] = Exchange::whereStatusId(4)->count();
        }

        $filter = $request->filter;
        if( !in_array($filter, [1, 2, 3, 4 ]) )
        {
                $filter = 1;
        }

        $orders = $orders->where('status_id','=',$filter)->get();

        $exchanges = City::All();
        $coins = Coin::All();
        $price_providers = PriceProvider::All();
        //dd($orders);
        return view('admin.orders.index', compact('orders','exchanges','coins','price_providers', 'filter', 'counts'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
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



    }
    public function exchange(Request $request)
    {

        $coins = Coin::All();
        $pair = Pair::where('id','=',$request->pair_id)->with('base_currency','quote_currency')->firstOrFail();

        $best_rate = CurrentRate::get_rate_best($pair);
        if($best_rate == null)
        {
            //continue;
        }
        $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
        if($limit_rate == null)
        {
            //continue;
        }
        $current_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
        if($current_rate == null)
        {
            //continue;
        }
        //\App\PurePHP::dd( array( $current_rate, $pair) );
        if($request->side == 'buy')
        {
            $params['rate'] = number_format($current_rate['bid'],$pair->quote_currency->round,'.','');
            $params['cur1'] = $pair->base_currency->name;
            $params['cur2'] = $pair->quote_currency->name;
            $params['rate2'] = number_format($current_rate['bid'],$pair->quote_currency->round,'.','');
        }else{
            $params['rate'] = number_format($current_rate['ask'],$pair->quote_currency->round,'.','');
            $params['cur1'] = $pair->quote_currency->name;
            $params['cur2'] = $pair->base_currency->name;
            $params['rate2'] = number_format($current_rate['ask'],$pair->quote_currency->round,'.','');
        }


        $params['amount_give'] = $request->amount_give;

        return view('exchange.exchange', compact('request','coins','params','pair'));
    }
    public function cashless_exchange(Request $request)
    {

        $coins = Coin::All();
        $pair = Pair::with('base_currency','quote_currency')->where('id','=',$request->pair_id)->firstOrFail();
        $best_rate = CurrentRate::get_rate_best($pair);
        if($best_rate == null)
        {
            //continue;
        }
        $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
        if($limit_rate == null)
        {
            //continue;
        }
        $current_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
        if($current_rate == null)
        {
            //continue;
        }

        if($request->side == 'buy')
        {
            $params['rate'] = number_format($current_rate['bid'],$pair->quote_currency->round,'.','');
            $params['cur1'] = $pair->base_currency->name;
            $params['cur2'] = $pair->quote_currency->name;
            $params['rate2'] = number_format($current_rate['bid'],$pair->quote_currency->round,'.','');
        }else{
            $params['rate'] = number_format($current_rate['ask'],$pair->quote_currency->round,'.','');
            $params['cur1'] = $pair->quote_currency->name;
            $params['cur2'] = $pair->base_currency->name;
            $params['rate2'] = number_format($current_rate['ask'],$pair->quote_currency->round,'.','');
        }


        $params['amount_give'] = $request->amount_give;

        return view('exchange.cashless_exchange', compact('request','coins','params','pair'));

    }
    /**
    * Display the specified resource.
    *
    * @param  \App\Exchange  $exchange
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request, String $uuid)
    {
        //
        $exchange = Exchange::where('uuid','=',$uuid)->firstOrFail();
        //\App\PurePHP::dd($exchange);
        $user = $exchange->user;


        if( $request->has('confirm') && ( $exchange->confirm == false ) )
        {
            $hash = hash('sha256', 'confirm'.$uuid.$exchange->id.$exchange->user_ip);
            if( $request->confirm == $hash )
            {
                $exchange->confirm = true;
                $exchange->save();
                try
                {
                Mail::queue(new NewOrder(config('mail.from.address'), $user, $exchange));
                } catch (Exception $e) {
            	    Log::error( $e->getMessage() );
                }
            }
        }




        return view('exchange.exchange_advcash', compact('exchange','user'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Exchange  $exchange
    * @return \Illuminate\Http\Response
    */
    public function edit(Exchange $exchange)
    {
        //
        if(($exchange->status_id != 3) && $exchange->status_id != 4 )
        {
            $exchange->status_id = $exchange->status_id+1;

            if($exchange->status_id == 3)
            {
                $amount_get = $exchange->amount_get;
                $amount_take =$exchange->amount_take;
                $reserv_base = Reserv::firstOrNew( array( 'coin_id'=>$exchange->pair->base_currency->id) );
                $reserv_quote = Reserv::firstOrNew( array( 'coin_id'=>$exchange->pair->quote_currency->id) );

                if($exchange->side == 'buy')
                {
                    $reserv_base->amount = $reserv_base->amount + $amount_take;
                    $reserv_quote->amount = $reserv_quote->amount - $amount_get;
                }else{
                    $reserv_base->amount = $reserv_base->amount - $amount_get;
                    $reserv_quote->amount = $reserv_quote->amount + $amount_take;
                }
                $reserv_base->save();
                $reserv_quote->save();
            }


            if(isset($_GET['profit']))
            {
                $exchange->profit = floatval($_GET['profit']);

                if( $exchange->user->referer_id == 0)
                {
                    if( $exchange->user->partner == 0 ) //Рядовой партнер который сам пришель
                    {
                        $exchange->ref_profit = $_GET['profit'] * 0.1;
                        $cur_profit_user = $exchange->user->balance;
                        $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.1; // 10% кешбек
                        $exchange->user->save();
                    }
                }else{
                    if($exchange->user->referer->partner == 0) // Реферал рядового партнера
                    {
                        $exchange->ref_profit = $_GET['profit'] * 0.05;
                        $cur_profit_referer = $exchange->user->referer->balance;
                        $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.05; // 5% "Рядовому партнеру"
                        $exchange->user->referer->save();

                        $cur_profit_user = $exchange->user->balance;
                        $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.05;  // 5% кешбек рефералу "Рядового партнера"
                        $exchange->user->save();
                    }elseif($exchange->user->referer->partner == 1){ //Реферал мониторинга
                        $exchange->ref_profit = $_GET['profit'] * 0.3;
                        $cur_profit_referer = $exchange->user->referer->balance;
                        $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.3; // 30% "Мониторингу"
                        $exchange->user->referer->save();

                        $cur_profit_user = $exchange->user->balance;
                        $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.1;  // 10% кешбек "Рядовому партнеру"
                        $exchange->user->save();
                    }elseif($exchange->user->referer->partner == 2){ //Лидер мнений
                        $exchange->ref_profit = $_GET['profit'] * 0.4;
                        $cur_profit_referer = $exchange->user->referer->balance;
                        $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.4; // 40% "Лидеру мнений"
                        $exchange->user->referer->save();

                        $cur_profit_user = $exchange->user->balance;
                        $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.1;  // 10% кешбек "Рядовому партнеру"
                        $exchange->user->save();
                        if( $exchange->user->referer->referer != null )
                        {
                            $cur_profit_referer = $exchange->user->referer->referer->balance;
                            $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.05; // 5% "Менеджеру Лидеров мнений"
                            $exchange->user->referer->referer->save();
                        }
                    }
                }
            }

            $exchange->save();
        }

        session()->flash('success', "Ok");
        return redirect('admin/orders');
    }


    public function application(Request $request)
    {
        //
        $request->validate([
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
        $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        $userIp = UserIp::where('user_ip','=',$user_ip)->first();
        if( $userIp == null )
        {
            $userIp = UserIp::create(['user_ip'=>$user_ip]);
        }else{
            if( $userIp->blocked )
            {
                session()->flash('warning', "Flood detection");
                return redirect('/site_offline');
            }
        }

        $exchange_list = Exchange::where('user_ip', '=', $user_ip)->orderBy('id','DESC')->first();
        if( $exchange_list != null )
        {
            if( ($exchange_list->created_at->timestamp + 60) > time() )
            {
                session()->flash('warning', "Flood detection");
                return redirect('/');
            }
        }



        $exchange = new Exchange;
        $exchange->fill($request->all());

        $exchange->user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        if( !Auth::check() )
        {
            //$exchange->user_id = $user->id;
            $user = User::where('email','=',$request->email)->get();
            if( $user->count() == 0 )
            {
                $user = new User;
                $user->fill($request->all());
                $user->name = $request->first_name;
                $user->role = 'user';
                $password = str_random(8);
                $user->password = Hash::make($password);
                //$user->ref_code = hash('sha256',uniqid('',true));
                $user->ref_code = strtolower(substr( str_replace(array('+','/','='),'', strrev(base64_encode( uniqid('',true) ))),0,20));
                $user->referer_id = session('referer_id', '0');
                $user->save();
		try{
            	    Mail::queue(new NewUser($user->email, $user, $password));
        	} catch (Exception $e) {
            	    Log::error( $e->getMessage() );
                }

                //\Mail::queue(new \App\Mail\NewUser('c1kworldex@gmail.com', $user));
            }else{
                $user = $user[0];
            }

        }else{

            $user = Auth::user();
        }
        //\App\PurePHP::dd( $user );
        $exchange->user_id = $user->id;
        $exchange->uuid = hash('sha256', uniqid('', true));
        $exchange->status_id = 1;
        $exchange->gate = 'c1k.world';
        $exchange->ref_code_id = $user->referer_id;
        $exchange->save();

	try{
    	    Mail::queue(new NewOrder($user->email, $user, $exchange));
        } catch (Exception $e) {
    	    Log::error( $e->getMessage() );
        }

        try{
    	    Mail::queue(new NewOrder(config('mail.from.address'), $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }


        return redirect('application/'.$exchange->uuid);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Exchange  $exchange
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Exchange $exchange)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Exchange  $exchange
    * @return \Illuminate\Http\Response
    */
    public function destroy(Exchange $exchange)
    {
        //

        if( $exchange->status_id != 3 )
        {
            $exchange->status_id = 4;
            $exchange->save();
        }



        session()->flash('success', "Ok");
        return redirect('admin/orders');
    }



    public function welcome(Request $request)
    {
        if(Auth::guest())
        {
            if( $request->rid != null)
            {
                $id = 14;
                if( ($request->rid == 821) || ($request->rid == 110))
                {
                    $id = 821;
                }

                if( session('referer_id', null) == null )
                {
                    $referer = User::where('id','=',$id)->first();
                    if( $referer != null)
                    {
                        $referer->visits = $referer->visits+1;
                        $referer->save();

                        session( array('referer_id'=>$referer->id) );

                    }
                }
            }
        }
        return view('welcome');
    }


}
