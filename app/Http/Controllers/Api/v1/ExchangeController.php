<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rates\CurrentRate;
use App\Exchange;
use App\UserIp;
use App\Reserv;
use App\User;
use App\Pair;
use App\AccountVerification;
use App\CardVerification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Mail\NewUser;
use App\Mail\NewOrder;
/**
* @group Exchange
*/


class ExchangeController extends Controller
{
    /**
    * History
    */

    public function index( Request $request )
    {
        $user = $request->user();
        $data = [];

        $orders = Exchange::with('pair','pair.base_currency','pair.quote_currency','status')->where('user_id','=',$user->id)->orderBy('id','DESC')->get();

        foreach( $orders as $order )
        {
            $data[] = [
                'id'=>$order->id,
                'send'=>$order->amount_take*1,
                'send_currency'=>$order->pair->base_currency->code,
                'get'=>$order->amount_get*1,
                'get_currency'=>$order->pair->quote_currency->code,
                'date'=>$order->created_at->format('Y-m-d H:i:s'),
                'status'=>$order->status->name,
            ];
        }

        return response()->json([ 'status'=>'success', 'data'=>$data]);
    }

    /**
    * Show application by uuid
    * Показать заявку по uuid
    * @bodyParam application string required Uuid заявки
    */
    public function show( Request $request )
    {
        $request->validate([
            'application'=>'required|string|max:64',
        ]);
        $order = Exchange::with('pair','pair.base_currency','pair.quote_currency','status','pair.city')->where('uuid','=',$request->application)->first();

        if( $order == null )
        {
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Application not found'], 404);
        }

        $rates = json_decode($order->rates_debug, true);

        if( $order->side == 'buy' )
        {
            if( $rates['pair_rate']['bid_coef']??0 > 1 )
            {
                $rate = round($rates['pair_rate']['bid_coef']??0*100-100, 4);
            }else{
                $rate = round(100-$rates['pair_rate']['bid_coef']??0*100, 4)*-1;
            }
        }else{
            if( $rates['pair_rate']['ask_coef']??0 > 1 )
            {
                $rate = round( $rates['pair_rate']['ask_coef']??0*100 - 100, 4)*-1;
            }else{
                $rate = round( 100 - $rates['pair_rate']['ask_coef']??0*100, 4);
            }
        }

        //$rate = ( $order->side == 'buy' ) ? ((($rates['pair_rate']['bid_coef']??0)-1)*100) : ((($rates['pair_rate']['ask_coef']??0)-1)*100);

        if( ( $order->pair->base_currency->currency_type == 'cash' ) || ( $order->pair->quote_currency->currency_type == 'cash' )  ){

        }elseif( ( $order->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($order->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC', 'USDTBEP20']) )  ){

        }elseif( ( $order->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($order->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC', 'USDTBEP20']) )  ){

        }else{
            $rate = null;
        }

        $need_verification = false;

        if( $order->side == 'sell' )
        {
            if(  in_array($order->pair->quote_currency->code, ['P24UAH','KSPBKZT','MONOBUAH']) )
            {
                if( !CardVerification::whereUserId( $order->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }elseif( in_array($order->pair->quote_currency->code, ['ADVCUSD','SKLUSD','SKLEUR','NTLRUSD','PNRUSD', 'SEPAEUR', 'SEPAUSD']) ){

                if( !AccountVerification::whereUserId( $order->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }
        }else{
            if( in_array($order->pair->base_currency->code, ['ADVCUSD', 'SKLUSD','SKLEUR','NTLRUSD','PNRUSD', 'SEPAEUR', 'SEPAUSD']) ){

                if( !AccountVerification::whereUserId( $order->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }
        }


        $data = [
            'id'=>$order->id,
            'send'=>$order->amount_take*1,
            'send_currency'=>( $order->side == 'buy' ) ? $order->pair->base_currency->code : $order->pair->quote_currency->code,
            'get'=>$order->amount_get*1,
            'get_currency'=>( $order->side == 'sell' ) ? $order->pair->base_currency->code : $order->pair->quote_currency->code,
            'date'=>$order->created_at->format('Y-m-d H:i:s').' UTC+2',
            'end_date'=>$order->created_at->addHour()->format('Y-m-d H:i:s').' UTC+2',
            'status'=>$order->status->name,
            'first_name'=>$order->first_name,
            'telegram'=>$order->telegram,
            'phone'=>$order->phone,
            'viber'=>$order->viber,
            'whatsapp'=>$order->whatsapp,
            'email'=>$order->email,
            'city'=>($order->pair->city->name != 'Cashless') ? $order->pair->city->name : null,
            'rate' => $rate,
            'address_to'=>$order->address_to,
            'address_from'=>$order->address_from,

            'need_verification'=>$need_verification,

            'send_currency_type'=>( $order->side == 'buy' ) ? $order->pair->base_currency->adress_type : $order->pair->quote_currency->adress_type,
            'get_currency_type'=>( $order->side == 'sell' ) ? $order->pair->base_currency->adress_type : $order->pair->quote_currency->adress_type
        ];

        if( $order->sepa != null )
        {
            $data[ 'sepa' ] = $order->sepa;
        }

        return response()->json([ 'status'=>'success', 'data'=>$data]);


    }

    /**
    * Create application
    *
    * Создать заявку на обмен
    * @bodyParam g-recaptcha-response string required
    * @bodyParam side string required in:sell,buy
    * @bodyParam category_pair_id numeric required
    * @bodyParam amount_take numeric required nullable
    * @bodyParam amount_get numeric required nullable
    * @bodyParam first_name string required nullable
    * @bodyParam address_from string required nullable
    * @bodyParam address_to string required nullable
    * @bodyParam viber string required nullable
    * @bodyParam phone string required nullable
    * @bodyParam telegram string required nullable
    * @bodyParam whatsapp string required nullable
    * @bodyParam email string required
    * @bodyParam referer string
    * @bodyParam ref_code string
    * @bodyParam bank_name string Наименование банка
    * @bodyParam bank_address string Юридический адрес банка
    * @bodyParam bank_account string Полное имя, фамилия владельца счета
    * @bodyParam bank_iban string Номер счета (IBAN)
    */

    public function application( Request $request )
    {
        $request->validate([
            'g-recaptcha-response' => 'required|recaptcha',
            'side'=>'required|string|max:4|in:sell,buy',
            'category_pair_id'=>'required|numeric|exists:pairs,id',
            'amount_take'=>'required|numeric',
            'amount_get'=>'required|numeric',
            'first_name'=>'required|string|max:191',
            'address_from'=>'nullable|string|max:191',
            'address_to'=>'nullable|string|max:191',
            'viber'=>'nullable|string|max:191',
            'phone'=>'nullable|string|max:191',
            'telegram'=>'nullable|string|max:191',
            'whatsapp'=>'nullable|string|max:191',
            'email'=>'required|string|max:191|email',
            'referer' => 'bail|string|max:191',
            'ref_code'=>'bail|string|max:191',
            'bank_name'=>'bail|string|max:191',
            'bank_address'=>'bail|string|max:191',
            'bank_account'=>'bail|string|max:191',
            'bank_iban'=>'bail|string|max:191',
        ]);
        $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        $site_mode = Cache::get( 'site_mode', null );

        if($site_mode != null)
        {
            if($site_mode == 'off')
            {
                abort(429);
            }
        }


        $userIp = UserIp::where('user_ip','=',$user_ip)->first();
        if( $userIp == null )
        {
            $userIp = UserIp::create(['user_ip'=>$user_ip]);
        }else{
            if( $userIp->blocked )
            {
                abort(429);
            }
        }

        $exchange_list = Exchange::where('user_ip', '=', $user_ip)->orderBy('id','DESC')->first();
        if( $exchange_list != null )
        {
            if( ($exchange_list->created_at->timestamp + 60) > time() )
            {
                abort(429);
            }
        }

        $pair = Pair::find($request->category_pair_id);
        if( $request->side == 'buy' )
        {
            $get_currency = $pair->quote_currency;
            $min_amount_take = $pair->base_min;
            $max_amount_take = $pair->base_max;

            $min_amount_get = $pair->quote_min;
            $max_amount_get = $pair->quote_max;
        }else{
            $get_currency = $pair->base_currency;
            $min_amount_take = $pair->quote_min;
            $max_amount_take = $pair->quote_max;

            $min_amount_get = $pair->base_min;
            $max_amount_get = $pair->base_max;
        }

        $reserv = Reserv::whereCoinId($get_currency->id)->first()->amount ?? 0;
        $request->validate([
            'amount_get'=>'max:'.$reserv,
        ]);

        $request->validate([
            //'amount_get'=>'numeric|min:'.$min_amount_get.'|max:'.$max_amount_get,
            'amount_get'=>'numeric|max:'.$max_amount_get,
            'amount_take'=>'numeric|min:'.$min_amount_take.'|max:'.$max_amount_take,
        ]);


        // rate flood
        //if( $request->email == '3617904@gmail.com')
        //{
        $pair = Pair::with('base_currency','quote_currency','provider','city')->find( $request->category_pair_id );
        $best_rate = CurrentRate::get_rate_best($pair);
        $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
        $final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);

        if( $request->side == 'sell' )
        {
            $control_price = $request->amount_get / ( $request->amount_take / $final_rate['ask'] );
            // $e = [
            //     'check'=>($request->amount_take / $final_rate['ask']),
            //     'check2'=>( $request->amount_get / ( $request->amount_take / $final_rate['ask'] ) ),
            //     'rate'=>(1-$control_price/$final_rate['ask'])*100,
            // ];

        }elseif( $request->side == 'buy' ){
            $control_price = $request->amount_get / ($request->amount_take * $final_rate['bid']) ;
            // $e = [
            //     'check'=>($request->amount_take * $final_rate['bid']),
            //     'check2'=>( $request->amount_get / ($request->amount_take * $final_rate['bid'])),
            //     'rate'=>($control_price/$final_rate['bid']-1)*100,
            // ];

        }
        // $t = [
        //     'get'=>$request->amount_get,
        //     'take'=>$request->amount_take,
        // ];
        if( $control_price > 1.002 )
        {
            abort(429, 'Reload page');
        }
        if( $control_price < 0.998 )
        {
            abort(429, 'Reload page');
        }
        //abort(429, json_encode([ $control_price, $final_rate ]) );
        //}
        // rate flood

        $exchange = new Exchange;
        $exchange->fill($request->only([
            'side',
            'category_pair_id',
            'amount_take',
            'amount_get',
            'first_name',
            'address_from',
            'address_to',
            'viber',
            'phone',
            'telegram',
            'whatsapp',
            'email',
        ]));
        $exchange->checkbox = 'on';


        $exchange->user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        $referer_id = 0;

        if( $request->has('referer') )
        {
            if( strpos($request->referer, 'bestchange' ) )
            {
                $referer_id = 48;
            }elseif( strpos($request->referer, 'kurs.expert' ) ){
                $referer = 1544;
            }elseif( strpos($request->referer, 'exchangesumo.com' ) ){
                $referer = 821;
            }elseif( strpos($request->referer, 'exnode.ru' ) ){
                $referer = 8053;
            }
        }

        if( ($referer_id == 0) && $request->has('ref_code') )
        {
            $referer = User::where('ref_code','=',$request->ref_code)->first();
            if( $referer != null )
            {
                $referer_id = $referer->id;
            }
        }

        if( !Auth::guard('api')->check() )
        {
            //$exchange->user_id = $user->id;
            $user = User::where('email','=',$request->email)->first();
            if( $user == null )
            {
                $user = new User;
                $user->fill($request->only(['viber','telegram','phone']));
                $user->name = $request->first_name;
                $user->role = 'user';
                $password = str_random(8);
                $user->password = Hash::make($password);
                $user->email = $request->email;
                $user->ref_code = strtolower(substr( str_replace(array('+','/','='),'', strrev(base64_encode( uniqid('',true) ))),0,20));
                $user->referer_id = $referer_id;
                $user->save();
                try{
                    Mail::queue(new NewUser($user->email, $user, $password));
                } catch (Exception $e) {
                    Log::error( $e->getMessage() );
                }

                //\Mail::queue(new \App\Mail\NewUser('c1kworldex@gmail.com', $user));
            }
        }else{

            $user = Auth::guard('api')->user();

            $user->name = $exchange->first_name;
            $user->telegram = $request->telegram ?? $user->telegram;
            $user->phone = $request->phone ?? $user->telegram;
            $user->save();
        }

        if( ( $referer_id != 0) && ($user->referer_id == 0) )
        {
            $user->referer_id = $referer_id;
            $user->save();
        }

        $exchange->bank_name = $request->bank_name;
        $exchange->bank_address = $request->bank_address;
        $exchange->bank_account = $request->bank_account;
        $exchange->bank_iban = $request->bank_iban;


        //\App\PurePHP::dd( $user );
        $exchange->user_id = $user->id;
        $exchange->uuid = hash('sha256', uniqid('', true));
        $exchange->status_id = 1;
        $exchange->gate = 'c1k.world';
        $exchange->ref_code_id = $user->referer_id;

        $exchange->rates_debug = json_encode([
            'pair_rate'=>[
                'bid_coef'=>$exchange->pair->bid_coef,
                'ask_coef'=>$exchange->pair->ask_coef,
            ],
            'best_rate'=>$best_rate,
            'limit_rate'=>$limit_rate,
            'final_rate'=>$final_rate,
            'control_price'=>$control_price,
        ]);

        $exchange->save();

        try{
            Mail::queue(new NewOrder($user->email, $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }

        try{
            Mail::queue(new NewOrder('c1kworldex@gmail.com', $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }
        try{
            Mail::queue(new NewOrder(config('mail.from.address'), $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }

        $rate =  ( $exchange->side == 'buy' ) ? ((($exchange->pair->bid_coef??0)-1)*100) : ((($exchange->pair->ask_coef??0)-1)*100);

        if( ( $exchange->pair->base_currency->currency_type == 'cash' ) || ( $exchange->pair->quote_currency->currency_type == 'cash' )  ){

        }elseif( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  ){

        }elseif( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  ){

        }else{
            $rate = null;
        }

        $need_verification = false;
        if( $exchange->side == 'sell' )
        {
            if(  in_array($exchange->pair->quote_currency->code, ['P24UAH','KSPBKZT','MONOBUAH']) )
            {
                if( !CardVerification::whereUserId( $exchange->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }elseif( in_array($exchange->pair->quote_currency->code, ['ADVCUSD', 'SKLUSD','SKLEUR','NTLRUSD','PNRUSD', 'SEPAEUR', 'SEPAUSD']) ){

                if( !AccountVerification::whereUserId( $exchange->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }
        }else{
            if( in_array($exchange->pair->base_currency->code, ['ADVCUSD', 'SKLUSD','SKLEUR','NTLRUSD','PNRUSD', 'SEPAEUR', 'SEPAUSD']) ){

                if( !AccountVerification::whereUserId( $exchange->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }
        }

        $data = [
            'id'=>$exchange->id,
            'send'=>$exchange->amount_take*1,
            'send_currency'=>$exchange->pair->base_currency->code,
            'get'=>$exchange->amount_get*1,
            'get_currency'=>$exchange->pair->quote_currency->code,
            'date'=>$exchange->created_at->format('Y-m-d H:i:s').' UTC+2',
            'end_date'=>$exchange->created_at->addHour()->format('Y-m-d H:i:s').' UTC+2',
            'status'=>$exchange->status->name,
            'uuid'=>$exchange->uuid,
            'first_name'=>$exchange->first_name,
            'telegram'=>$exchange->telegram,
            'viber'=>$exchange->viber,
            'whatsapp'=>$exchange->whatsapp,
            'phone'=>$exchange->phone ?? $user->phone.'*',
            'email'=>$exchange->email,
            'city'=>$exchange->pair->city->name,
            'rate' => $rate,
            'address_to'=>$exchange->address_to,
            'address_from'=>$exchange->address_from,

            'need_verification'=>$need_verification,

            'send_currency_type'=>( $exchange->side == 'buy' ) ? $exchange->pair->base_currency->adress_type : $exchange->pair->quote_currency->adress_type,
            'get_currency_type'=>( $exchange->side == 'sell' ) ? $exchange->pair->base_currency->adress_type : $exchange->pair->quote_currency->adress_type
        ];

        return response()->json([ 'status'=>'success', 'data'=>$data]);

    }

    /**
    * Create application sepa
    *
    * Создать заявку на обмен SEPA
    * @bodyParam g-recaptcha-response string required
    * @bodyParam side string required in:sell,buy
    * @bodyParam category_pair_id numeric required
    * @bodyParam amount_take numeric required nullable
    * @bodyParam amount_get numeric required nullable
    * @bodyParam first_name string required nullable
    * @bodyParam address_from string required nullable
    * @bodyParam address_to string required nullable
    * @bodyParam viber string required nullable
    * @bodyParam phone string required nullable
    * @bodyParam telegram string required nullable
    * @bodyParam whatsapp string required nullable
    * @bodyParam email string required
    * @bodyParam referer string
    * @bodyParam ref_code string
    * @bodyParam bank_name string Наименование банка
    * @bodyParam bank_address string Юридический адрес банка
    * @bodyParam bank_account string Полное имя, фамилия владельца счета
    * @bodyParam bank_iban string Номер счета (IBAN)
    *
    * @bodyParam purpose_of_payment string Назначение платежа
    *
    * @bodyParam street string Улица и номер дома
    * @bodyParam city string Город \ Населенный пункт
    * @bodyParam postcode string Почтовый индекс
    * @bodyParam reason string Скакой целью вы отправляете средства (селект со списком выбра)
    * @bodyParam bank_owner string Кому принадлежит банковский счет (селект со списком выбра)
    * @bodyParam site string Веб-сайт компании
    *
    * @bodyParam dir_name string - Имя директора
    * @bodyParam company_address string Адрес отправителя (компании)
    * @bodyParam swift_bic   string  - Swift/bic;
    * @bodyParam field_of_activity string - Cфера деятельности
    *
    *
    */
    public function application_sepa( Request $request )
    {
        $request->validate([
            'g-recaptcha-response' => 'required|recaptcha',
            'side'=>'required|string|max:4|in:sell,buy',
            'category_pair_id'=>'required|numeric|exists:pairs,id',
            'amount_take'=>'required|numeric',
            'amount_get'=>'required|numeric',
            'first_name'=>'required|string|max:191',
            'address_from'=>'nullable|string|max:191',
            'address_to'=>'nullable|string|max:191',
            'viber'=>'nullable|string|max:191',
            'phone'=>'required|string|max:191',
            'telegram'=>'nullable|string|max:191',
            'whatsapp'=>'nullable|string|max:191',
            'email'=>'required|string|max:191|email',
            'referer' => 'bail|string|max:191',
            'ref_code'=>'bail|string|max:191',
            'bank_name'=>'bail|required|string|max:191',
            'bank_address'=>'bail|required|string|max:191',
            'bank_account'=>'bail|required|string|max:191',
            'bank_iban'=>'bail|required|string|max:191',

            'purpose_of_payment'=>'bail|required|string|max:191',
            'street'=>'nullable|string|max:191',
            'city'=>'nullable|string|max:191',
            'postcode'=>'nullable|string|max:191',
            'reason'=>'nullable|string|max:191',
            'bank_owner'=>'nullable|string|max:191',
            'site'=>'nullable|string|max:191',

            'dir_name'=>'nullable|string|max:191',
            'company_address'=>'nullable|string|max:191',
            'swift_bic'=>'nullable|string|max:191',
            'field_of_activity'=>'nullable|string|max:191',
        ]);
        $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        $site_mode = Cache::get( 'site_mode', null );

        if($site_mode != null)
        {
            if($site_mode == 'off')
            {
                abort(429);
            }
        }


        $userIp = UserIp::where('user_ip','=',$user_ip)->first();
        if( $userIp == null )
        {
            $userIp = UserIp::create(['user_ip'=>$user_ip]);
        }else{
            if( $userIp->blocked )
            {
                abort(429);
            }
        }

        $exchange_list = Exchange::where('user_ip', '=', $user_ip)->orderBy('id','DESC')->first();
        if( $exchange_list != null )
        {
            if( ($exchange_list->created_at->timestamp + 60) > time() )
            {
                abort(429);
            }
        }

        $pair = Pair::find($request->category_pair_id);
        if( $request->side == 'buy' )
        {
            $get_currency = $pair->quote_currency;
            $min_amount_take = $pair->base_min;
            $max_amount_take = $pair->base_max;

            $min_amount_get = $pair->quote_min;
            $max_amount_get = $pair->quote_max;
        }else{
            $get_currency = $pair->base_currency;
            $min_amount_take = $pair->quote_min;
            $max_amount_take = $pair->quote_max;

            $min_amount_get = $pair->base_min;
            $max_amount_get = $pair->base_max;
        }

        $reserv = Reserv::whereCoinId($get_currency->id)->first()->amount ?? 0;
        $request->validate([
            'amount_get'=>'max:'.$reserv,
        ]);

        $request->validate([
            //'amount_get'=>'numeric|min:'.$min_amount_get.'|max:'.$max_amount_get,
            'amount_get'=>'numeric|max:'.$max_amount_get,
            'amount_take'=>'numeric|min:'.$min_amount_take.'|max:'.$max_amount_take,
        ]);


        // rate flood
        //if( $request->email == '3617904@gmail.com')
        //{
        $pair = Pair::with('base_currency','quote_currency','provider','city')->find( $request->category_pair_id );
        $best_rate = CurrentRate::get_rate_best($pair);
        $limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
        $final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);

        if( $request->side == 'sell' )
        {
            $control_price = $request->amount_get / ( $request->amount_take / $final_rate['ask'] );
            // $e = [
            //     'check'=>($request->amount_take / $final_rate['ask']),
            //     'check2'=>( $request->amount_get / ( $request->amount_take / $final_rate['ask'] ) ),
            //     'rate'=>(1-$control_price/$final_rate['ask'])*100,
            // ];

        }elseif( $request->side == 'buy' ){
            $control_price = $request->amount_get / ($request->amount_take * $final_rate['bid']) ;
            // $e = [
            //     'check'=>($request->amount_take * $final_rate['bid']),
            //     'check2'=>( $request->amount_get / ($request->amount_take * $final_rate['bid'])),
            //     'rate'=>($control_price/$final_rate['bid']-1)*100,
            // ];

        }
        // $t = [
        //     'get'=>$request->amount_get,
        //     'take'=>$request->amount_take,
        // ];
        if( $control_price > 1.002 )
        {
            abort(429, 'Reload page');
        }
        if( $control_price < 0.998 )
        {
            abort(429, 'Reload page');
        }
        //abort(429, json_encode([ $control_price, $final_rate ]) );
        //}
        // rate flood

        $exchange = new Exchange;
        $exchange->fill($request->only([
            'side',
            'category_pair_id',
            'amount_take',
            'amount_get',
            'first_name',
            'address_from',
            'address_to',
            'viber',
            'phone',
            'telegram',
            'whatsapp',
            'email',
        ]));
        $exchange->checkbox = 'on';

        $exchange->sepa = [
            'purpose_of_payment'=>$request->purpose_of_payment,
            'street'=>$request->street,
            'city'=>$request->city,
            'postcode'=>$request->postcode,
            'reason'=>$request->reason,
            'bank_owner'=>$request->bank_owner,
            'site'=>$request->site,

            'dir_name'=>$request->dir_name,
            'company_address'=>$request->company_address,
            'swift_bic'=>$request->swift_bic,
            'field_of_activity'=>$request->field_of_activity,
        ];

        $exchange->user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        $referer_id = 0;

        if( $request->has('referer') )
        {
            if( strpos($request->referer, 'bestchange' ) )
            {
                $referer_id = 48;
            }elseif( strpos($request->referer, 'kurs.expert' ) ){
                $referer = 1544;
            }elseif( strpos($request->referer, 'exchangesumo.com' ) ){
                $referer = 821;
            }elseif( strpos($request->referer, 'exnode.ru' ) ){
                $referer = 8053;
            }
        }

        if( ($referer_id == 0) && $request->has('ref_code') )
        {
            $referer = User::where('ref_code','=',$request->ref_code)->first();
            if( $referer != null )
            {
                $referer_id = $referer->id;
            }
        }

        if( !Auth::guard('api')->check() )
        {
            //$exchange->user_id = $user->id;
            $user = User::where('email','=',$request->email)->first();
            if( $user == null )
            {
                $user = new User;
                $user->fill($request->only(['viber','telegram','phone']));
                $user->name = $request->first_name;
                $user->role = 'user';
                $password = str_random(8);
                $user->password = Hash::make($password);
                $user->email = $request->email;
                $user->ref_code = strtolower(substr( str_replace(array('+','/','='),'', strrev(base64_encode( uniqid('',true) ))),0,20));
                $user->referer_id = $referer_id;
                $user->save();
                try{
                    Mail::queue(new NewUser($user->email, $user, $password));
                } catch (Exception $e) {
                    Log::error( $e->getMessage() );
                }

                //\Mail::queue(new \App\Mail\NewUser('c1kworldex@gmail.com', $user));
            }
        }else{

            $user = Auth::guard('api')->user();

            $user->name = $exchange->first_name;
            $user->telegram = $request->telegram ?? $user->telegram;
            $user->phone = $request->phone ?? $user->telegram;
            $user->save();
        }

        if( ( $referer_id != 0) && ($user->referer_id == 0) )
        {
            $user->referer_id = $referer_id;
            $user->save();
        }

        $exchange->bank_name = $request->bank_name;
        $exchange->bank_address = $request->bank_address;
        $exchange->bank_account = $request->bank_account;
        $exchange->bank_iban = $request->bank_iban;


        //\App\PurePHP::dd( $user );
        $exchange->user_id = $user->id;
        $exchange->uuid = hash('sha256', uniqid('', true));
        $exchange->status_id = 1;
        $exchange->gate = 'c1k.world';
        $exchange->ref_code_id = $user->referer_id;

        $exchange->rates_debug = json_encode([
            'pair_rate'=>[
                'bid_coef'=>$exchange->pair->bid_coef,
                'ask_coef'=>$exchange->pair->ask_coef,
            ],
            'best_rate'=>$best_rate,
            'limit_rate'=>$limit_rate,
            'final_rate'=>$final_rate,
            'control_price'=>$control_price,
        ]);

        $exchange->save();

        try{
            Mail::queue(new NewOrder($user->email, $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }

        try{
            Mail::queue(new NewOrder('c1kworldex@gmail.com', $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }
        try{
            Mail::queue(new NewOrder(config('mail.from.address'), $user, $exchange));
        } catch (Exception $e) {
            Log::error( $e->getMessage() );
        }

        $rate =  ( $exchange->side == 'buy' ) ? ((($exchange->pair->bid_coef??0)-1)*100) : ((($exchange->pair->ask_coef??0)-1)*100);

        if( ( $exchange->pair->base_currency->currency_type == 'cash' ) || ( $exchange->pair->quote_currency->currency_type == 'cash' )  ){

        }elseif( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  ){

        }elseif( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  ){

        }else{
            $rate = null;
        }

        $need_verification = false;
        if( $exchange->side == 'sell' )
        {
            if(  in_array($exchange->pair->quote_currency->code, ['P24UAH','KSPBKZT','MONOBUAH']) )
            {
                if( !CardVerification::whereUserId( $exchange->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }elseif( in_array($exchange->pair->quote_currency->code, ['ADVCUSD', 'SKLUSD','SKLEUR','NTLRUSD','PNRUSD', 'SEPAEUR', 'SEPAUSD']) ){

                if( !AccountVerification::whereUserId( $exchange->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }
        }else{
            if( in_array($exchange->pair->base_currency->code, ['ADVCUSD', 'SKLUSD','SKLEUR','NTLRUSD','PNRUSD', 'SEPAEUR', 'SEPAUSD']) ){

                if( !AccountVerification::whereUserId( $exchange->user_id )->whereApproved(true)->first() )
                {
                    $need_verification = true;
                }
            }
        }

        $data = [
            'id'=>$exchange->id,
            'send'=>$exchange->amount_take*1,
            'send_currency'=>$exchange->pair->base_currency->code,
            'get'=>$exchange->amount_get*1,
            'get_currency'=>$exchange->pair->quote_currency->code,
            'date'=>$exchange->created_at->format('Y-m-d H:i:s').' UTC+2',
            'end_date'=>$exchange->created_at->addHour()->format('Y-m-d H:i:s').' UTC+2',
            'status'=>$exchange->status->name,
            'uuid'=>$exchange->uuid,
            'first_name'=>$exchange->first_name,
            'telegram'=>$exchange->telegram,
            'viber'=>$exchange->viber,
            'whatsapp'=>$exchange->whatsapp,
            'phone'=>$exchange->phone ?? $user->phone.'*',
            'email'=>$exchange->email,
            'city'=>$exchange->pair->city->name,
            'rate' => $rate,
            'address_to'=>$exchange->address_to,
            'address_from'=>$exchange->address_from,

            'need_verification'=>$need_verification,

            'sepa'=>$exchange->sepa,

            'send_currency_type'=>( $exchange->side == 'buy' ) ? $exchange->pair->base_currency->adress_type : $exchange->pair->quote_currency->adress_type,
            'get_currency_type'=>( $exchange->side == 'sell' ) ? $exchange->pair->base_currency->adress_type : $exchange->pair->quote_currency->adress_type
        ];

        return response()->json([ 'status'=>'success', 'data'=>$data]);

    }

}
