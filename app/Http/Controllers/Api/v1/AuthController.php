<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

/**
* @group Auth
*/

class AuthController extends Controller
{
    /**
     * Login
     *
     * @bodyParam email email Почта пользователя
     * @bodyParam password Пароль пользователя
     * @bodyParam g-recaptcha-response string Рекапча
     *
     */
    public function login( Request $request )
    {
        $request->validate([
            'email'=>'bail|required|email',
            'password'=>'bail|required|string|max:191|min:8',
            //'g-recaptcha-response' => 'bail|required|recaptcha',
        ]);

        $user = User::whereEmail( $request->email )->first();
        if( $user != null )
        {

            if( password_verify($request->password, $user->password ) )
            {
                $data = [
                    'access_token'=>$user->createToken('c1k.world')->accessToken
                 ];

                return response()->json(['status'=>'success', 'data'=>$data]);
            }
        }

        return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Wrong password or email'], 401);
    }





    /**
     * Reset password
     *
     * Запрос на сброс пароля, всегда возвращает 200
     *
     * @bodyParam email string Почта пользователя
     * @bodyParam g-recaptcha-response string Рекапча
     *
     * @response200 { status: "success", data: null }
     */
    public function reset_password(  Request $request )
    {
        $request->validate([
            'email'=>'bail|required|email|max:191|email:rfc,dns',
            'g-recaptcha-response' => 'bail|required|recaptcha',
        ]);
        $user = User::whereEmail( $request->email )->first();
        if( $user != null )
        {
             $token = Password::getRepository()->create($user);
             $user->sendPasswordResetNotification($token);

        }else{
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Email not found'], 400);
        }
        return response()->json(['status'=>'success', 'data'=>null]);
    }


    /**
     * Reset password confirm
     *
     * Подтверждение смены пароля с помощью токена из письма, успех возвращает 200, не успех 401, ошибки параметров 422
     *
     * @bodyParam email string required Почта
     * @bodyParam token string required Токен из почты
     * @bodyParam new_password string required Новый пароль
     * @bodyParam new_password_confirmation string required Новый пароль (подтверждение)
     * @bodyParam g-recaptcha-response string required Капча
     *
     * @response200 { status: "success", data: null }
     * @response401 { status: "error", data: null }
     */
    public function reset_password_confirm(  Request $request )
    {
        $request->validate([
            'email'=>'bail|required|email|max:191|email:rfc,dns',
            'token'=>'bail|required|string|max:191|',
            'new_password'=>'bail|required|string|confirmed|min:8|max:191',
            'g-recaptcha-response' => 'bail|required|recaptcha',
        ]);
        $user = User::whereEmail( $request->email )->first();
        if( $user != null )
        {
            $token = DB::table('password_resets')->whereEmail($user->email)->first();
            if( $token != null )
            {
                if( password_verify($request->token, $token->token ) )
                {
                    $user->password = password_hash($request->new_password, PASSWORD_DEFAULT);
                    $user->save();

                    return response()->json(['status'=>'success', 'data'=>null]);
                }
            }
        }else{
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Email not found'], 400);
        }
        abort(401);
    }


    /**
     * Register
     * Регистрация пользователя
     *
     * Возвращает или 422 ошибку, или 200 ответ с { status: "success", data: null }
     *
     *
     * @bodyParam email email required Почта
     * @bodyParam password password required Пароль
     * @bodyParam password_confirmation password required Подтверждение пароля
     * @bodyParam g-recaptcha-response string required Рекапча
     * @bodyParam g-recaptcha-response string required Рекапча
     * @bodyParam referer string
     * @bodyParam ref_code string
     *
     * @response200 { status: "success", data: null }
     */
    public function signup( Request $request )
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email'=>'bail|required|email|max:191|email:rfc,dns',
            'password'=>'bail|required|string|confirmed|min:8|max:191',
            'g-recaptcha-response' => 'bail|required|recaptcha',
            'referer' => 'bail|string|max:191',
            'ref_code' => 'bail|string|max:191',
        ]);
        $user = User::firstOrCreate([
            'email'=>$request->email,
        ],
        [
            'email'=>$request->email,
            'name'=>$request->name,
            'password'=>password_hash($request->password, PASSWORD_DEFAULT),
        ]);
        if( !$user->wasRecentlyCreated )
        {
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Already registered'], 400);
        }
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
        $user->referer_id = $referer_id;

        if( $user->ref_code == null )
        {
            $user->referer_id = $referer_id;
            $user->ref_code = strtolower(substr( str_replace(array('+','/','='),'', strrev(base64_encode( uniqid('',true) ))),0,20));

        }

        $user->save();
        return response()->json(['status'=>'success', 'data'=>null ]);
    }


}
