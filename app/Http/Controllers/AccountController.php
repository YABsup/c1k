<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    public function index()
    {
        //
        $user = \Auth::user();
        $ref_status = 1;
        if($user->referer != null )
        {
            if($user->referer->partner == 0 )
            {
                $ref_status = 0;
            }
        }
        $orders = \App\Exchange::with('pair','pair.base_currency','pair.quote_currency','status')->where('user_id','=',$user->id)->orderBy('id','DESC')->get();
        return view('account.index', compact('user','orders','ref_status'));
    }

    public function get_withdraw_list()
    {
        //
        $user = \Auth::user();
        $ref_status = 1;
        if($user->referer != null )
        {
            if($user->referer->partner == 0 )
            {
                $ref_status = 0;
            }
        }
        $withdraws = \App\Withdraw::with('status')->where('user_id','=',$user->id)->orderBy('id','DESC')->get();
        return view('account.withdraws', compact('user','ref_status','withdraws') );
    }



    public function partner()
    {
        //
        $user = \Auth::user();
        $ref_status = 1;
        if($user->referer != null )
        {
            if($user->referer->partner == 0 )
            {
                $ref_status = 0;
            }
        }

        $sel = $user->referals;
        $orders = \App\Exchange::with('pair','pair.base_currency','pair.quote_currency','status')->whereIn('user_id',$sel)->orderBy('id','DESC')->get();


        return view('account.partner', compact('user','orders', 'balance','sel','ref_status'));
    }

    public function get_profile()
    {
        //
        $user = \Auth::user();
        return view('account.profile',compact('user'));
    }
    public function post_profile(Request $request)
    {
        //
        $user = \Auth::user();
        $user->fill($request->All());
        $user->name = $request->username;
        $user->save();

        return view('account.profile',compact('user'));
    }

    public function get_change_password()
    {
        //
        return view('account.change_password');
    }

    public function faq_base()
    {
        //
        return view('account.faq_base');
    }
    public function faq_monitor()
    {
        //
        return view('account.faq_monitor');
    }
    public function faq_lider()
    {
        //
        return view('account.faq_lider');
    }
    public function faq_manager()
    {
        //
        return view('account.faq_manager');
    }

    public function post_change_password(Request $request)
    {
        $user = \Auth::user();

        header("Content-Type: application/json");
        if (Hash::check($request->oldPass, $user->password)) {
            $user->password = Hash::make($request->newPass);
            $user->save();
        }else{
            header('HTTP/1.1 400 Bad Request');

        }
        return view('account.change_password');
    }


    public function get_withdraw()
    {
        //
        $user = \Auth::user();

    	$withdraws = \App\Withdraw::where('user_id','=',$user->id)->where('status_id','=', 0 )->get();


        return view('account.withdraw',compact('user','withdraws'));
    }

    public function card_verify_info()
    {
        //
        return view('account.card_verify_info');
    }
    public function card_verify()
    {
        //
        return view('account.card_verify');
    }
    public function skrill_verify_info()
    {
        //
        return view('account.skrill_verify_info');
    }
    public function skrill_verify()
    {
        //
        return view('account.skrill_verify');
    }

    
    //account/card_verify
    public function verify_card(Request $request)
    {
        //
        $user = \Auth::user();
        $foto = $request->file('foto');



        if($user->verified_send != 1)
        {
            if( $foto != null)
            {
                error_log('foto');
                $user->verified_send = 1;
                $user->save();
                $sample_mail_text['first_name'] = $request->first_name;
                $sample_mail_text['tel'] = $request->tel;
                $sample_mail_text['email'] = $request->email;
                $sample_mail_text['card'] = $request->card;


                $mail = new \App\Mail\SampleMail('c1kworldex@gmail.com', 'New card verifiation', array('request'=>$sample_mail_text, 'user'=>$user ), 'mail.card_verifi');

            	foreach( $foto as $fotos)
            	{
            		$mail->attach($fotos->getRealPath(), array(
                	'as' => $fotos->getClientOriginalName(), // If you want you can chnage original name to custom name
                	'mime' => $fotos->getMimeType())
            	    );
		}

                \Mail::queue($mail, $user);

            }
        }

        return view('account.card_verify');
    }
    public function verify_skrill(Request $request)
    {
        //
        $user = \Auth::user();
        $foto = $request->file('foto');


        if($user->verified_send != 1)
        {
            if( $foto != null)
            {
                error_log('foto');
                $user->verified_send = 1;
                $user->save();
                $sample_mail_text['first_name'] = $request->first_name;
                $sample_mail_text['tel'] = $request->tel;
                $sample_mail_text['email'] = $request->email;
                $sample_mail_text['card'] = $request->card;


                $mail = new \App\Mail\SampleMail('c1kworldex@gmail.com', 'New skrill/NETELLER verifiation', array('request'=>$sample_mail_text, 'user'=>$user ), 'mail.skrill_verifi');

                foreach( $foto as $fotos)
                {
                    $mail->attach($fotos->getRealPath(), array(
                    'as' => $fotos->getClientOriginalName(), // If you want you can chnage original name to custom name
                    'mime' => $fotos->getMimeType())
                    );
        }
                \Mail::queue($mail, $user);

            }
        }

        return view('account.skrill_verify');
    }
    public function post_withdraw()
    {
        //

    }
    public function signup($hash, Request $request)
    {
        if( array_key_exists('HTTP_REFERER', $request->server) );
        {
            $ref_site = $request->server('HTTP_REFERER');
            if( strpos( $ref_site, 'bestchange.com' ) )
            {
                \Session::put('locale', 'en');
            }elseif( strpos( $ref_site, 'bestchange.ru' ) )
            {
                //\Session::put('locale', 'ru');
            }

        }

        if( (strlen($hash) == 64) || (strlen($hash) == 26) || (strlen($hash) == 13) || (strlen($hash) == 20)  )
        {
            $hash=strtolower($hash);
            if(\Auth::guest())
            {
                $referer = \App\User::where('ref_code','=',$hash)->first();
                if($referer != null)
                {
                    $referer->visits = $referer->visits+1;
                    $referer->save();
                    session( array('referer_id'=>$referer->id) );
                }
            }
        }

        return redirect()->route('welcome');
    }

    public function faq()
    {
        //
        return view('account.faq');
    }

    public function bepartner()
    {
        //
        $user = \Auth::user();
        $ref_status = 1;
        if( $user != null)
        {

            if( $user->referer != null )
            {
                if( $user->referer->partner == 0 )
                {
                    $ref_status = 0;
                }
            }
        }
        return view('account.bepartner',compact('user','ref_status'));
    }

    public function media()
    {
        //
        return view('account.media');
    }

    public function become_lider()
    {
        //
        return view('account.become_lider');
    }
    public function become_monitor()
    {
        //
        return view('account.become_monitor');
    }
    public function become_manager()
    {
        //
        return view('account.become_manager');
    }
}
