<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CardVerification;
use App\AccountVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        return view('admin.users.index', compact('users'));
    }

    public function index_statistics()
    {
        $users = User::with('referals','orders')->orderBy('id','DESC')->get();
        return view('admin.users.index_statistics', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'new_name' => 'min:3|max:150',
            'new_email' => 'required|email|max:150|unique:users,email',
            'new_password' => 'required|min:6|max:150'
        ]);

        $user = User::create([
            'name' => $request->get('new_name'),
            'email' => $request->get('new_email'),
            'password' => Hash::make($request->get('new_password')),
        ]);

        session()->flash('success', "Пользователь успешно создан");

        return route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $data = array();
        $referals = User::where('referer_id', $user->id)->get();
        $orders = \App\Exchange::where('user_id',$user->id)->where('status_id','!=', '4')->orderBy('status_id', 'ASC')->orderBy('updated_at', 'DESC')->with('pair','user','user.referer','pair.city','status','pair.base_currency','pair.quote_currency')->get();
        $referalOrders = \App\Exchange::where('ref_code_id',$user->id)->where('status_id','!=', '4')->orderBy('status_id', 'ASC')->orderBy('updated_at', 'DESC')->with('pair','user','user.referer','pair.city','status','pair.base_currency','pair.quote_currency')->get();

        return view('admin.users.show', compact('user', 'referals', 'orders', 'referalOrders'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'min:3|max:150',
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);
        if($request->balance != $user->balance)
        {
          //TODO logging
          $log = new \App\Logging;
          $log->user_id = \Auth::user()->id;
          $log->event = 'change_user_balance';
          $log->desc = 'OLD: '.$user->balance .' NEW: '. $request->balance;
          $log->save();
          $user->balance = $request->balance;
        }
        if($request->verified != $user->verified)
        {
          //TODO logging
          $log = new \App\Logging;
          $log->user_id = \Auth::user()->id;
          $log->event = 'verify_user';
          $log->desc = 'OLD: '.$user->verified .' NEW: '. $request->verified;
          $log->save();

          if( $request->verified == true )
          {
              AccountVerification::whereUserId($user->id)->update([
                  'approved'=>true,
                  'admin_id'=> $log->user_id,
              ]);
              CardVerification::whereUserId($user->id)->update([
                  'approved'=>true,
                  'admin_id'=> $log->user_id,
              ]);
          }

          $user->verified = $request->verified;
        }


        $user->update($request->only([
            'name',
            'email',
            'verified',
        ]));

        session()->flash('success', "Пользователь #$user->id успешно обновлен");

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(auth()->id() === $user->id) {
            session()->flash('warning', "Вы не можете удалить собственный аккаунт");

            return back();
        }
        if($user->id === 4) {
            session()->flash('warning', "Ошибка при удалении пользователя");

            return back();
        }
        //try {
            //$user->delete();
        //} catch (\Exception $e) {
            session()->flash('warning', "Ошибка при удалении пользователя");

            return back();
        //}

        session()->flash('success', "Пользователь #$user->id успешно удалён");

        return back();
    }
}
