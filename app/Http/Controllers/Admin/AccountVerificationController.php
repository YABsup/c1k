<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\AccountVerification;
use Illuminate\Http\Request;

class AccountVerificationController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
        $data = AccountVerification::with('user','admin')->orderBy('id','desc')->get();

        return view('admin.account_verification.index', compact('data'));
    }




    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\AccountVerification  $accountVerification
    * @return \Illuminate\Http\Response
    */
    public function destroy(AccountVerification $accountVerification)
    {
        //
        $accountVerification->user->verified_send = false;
        $accountVerification->admin_who_deleted = request()->user()->id;
        $accountVerification->delete();
        session()->flash('success', "Ok");
        return redirect('admin/account_verifications');

    }
}
