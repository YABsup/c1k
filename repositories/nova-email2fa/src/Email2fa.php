<?php

namespace AlogicProjects\Email2fa;

use Laravel\Nova\Tool;
use Request;

class Email2fa extends Tool
{
    public function boot()
    {
    }


    public function authenticate()
    {

        if ( $secret = Request::get('secret') ) {
            $user = request()->user();
            if( $secret === $user->email_2fa ){
                $user->email_2fa = null;
                $user->save();
                return response()->redirectTo(config('nova.path'));
            }
        }
        $data['error'] = 'One time password is invalid.';

        return view('alogicemail2fa::authenticate', $data);
    }
}
