<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/account';
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            //'ref_code' => hash('sha256',uniqid('',true)),
            //'referer_id' => session('referer_id', '0')
        ]);

        //$user->ref_code = hash('sha256',uniqid('',true));

        $user->ref_code = strtolower(substr( str_replace(array('+','/','='),'', strrev(base64_encode( uniqid('',true) ))),0,20));

        $user->referer_id = session('referer_id', '0');
        $user->save();

        //$ref_code = new \App\ReferalCode;
        //$ref_code->user_id = $user->id;
        //$ref_code->visits = 0;
        //$ref_code->save();

        //$referal = new \App\Referal;
        //$referal->referer_id = session('referer_id', '0');
        //$referal->referal_id = $user->id;

        //$referal->ref_code_id = session('ref_code_id', '0');
        //$referal->save();
        return $user;
    }
}
