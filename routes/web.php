<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// use App\Mail\NewOrder;
use App\Mail\NewOrder2;
// use App\Mail\NewOrderMarkdown;
// use App\User;
use App\Exchange;

// Route::get('test_mail/{exchange}',function ( Request $request, Exchange $exchange ) {
//
//     return new NewOrder($exchange->user->email, $exchange->user, $exchange);
// });

Route::get('test_mail_new_sdsa12533re/{exchange}',function ( Request $request, Exchange $exchange ) {

    return new NewOrder2($exchange->user->email, $exchange->user, $exchange);
});

//Route::get('telegram-login','Auth\TelegramLoginController@validateLogin');

Route::get('site_off_login','Auth\LoginController@showLoginForm');
Route::get('site_offline', function () {
    return view('site_off');
})->name('site_offline');






//OLD
//OLDRoute::get('100usdt', function () {
//OLD    return view('contest');
//OLD})->name('contest');

//OLDRoute::get('100501usdt', function () {
//OLD    return view('contest2');
//OLD})->name('contest2');



Route::group(['middleware' => 'site_mode'], function () {

    //Route::get('', 'ExchangeController@welcome')->name('welcome');
    Route::get('', function () {
        return view('static_pages.contacts');
    })->name('welcome');


    Route::post('changelocale', 'TranslationController@changeLocale')->name('changelocale');

    Auth::routes(['verify' => true]);

    Route::get('partners', function () {
//        return view('static_pages.partners');
        return view('static_pages.contacts');
    })->name('partners');


//    Route::get('investing', function () {
//        return response()->file('C1K_investing.pdf');
//    })->name('investing');

    Route::get('about', function () {
        return view('static_pages.about');
    })->name('about');

    Route::get('contacts', function () {
        return view('static_pages.contacts');
    })->name('contacts');

    Route::get('oferta', function () {
        return view('static_pages.oferta');
    })->name('oferta');

    Route::get('card_verify_info', 'AccountController@card_verify_info')->name('card_verify_info');
    Route::get('skrill_verify_info', 'AccountController@skrill_verify_info')->name('skrill_verify_info');

    Route::group(['middleware' => 'auth'], function () {
        Route::group(array('middleware' => 'admin_panel_access', 'prefix'=>'admin', 'as' => 'admin.'), function () {

            Route::post('to_factor', function ( ) {
                request()->validate([
                    'to_factor'=>'required|string|max:6|min:6',
                    'g-recaptcha-response' => 'required|recaptcha',
                ]);
                $user = request()->user();
                if( $user->to_factor == request()->to_factor )
                {
                        $user->to_factor = null;
                        $user->save();
                        return redirect()->route('admin.dashboard');
                }
                return redirect()->back()
                    ->withErrors(['to_factor' =>
                        'The two factor code you have entered does not match']);
            });



            Route::get('to_factor', function ( ) {
                if( request()->user()->to_factor == null )
                {
                    return redirect()->route('admin.dashboard');
                }
                return response()->view('admin.to_factor');
            })->name('to_factor');
        });
    });



    Route::group(['middleware' => 'auth'], function () {
        Route::group(array('middleware' => ['admin_panel_access', 'to_factor'], 'prefix'=>'admin', 'as' => 'admin.'), function () {
            Route::get('', 'Admin\DashboardController@index')->name('dashboard');
            Route::resource('users','Admin\UserController');

            Route::group(['middleware' => 'admin'], function (){

                Route::get('users_statistics','Admin\UserController@index_statistics');//->name('admin.users.index');

                Route::get('city/{city}/toggle', 'Admin\CityController@set_active')->name('city.set_active');
                Route::resource('city', 'Admin\CityController');
                Route::resource('coin', 'Admin\CoinController');
                Route::resource('reserv', 'Admin\ReservController');

                Route::get('pairs/favorites', 'Admin\PairController@favorites')->name('pairs.favorite');

                Route::resource('pairs', 'Admin\PairController');
                Route::resource('exchange', 'ExchangeController');
                Route::resource('referals', 'Admin\ReferalController');

                Route::resource('withdraw', 'WithdrawController');

                Route::get('orders', 'ExchangeController@index')->name('orders');

                Route::get('account_verifications', 'Admin\AccountVerificationController@index')->name('account_verifications');
                Route::get('card_verifications', 'Admin\CardVerificationController@index')->name('card_verifications');
                Route::delete('card_verifications/{cardVerification}', 'Admin\CardVerificationController@destroy')->name('card_verifications.destroy');
                Route::delete('account_verifications/{accountVerification}', 'Admin\AccountVerificationController@destroy')->name('account_verifications.destroy');


                Route::get('go_to_offline', 'Admin\DashboardController@go_to_offline')->name('go_to_offline');
                Route::get('go_to_online', 'Admin\DashboardController@go_to_online')->name('go_to_online');

                Route::get('best_offline', 'Admin\DashboardController@best_offline')->name('best_offline');
                Route::get('best_online', 'Admin\DashboardController@best_online')->name('best_online');

                Route::get('cash_offline', 'Admin\DashboardController@cash_offline')->name('cash_offline');
                Route::get('cash_online', 'Admin\DashboardController@cash_online')->name('cash_online');

                Route::get('bestchange',function () {
                    return \App\PurePHP::get_bestchange();
                })->name('test_bestchange');

            });
            Route::group(['middleware' => 'pr'], function (){
                Route::resource('anketas', 'AnketaController');
                Route::get('users_statistics','Admin\UserController@index_statistics');
            });

        });

        Route::group(array( 'prefix'=>'account', 'as' => 'account.'), function () {
            Route::get('', 'AccountController@index')->name('dashboard');
            Route::get('partner', 'AccountController@partner')->name('partner');
            Route::get('profile', 'AccountController@get_profile')->name('profile');
            Route::post('profile', 'AccountController@post_profile')->name('profile_post');
            Route::get('change_password', 'AccountController@get_change_password')->name('change_password');
            Route::post('change_password', 'AccountController@post_change_password')->name('change_password_post');

            Route::get('withdraw/list', 'AccountController@get_withdraw_list')->name('withdraw-list');
            Route::get('withdraw', 'AccountController@get_withdraw')->name('withdraw');




            Route::get('faq', 'AccountController@faq')->name('faq');

            Route::get('media', 'AccountController@media')->name('media');

            Route::get('become_manager', 'AccountController@become_manager')->name('become_manager');
            Route::get('become_monitor', 'AccountController@become_monitor')->name('become_monitor');
            Route::get('become_lider', 'AccountController@become_lider')->name('become_lider');
            Route::post('anketa', 'AnketaController@create')->name('anketa');
            Route::get('verify/{code}', 'AnketaController@verify')->name('verify_anketa');

            Route::get('card_verify', 'AccountController@card_verify')->name('card_verify');
            Route::post('card_verify', 'AccountController@verify_card')->name('verify_card');

            Route::get('skrill_verify', 'AccountController@skrill_verify')->name('skrill_verify');
            Route::post('skrill_verify', 'AccountController@verify_skrill')->name('verify_skrill');

            Route::post('withdraw', 'WithdrawController@store')->name('create_withdraw');
        });

    });
    Route::get('bepartner', 'AccountController@bepartner')->name('bepartner');
    Route::get('faq_base', 'AccountController@faq_base')->name('faq_base');
    Route::get('faq_monitor', 'AccountController@faq_monitor')->name('faq_monitor');
    Route::get('faq_lider', 'AccountController@faq_lider')->name('faq_lider');
    Route::get('faq_manager', 'AccountController@faq_manager')->name('faq_manager');

    Route::get('media', 'AccountController@media')->name('media');


    Route::get('exchange', 'ExchangeController@welcome');

    Route::post('newexchange', 'ExchangeController@newexchange');
    Route::post('exchange', 'ExchangeController@exchange');
    Route::post('cashless_exchange', 'ExchangeController@cashless_exchange');
    //Route::get('cashless_exchange', 'ExchangeController@cashless_exchange');
    Route::get('cashless_exchange', 'ExchangeController@welcome');

    Route::get('signup/{hash}', 'AccountController@signup')->name('signup');

    Route::post('application', 'ExchangeController@application');


    // Adv Merchant
    Route::post('application/merchant/success', function () {
        return view('merchant/success');
    });
    Route::post('application/merchant/error', function () {
        return view('merchant/error');
    });
    Route::post('application/merchant/status', function () {
        return view('merchant/status');
    });


    Route::get('application/{exchange}', 'ExchangeController@show');
    Route::get('application', 'ExchangeController@welcome');
});





