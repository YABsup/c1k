<?php

namespace App\Http\Controllers;

use App\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
        $data = Withdraw::orderBy('status_id', 'ASC')->orderBy('created_at', 'DESC')->with('user','admin')->get();
        return view('admin.withdraws.index', compact('data'));
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
        $user = \Auth::user();
        //if($user->id == 14)
        //{
        $data = array(
            'user_id'=>$user->id,
            'balance'=>$user->balance,

        );

        $params = $request->all();
        //dd($params);
        $withdraw = new Withdraw;
        $withdraw->user_id = $user->id;
        $withdraw->balance = $user->balance;

        $withdraw->fio = $params['fio'];
        $withdraw->telegram = $params['telegram'];
        $withdraw->currency = $params['currency'];
        $withdraw->address = $params['address'];
        $withdraw->save();
        //}
        \Mail::queue(new \App\Mail\SampleMail('c1kworldex@gmail.com', 'Новая заявка на вывод'.$withdraw->id, 'Поступила новая заявка '.$withdraw->id));

        return redirect('account/withdraw');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Withdraw  $withdraw
    * @return \Illuminate\Http\Response
    */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Withdraw  $withdraw
    * @return \Illuminate\Http\Response
    */
    public function edit(Withdraw $withdraw)
    {

        if( $withdraw->status_id == 0)
        {

            $log = new \App\Logging;
            $log->user_id = \Auth::user()->id;
            $log->event = 'approve_withdraw';
            $log->desc = json_encode( [$withdraw->id, $withdraw->user->balance,  $withdraw->balance ] ); //TODO detailed
            $log->save();

            $withdraw->user_approved = \Auth::user()->id;
            $withdraw->status_id = 1;
            $withdraw->save();
            $withdraw->user->balance = $withdraw->user->balance - $withdraw->balance;
            $withdraw->user->save();




        }
        //
        // if(($exchange->status_id != 3) && $exchange->status_id != 4 )
        // {
        //   $exchange->status_id = $exchange->status_id+1;
        //
        //   if($exchange->status_id == 3)
        //   {
        //     $amount_get = $exchange->amount_get;
        //     $amount_take =$exchange->amount_take;
        //     $reserv_base = \App\Reserv::firstOrNew( array( 'coin_id'=>$exchange->pair->base_currency->id) );
        //     $reserv_quote = \App\Reserv::firstOrNew( array( 'coin_id'=>$exchange->pair->quote_currency->id) );
        //
        //     if($exchange->side == 'buy')
        //     {
        //       $reserv_base->amount = $reserv_base->amount + $amount_take;
        //       $reserv_quote->amount = $reserv_quote->amount - $amount_get;
        //     }else{
        //       $reserv_base->amount = $reserv_base->amount - $amount_get;
        //       $reserv_quote->amount = $reserv_quote->amount + $amount_take;
        //     }
        //     $reserv_base->save();
        //     $reserv_quote->save();
        //   }
        //
        //
        //   if(isset($_GET['profit']))
        //   {
        //     $exchange->profit = floatval($_GET['profit']);
        //
        //     if( $exchange->user->referer_id == 0)
        //     {
        //       if( $exchange->user->partner == 0 ) //Рядовой партнер который сам пришель
        //       {
        //         $cur_profit_user = $exchange->user->balance;
        //         $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.1; // 10% кешбек
        //         $exchange->user->save();
        //       }
        //     }else{
        //       if($exchange->user->referer->partner == 0) // Реферал рядового партнера
        //       {
        //         $cur_profit_referer = $exchange->user->referer->balance;
        //         $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.05; // 5% "Рядовому партнеру"
        //         $exchange->user->referer->save();
        //
        //         $cur_profit_user = $exchange->user->balance;
        //         $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.05;  // 5% кешбек рефералу "Рядового партнера"
        //         $exchange->user->save();
        //       }elseif($exchange->user->referer->partner == 1){ //Реферал мониторинга
        //         $cur_profit_referer = $exchange->user->referer->balance;
        //         $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.3; // 30% "Мониторингу"
        //         $exchange->user->referer->save();
        //
        //         $cur_profit_user = $exchange->user->balance;
        //         $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.1;  // 10% кешбек "Рядовому партнеру"
        //         $exchange->user->save();
        //       }elseif($exchange->user->referer->partner == 2){ //Лидер мнений
        //         $cur_profit_referer = $exchange->user->referer->balance;
        //         $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.4; // 40% "Лидеру мнений"
        //         $exchange->user->referer->save();
        //
        //         $cur_profit_user = $exchange->user->balance;
        //         $exchange->user->balance = $cur_profit_user + $_GET['profit'] * 0.1;  // 10% кешбек "Рядовому партнеру"
        //         $exchange->user->save();
        //         if( $exchange->user->referer->referer != null )
        //         {
        //           $cur_profit_referer = $exchange->user->referer->referer->balance;
        //           $exchange->user->referer->balance = $cur_profit_referer + $_GET['profit'] * 0.05; // 5% "Менеджеру Лидеров мнений"
        //           $exchange->user->referer->referer->save();
        //         }
        //       }
        //     }
        //   }
        //
        //   $exchange->save();
        // }

        session()->flash('success', "Ok");
        return redirect('admin/withdraw');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Withdraw  $withdraw
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Withdraw $withdraw)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Withdraw  $withdraw
    * @return \Illuminate\Http\Response
    */
    public function destroy(Withdraw $withdraw)
    {
        //
        if( $withdraw->status_id != 1)
        {
            $withdraw->status_id = 2;
            $withdraw->save();
        }

        session()->flash('success', "Ok");
        return redirect('admin/withdraw');
    }
}
