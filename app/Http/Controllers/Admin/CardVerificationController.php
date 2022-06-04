<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CardVerification;
use Illuminate\Http\Request;

class CardVerificationController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
        $data = CardVerification::with('user','admin')->orderBy('id','desc')->get();

        return view('admin.card_verification.index', compact('data'));
    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\CardVerification  $cardVerification
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, CardVerification $cardVerification)
    {
        //

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\CardVerification  $cardVerification
    * @return \Illuminate\Http\Response
    */
    public function destroy(CardVerification $cardVerification)
    {
        //
        $cardVerification->user->verified_send = false;
        $cardVerification->admin_who_deleted = request()->user()->id;
        $cardVerification->delete();
        session()->flash('success', "Ok");
        return redirect('admin/card_verifications');
    }
}
