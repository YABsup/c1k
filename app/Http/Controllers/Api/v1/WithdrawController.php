<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exchange;
use App\Withdraw;
use App\UserIp;
use App\Reserv;
use App\User;
use App\Pair;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


/**
* @group Withdraw
* @authorized
*
*/

class WithdrawController extends Controller
{

    /** Withdraws List
    *
    *
    */
    public function index( Request $request )
    {
        $user = $request->user();
        $data = [];

        $withdraws = Withdraw::whereUserId($user->id)->orderBy('id','DESC')->get();
        foreach( $withdraws as $withdraw )
        {
            $data[] = [
                'id'=>$withdraw->id,
                'currency'=>$withdraw->currency,
                'address'=>$withdraw->address,
                'balance'=>$withdraw->balance*1,
                'status'=>($withdraw->status_id == 0) ? 'pending' : 'complite',
                'date'=>$withdraw->created_at->format('Y-m-d H:i:s'),
            ];
        }


        return response()->json([ 'status'=>'success', 'data'=>$data]);
    }

    /**
    * Create
    *
    * @bodyParam fio string required
    * @bodyParam currency string required in:BTC,ETH,USDT
    * @bodyParam address string required
    * @bodyParam telegram string required
    *
    */

    public function create( Request $request )
    {
        $request->validate([
            'fio'=>'required|string|max:191',
            'currency'=>'required|string|in:BTC,ETH,USDT',
            'address'=>'required|string|max:191',
            'telegram'=>'required|string|max:191',
        ]);

        $user = $request->user();
        if( $user->balance < 100 )
        {
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Недостаточнго средств на балансе, минимальный вывод 100$'], 400);
        }
        if( ( Withdraw::whereUserId($user->id)->where('status_id','=',0)->first() != null ) )
        {
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'У Вас уже есть заявка на расмотрении, ожидайте'], 400);
        }

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

        $text = 'Пользователь: '."<br>";
        $text .= 'Имя: '.$user->name."<br>";
        $text .= 'email: '.$user->email."<br>";
        $text .= 'Telagram: '.$user->name."<br>";
        $text .= "<br><hr>";
        $text .= 'Заявка на вывод: '."<br>";
        $text .= 'Контакт (имя): '.$withdraw->fio."<br>";
        $text .= 'Контакт (telegram): '.$withdraw->telegram."<br>";
        $text .= 'Валюта: '.$withdraw->currency."<br>";
        $text .= 'Адрес: '.$withdraw->address."<br>";
        $text .= 'Сума: '.($withdraw->balance*1)."$"."<br>";


        \Mail::queue(new \App\Mail\SampleMail('c1kworldex@gmail.com', 'Новая заявка на вывод - '.$withdraw->id, $text ));
        //\Mail::queue(new \App\Mail\SampleMail( '3617904@gmail.com', 'Новая заявка на вывод - '.$withdraw->id, $text ));




        $data = $withdraw;
        return response()->json([ 'status'=>'success', 'data'=>$data]);
    }



}
