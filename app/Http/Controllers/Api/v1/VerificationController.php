<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountVerification;
use App\CardVerification;
use App\Exchange;
use App\User;

use Illuminate\Support\Facades\Password;

/**
* @group Verification
*/

class VerificationController extends Controller
{
    /**
    * Card
    * Верификация карты
    *
    * @bodyParam photos image required Массив фото макс 5 штук
    * @bodyParam first_name string required Имя
    * @bodyParam tel string required Телефон
    * @bodyParam email string required Email
    * @bodyParam card string required Card
    *
    */
    public function card( Request $request )
    {
        $request->validate([
            'photos'=>'required|array|max:5',
            //'photos.*'=>'required|image|max:10000',
            'photos.*'=>'required| mimes:jpeg,jpg,png|max:10000',
            'first_name'=>'required|string|max:191',
            'tel'=>'required|string|max:191',
            'email'=>'required|string|max:191',
            'card'=>'required|string|max:191',
        ]);

        $user = $request->user();
        $data = [];

        $photos = $request->file('photos');

        if( !CardVerification::whereUserId($user->id)->first() )
        {
            if( $photos != null)
            {
                $user->verified_send = 1;

		if( $user->phone == null )
		{
		    $user->phone = $request->tel;
		}

                $user->save();

                $verification = new CardVerification;
                $verification->user_id = $user->id;
                $verification->card = $request->card;
                $verification->save();


                $sample_mail_text['first_name'] = $request->first_name;
                $sample_mail_text['tel'] = $request->tel;
                $sample_mail_text['email'] = $request->email;
                $sample_mail_text['card'] = $request->card;

                $mail = new \App\Mail\SampleMail('c1kworldex@gmail.com', 'New card verifiation', array('request'=>$sample_mail_text, 'user'=>$user ), 'mail.card_verifi');

                foreach( $photos as $photo)
                {
                    $mail->attach($photo->getRealPath(), array(
                        'as' => $photo->getClientOriginalName(), // If you want you can chnage original name to custom name
                        'mime' => $photo->getMimeType())
                    );
                }

                \Mail::queue($mail, $user);
                
            }
        }


        return response()->json(['status'=>'success','data'=>null]);
    }

    /**
    * Account
    * Верификация аккаунта Skrill/Netteler
    *
    * @bodyParam photos image required Массив фото макс 5 штук
    * @bodyParam first_name string required Имя
    * @bodyParam tel string required Телефон
    * @bodyParam email string required Email
    * @bodyParam account string required Account
    *
    */
    public function account( Request $request )
    {

        $request->validate([
            'photos'=>'required|array|max:5',
            //'photos.*'=>'required|image|max:10000',
            'photos.*'=>'required| mimes:jpeg,jpg,png|max:10000',
            //'first_name'=>'required|string|max:191',
            //'tel'=>'required|string|max:191',
            //'email'=>'required|string|max:191',
            //'account'=>'required|string|max:191',
        ]);

        $user = $request->user();
        $data = [];

        $photos = $request->file('photos');

        if( !AccountVerification::whereUserId( $user->id )->first() )
        {
            if( $photos != null)
            {
                $user->verified_send = 1;
                $user->save();

                $verification = new AccountVerification;
                $verification->user_id = $user->id;
                $verification->account = $request->account;
                $verification->save();


                $sample_mail_text['first_name'] = $user->name;
                $sample_mail_text['tel'] = $request->tel;
                $sample_mail_text['email'] = $request->email;
                $sample_mail_text['card'] = $request->account;

                $mail = new \App\Mail\SampleMail('c1kworldex@gmail.com', 'New skrill/NETELLER verifiation', array('request'=>$sample_mail_text, 'user'=>$user ), 'mail.skrill_verifi');

                foreach( $photos as $photo)
                {
                    $mail->attach($photo->getRealPath(), array(
                        'as' => $photo->getClientOriginalName(), // If you want you can chnage original name to custom name
                        'mime' => $photo->getMimeType())
                    );
                }

                \Mail::queue($mail, $user);

            }
        }


        return response()->json(['status'=>'success','data'=>null]);
    }

}
