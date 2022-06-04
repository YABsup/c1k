<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Anketa;
use App\Mail\NewAnketa;
use App\Mail\SampleMail;
/**
* @group Partner anketa
*/

class AnketaController extends Controller
{
    /**
    * Store new anketa
    *
    * @bodyParam g-recaptcha-response string Рекапча
    */
    public function store( Request $request )
    {
        $request->validate([
            'username'=>'required|string|max:255|nullable',
            'email'=>'required|string|max:255|email',
            'telegram'=>'string|max:255|nullable',
            'kind_of_activity'=>'string|max:255|nullable',
            'auditory_type'=>'string|max:255|nullable',
            'auditory_count'=>'string|max:255|nullable',
            'youtube_link'=>'string|max:255|nullable',
            'insta_link'=>'string|max:255|nullable',
            'telegram_link'=>'string|max:255|nullable',
            'additional_link'=>'string|max:2000|nullable',
            'additional_info'=>'string|max:2000|nullable',
            'platform_name'=>'string|max:255|nullable',
            'platform_link'=>'string|max:255|nullable',
            'platform_position'=>'string|max:255|nullable',
            'platform_age'=>'string|max:255|nullable',
            'type'=>'required|string|max:255|in:base,monitor,manager,lider',
        ]);

        $test = Anketa::where('user_id','=',Auth::user()->id)->where('status','<',4)->first();
        if( $test != null )
        {
            return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Your partnership application is already pending'], 400);
        }


        $anketa = new Anketa;
        $anketa->user_id = Auth::user()->id;
        $anketa->verify_code = hash('sha256', uniqid('', true));

        $anketa->fill($request->only([
            'username',
            'email',
            'telegram',
            'kind_of_activity',
            'auditory_type',
            'auditory_count',
            'youtube_link',
            'insta_link',
            'telegram_link',
            'additional_link',
            'additional_info',
            'platform_name',
            'platform_link',
            'platform_position',
            'platform_age',
            'type',
        ]));
        $anketa->status=0;
        $anketa->save();

        Mail::queue(new NewAnketa(Auth::user()->email, Auth::user(), $anketa->verify_code));
        Mail::queue(new NewAnketa('c1kworldex@gmail.com', Auth::user(), "Проверочный код отправлен"));
        //\Mail::queue(new \App\Mail\NewAnketa('3617904@gmail.com', \Auth::user(), $anketa->verify_code));
        return response()->json(['status'=>'success', 'data'=>null, 'message'=>'Confirm the application via the link in your email']);
    }

    /**
     * Confirm be_partner
     * @bodyParam g-recaptcha-response string Рекапча
     * @bodyParam verify_code string Код из письма
     */

    public function confirm( Request $request)
    {
        $request->validate([
            'verify_code'=>'required|string|max:191',
            'g-recaptcha-response' => 'bail|required|recaptcha',
        ]);

        $anketa = Anketa::where('verify_code','=',$request->verify_code)->first();

        if($anketa != null)
        {
            if($anketa->status == 0)
            {
                $h4 = __('anketa.thanks.h4');
                $text = __('anketa.thanks.text');

                $anketa->status = 1;
                $anketa->save();
                Mail::queue(new SampleMail('pr@c1k.world', __('anketa.mail_code_verify_success.subject'), __('anketa.mail_code_verify_success.text')));
                Mail::queue(new SampleMail($anketa->email, 'Ваша заявка на активацию партнерской программы успешно принята', __('anketa.mail_code_verify_success.text'), 'mail.anketa-verify-ok'));
                return response()->json(['status'=>'success', 'data'=>null, 'message'=>'Confirmation code accepted, await review result']);
            }

        }
        return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Verification code is not valid'], 400);

    }


}
