<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\User;
use App\Exchange;
use App\Withdraw;
use App\PurePHP;
//use \Redis;


Route::group( ['middleware'=>'api'], function()
{
    Route::get('v1/blog', 'Api\v1\BlogController@index');
    Route::get('v1/blog/{slug}', 'Api\v1\BlogController@show');

    Route::get('v1/rates', 'Api\RatesController@index');
    Route::get('v1/export', 'Api\RatesController@export');
    Route::get('v1/rates_new', 'Api\RatesController@index');
    Route::get('v1/rates_old', 'Api\RatesController@index_old');
});


Route::group( ['middleware'=>'api'], function()
{
    Route::post('v1/login', 'Api\v1\AuthController@login');
    Route::post('v1/register', 'Api\v1\AuthController@signup');
    Route::post('v1/reset_password', 'Api\v1\AuthController@reset_password');
    Route::post('v1/reset_password_confirm', 'Api\v1\AuthController@reset_password_confirm');

    Route::get('v1/application', 'Api\v1\ExchangeController@show');

    Route::post('v1/application', 'Api\v1\ExchangeController@application');

    Route::post('v1/application_sepa', 'Api\v1\ExchangeController@application_sepa');

});


Route::group( ['middleware'=>'auth:api'], function()
{


    Route::get('v1/user', 'Api\v1\UserController@profile');
    Route::post('v1/user', 'Api\v1\UserController@profile_update');

    Route::post('v1/change_password', 'Api\v1\UserController@change_password');

    Route::post('v1/be_partner/confirm', 'Api\v1\AnketaController@confirm');
    Route::post('v1/be_partner', 'Api\v1\AnketaController@store');

    Route::get('v1/exchanges', 'Api\v1\ExchangeController@index');
    Route::get('v1/referals', 'Api\v1\ReferalController@index');

    Route::post('v1/verification/card', 'Api\v1\VerificationController@card');
    Route::post('v1/verification/account', 'Api\v1\VerificationController@account');

    Route::post('v1/withdraw', 'Api\v1\WithdrawController@create');
    Route::get('v1/withdraw', 'Api\v1\WithdrawController@index');

});






Route::middleware('api')->get('new_channel_post', function (Request $request) {

    if( $request->post_id != null)
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $news_id = $redis->get('news_id');
        if( $news_id != null )
        {
            $news_id = json_decode($news_id, true);
            $new_news_id = [ $request->post_id, $news_id[0], $news_id[1] ];
        }else{
            $new_news_id = [ $request->post_id, 1461, 1460 ];
        }
        $redis->set('news_id', json_encode($new_news_id) );
        return json_encode( $new_news_id );
    }
    return json_encode( $new_news_id );

});


Route::middleware('auth:api')->get('get_info', function (Request $request) {
    $user = $request->user();

    //$user = \App\User::where('id','=',48)->first();

    $referals = User::where('referer_id','=',$user->id)->select('id','email')->get();

    $exchanges = Exchange::where('ref_code_id', '=', $user->id)->with('status')->select('id', 'user_id', 'status_id','created_at','ref_profit')->get();
    $withdraws = Withdraw::where('user_id', '=', $user->id)->select('status_id', 'currency','address','balance','created_at','updated_at')->get();


    $data = [];
    $data['balance'] = $user->balance;
    $data['min_payout'] = 100;
    $data['items'] = [
        [ 'id'=>'BTC', 'title'=>'BTC', 'comission'=>0, 'amount' => $user->balance ],
        [ 'id'=>'ETH', 'title'=>'ETH', 'comission'=>0, 'amount' => $user->balance ],
        [ 'id'=>'USDT', 'title'=>'USDT', 'comission'=>0, 'amount' => $user->balance ],
    ];

    return $data;
});

Route::middleware('auth:api')->post('add_payout', function (Request $request) {

    $request->validate([
        'method_id'=>'required|string|in:BTC,ETH,USDT',
        'address'=>'required|string',
    ]);

    $user = $request->user();

    $params = $request->all();

    $payout_id = 0;
    if( $user->balance < 100 )
    {
        return response()->json(['error' => 'min amount 100' ], 200);
    }
    $withdraw = new Withdraw;
    $withdraw->user_id = $user->id;
    $withdraw->balance = $user->balance;

    $withdraw->fio = $user->name;
    $withdraw->telegram = $user->telegram;
    $withdraw->currency = $params['method_id'];
    $withdraw->address = $params['address'];

    $withdraw->save();


    return response()->json(['payout_id' => $withdraw->id ], 200);
});


Route::middleware('auth:api')->post('get_exchanges', function (Request $request) {

    $user = $request->user();
    $result = [];

    $data = Exchange::where('user_id',$user->id)->first();

    foreach($data as $row)
    {
        $tmp = [];
        $tmp['id'] = $row->id;
        $tmp['time'] = $row->id;
        $tmp['date'] = $row->id;
        $tmp['currency_code_give'] = $row->id;
        $tmp['currency_code_get'] = $row->id;
        $tmp['course_give'] = $row->id;
        $tmp['course_get'] = $row->id;
        $tmp['amount_give'] = $row->id;
        $tmp['amount_get'] = $row->id;
        $tmp['exchange_success'] = $row->status_id;
        $tmp['accrued'] = $row->id;
        $tmp['partner_reward'] = $row->ref_profit;
        $tmp['user_hash'] = $row->id;

        $result[] = $tmp;
    }


    return response()->json(['items' => $result ], 200);
});


Route::middleware('auth:api')->post('get_payouts', function (Request $request) {

    $user = $request->user();

    $withdraws = Withdraw::where('user_id', '=', $user->id)->get();
    $result = [];
    foreach($withdraws as $withdraw)
    {
        $tmp = [];
        $tmp['id'] = $withdraw->id;
        $tmp['time'] = $withdraw->created_at->timestamp;
        $tmp['date'] = $withdraw->created_at;
        $tmp['method_id'] = $withdraw->currency;
        $tmp['account'] = $withdraw->address;
        $tmp['pay_amount'] = $withdraw->balance;
        $tmp['pay_currency_code'] = $withdraw->currency;
        $tmp['original_amount'] = $withdraw->balance;
        $tmp['original_currency_code'] = $withdraw->currency;
        $tmp['status'] = $withdraw->status_id;

        $result[] = $tmp;
    }


    return response()->json(['items' => $result ], 200);
});

//Route::get('/rates/pairs', 'PairController@api_to_front');
Route::get('rates/pairs', 'Rates\CurrentRate@api_to_front');

Route::get('rates/pairs/new', 'Rates\CurrentRate@api_to_front_new');

Route::group( ['middleware'=>'apijson'], function()
{
    /* все что связано с обновлением базовых прайсов */
    Route::resource('reference_rates', 'Rates\ReferenceRates');

    Route::get('update_bittrex', 'Rates\BittrexCourseController@create');
    Route::get('update_bitfinex', 'Rates\BitfinexCourseController@create');
    Route::get('update_binance', 'Rates\BinanceCourseController@create');
    Route::get('update_bitstamp', 'Rates\BitstampCourseController@create');

    Route::get('update_forex','Rates\ForexCourseController@create');
    Route::get('update_privat24','Rates\ReferenceRates@update_privat24');


    Route::get('update_one2one',function () {
        return PurePHP::one2one();
    });
    Route::get('update_manual',function () {
        return PurePHP::manual();
    });
    Route::get('update_bestchange',function () {
        return PurePHP::get_bestchange();
    });

    Route::get('rates/clear_old_rates', 'Rates\ReferenceRates@clear_old_rates');
}
);
