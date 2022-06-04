<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

use App\AccountVerification;
use App\CardVerification;

use Illuminate\Support\Facades\Password;

/**
* @group Auth
*/

class UserController extends Controller
{

    /**
     * Profile
     * Получить профиль
     *
     *
     */
    public function profile(  Request $request )
    {
        $user = $request->user();
        $data = $user->toArray();

        $data['account_verification'] = AccountVerification::where('user_id','=',$user['id'])->first();
        $data['card_verification'] = CardVerification::where('user_id','=',$user['id'])->first();

        return response()->json(['status'=>'success', 'data'=>$data]);
    }

    /**
     * Profile
     * Обновить профиль
     *
     * @bodyParam name string string
     * @bodyParam phone string string
     * @bodyParam viber string string
     * @bodyParam whatsapp string string
     * @bodyParam telegram string string
     * @bodyParam email string Почта
     *
     */
    public function profile_update( Request $request )
    {
        $request->validate([
            'name'=>'string|nullable|max:191',
            'phone'=>'string|nullable|max:191',
            'viber'=>'string|nullable|max:191',
            'whatsapp'=>'string|nullable|max:191',
            'telegram'=>'string|nullable|max:191',
            'email'=>'bail|required|email|max:191|email:rfc,dns|unique:users,email,'.\Auth::user()->id,
        ]);

        $user = $request->user();

        if( $request->has('name') )
        {
            $user->name = $request->name;
        }
        if( $request->has('phone') )
        {
            $user->phone = $request->phone;
        }
        if( $request->has('viber') )
        {
            $user->viber = $request->viber;
        }
        if( $request->has('whatsapp') )
        {
            $user->whatsapp = $request->whatsapp;
        }
        if( $request->has('telegram') )
        {
            $user->telegram = $request->telegram;
        }
        if( $request->has('email') )
        {
            $user->email = $request->email;
        }
        $user->save();

        return response()->json(['status'=>'success', 'data'=>$user]);
    }


    /**
     * Change password
     *
     * Смена пароля, возвращает 200 успешно, 401 неправильный старый пароль, 422 ошибки параметров
     *
     * @response200 { status: "success", data: null }
     */
    public function change_password(  Request $request )
    {
        $request->validate([
            'old_password'=>'bail|required|string|min:8|max:191',
            'new_password'=>'bail|required|confirmed|string|min:8|max:191',
        ]);
        $user = $request->user();
        if( password_verify($request->old_password, $user->password ) )
        {
            $user->password = password_hash($request->new_password, PASSWORD_DEFAULT);
            $user->save();

            return response()->json(['status'=>'success', 'data'=>null]);
        }
        abort(401, "Wrong old_password.");
    }



}
