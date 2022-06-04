<?php

namespace App\Http\Controllers;

use App\Anketa;
use Illuminate\Http\Request;

class AnketaController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
        $anketas = Anketa::where('status','!=',4)->orderBy('updated_at', 'DESC')->orderBy('status', 'ASC')->get();

        return view('admin.anketa.index', compact('anketas'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {

        //$request->validate([
        //    'g-recaptcha-response' => 'required|recaptcha',
        //]);
        //
        if(\Auth::user())
        {
            $anketa = new Anketa;
            $anketa->user_id = \Auth::user()->id;
            $anketa->verify_code = hash('sha256', uniqid('', true));

            $anketa->fill($request->all());
            $anketa->status=0;
            $anketa->save();

            \Mail::queue(new \App\Mail\NewAnketa(\Auth::user()->email, \Auth::user(), $anketa->verify_code));
            \Mail::queue(new \App\Mail\NewAnketa('c1kworldex@gmail.com', \Auth::user(), "Проверочный код отправлен"));
            //\Mail::queue(new \App\Mail\NewAnketa('3617904@gmail.com', \Auth::user(), $anketa->verify_code));

            $h4 = __('anketa.submit.h4');
            $text = __('anketa.submit.text');

            return view('account.sample',compact('h4','text'));
        }else{
            session()->flash('warning', "Error");
            return back();
        }

    }

    function verify(String $code)
    {
        $anketa = Anketa::where('verify_code','=',$code)->first();

        if($anketa != null)
        {
            if($anketa->status == 0)
            {
                $h4 = __('anketa.thanks.h4');
                $text = __('anketa.thanks.text');

                $anketa->status = 1;
                $anketa->save();
                \Mail::queue(new \App\Mail\SampleMail('pr@c1k.world', __('anketa.mail_code_verify_success.subject'), __('anketa.mail_code_verify_success.text')));
                \Mail::queue(new \App\Mail\SampleMail($anketa->email, 'Ваша заявка на активацию партнерской программы успешно принята', __('anketa.mail_code_verify_success.text'), 'mail.anketa-verify-ok'));

            }else{
                $h4 = __('anketa.proccess.h4');
                $text = __('anketa.proccess.text');
            }
        }else{
            $h4 = __('anketa.bad_code.h4');
            $text = __('anketa.bad_code.text');
        }

        return view('account.sample',compact('h4','text'));
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
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Anketa  $anketa
    * @return \Illuminate\Http\Response
    */
    public function show(Anketa $anketa)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Anketa  $anketa
    * @return \Illuminate\Http\Response
    */
    public function edit(Anketa $anketa)
    {
        //
        return view('admin.anketa.edit', compact('anketa'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Anketa  $anketa
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Anketa $anketa)
    {

        $res = $request->All();

        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'anketa_approve';
        $log->desc = json_encode( array($anketa->id, $res['anketa_approve']) );
        $log->save();

        if( $res['anketa_approve']=='true' )
        {
            $anketa->status = 2;
            if( $anketa->type == 'monitor' )
            {
                $anketa->user->partner = 1;
                $anketa->user->referer_id = 0;
            }elseif( $anketa->type == 'lider' ){
                $anketa->user->partner = 2;

                //$anketa->user->referer_id = 0;

            }elseif( $anketa->type == 'manager' ){
                $anketa->user->partner = 3;
                $anketa->user->referer_id = 0;
            }else{
                $anketa->user->partner = 0;
            }

            $anketa->user->save();
            $anketa->save();

            \Mail::queue(new \App\Mail\SampleMail($anketa->email, 'Ваша заявка на партнерство утверждена', 'Ваша заявка на партнерство утверждена', 'mail.anketa-accept'));

        }else{
            $anketa->status = 3;
            $anketa->save();

            \Mail::queue(new \App\Mail\SampleMail($anketa->email, 'Ваша заявка на партнерство отклонена', 'Ваша заявка на партнерство отклонена', 'mail.anketa-reject'));
        }

        //    $anketa->save();

        $anketas = Anketa::where('status','!=',4)->orderBy('updated_at', 'DESC')->orderBy('status', 'ASC')->get();

        return view('admin.anketa.index', compact('anketas'));
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Anketa  $anketa
    * @return \Illuminate\Http\Response
    */
    public function destroy(Anketa $anketa)
    {
        //
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'anketa_approve';
        $log->desc = json_encode( array($anketa->id, 'false') );
        $log->save();
        $anketa->status = 4;
        $anketa->save();
        \Mail::queue(new \App\Mail\SampleMail($anketa->email, 'Ваша заявка на партнерство отклонена', 'Ваша заявка на партнерство отклонена', 'mail.anketa-reject'));

        $anketas = Anketa::where('status','!=',4)->orderBy('updated_at', 'DESC')->orderBy('status', 'ASC')->get();

        return view('admin.anketa.index', compact('anketas'));
    }
}
