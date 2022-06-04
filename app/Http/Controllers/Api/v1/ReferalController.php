<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exchange;
use App\User;

use Illuminate\Support\Facades\Password;

/**
* @group Referals
*/

class ReferalController extends Controller
{
    /**
     * Index
     * Список реферальных обменов
     *
     *
     */
    public function index( Request $request )
    {

        $user = $request->user();
        $data = [];


        $orders = Exchange::with('pair','pair.base_currency','pair.quote_currency','status')->whereRefCodeId( $user->id )->orderBy('id','DESC')->get();

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


        return response()->json(['status'=>'success','data'=>$data]);
    }



}