//exports
Route::get('exportxmlbest','Rates\CurrentRate@bestchange_export')->name('exportxmlbest_old');


Route::get('export-rates-bitcoinwide-com',function(){
    $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    if( !in_array( $user_ip, ['135.181.250.104', '95.216.177.34', '78.46.83.203','93.74.134.10']) )
    {
	abort(418);
    }


    $rates = new \App\Http\Controllers\Rates\CurrentRate;

    return $rates->bestchange_export();

})->name('export-rates-bitcoinwide-com');



// Route::get('export-rates-bitcoinwide-com',function(){
//     $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
//     if( $user_ip != '78.46.83.203' )
//     {
// 	abort(418);
//     }
//
//
//     $rates = new \App\Http\Controllers\Rates\CurrentRate;
//
//     return $rates->bestchange_export();
//
// })->name('export-rates-bitcoinwide-com');

Route::get('export-rates-exnode-ru',function(){
    $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    //104.21.82.168
    //172.67.130.143
    //104.21.65.4


//    if( $user_ip != '78.46.83.203' )
//    {
//	abort(418);
//    }


    $rates = new \App\Http\Controllers\Rates\CurrentRate;

    return $rates->bestchange_export();

})->name('export-rates-exnode-ru');


Route::get('exportxml-test','Rates\CurrentRate@bestchange_export_new')->name('exportxml-test');

Route::get('exportxmlfilebest','Rates\CurrentRate@bestchange_export')->name('exportxmlbest');

Route::get('exportxmlfile','Rates\CurrentRate@bestchange_export')->name('exportxmlbest');

Route::get('exportxmlbest.xml','Rates\CurrentRate@bestchange_file')->name('exportxmlbestfile');

Route::get('exportxmlfileok','Rates\CurrentRate@bestchange_export')->name('exportxmlok');

Route::get('exportxmlok','Rates\CurrentRate@bestchange_export')->name('exportxmlok');
Route::get('exportxmlok.xml','Rates\CurrentRate@bestchange_export')->name('exportxmlokfile');

Route::get('exportxmlglazok','Rates\CurrentRate@bestchange_export')->name('exportxmlglazok');
Route::get('exportxmlglazok.xml','Rates\CurrentRate@bestchange_export')->name('exportxmlglazokfile');

Route::get('export_exchangesumo','Rates\CurrentRate@exchangesumo')->name('export_exchangesumo');
