<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Reserv;
use Illuminate\Http\Request;

class ReservController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {


        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            $reservs = \App\Reserv::whereIn('coin_id',[89,90,145,149,158])->get();
        }else{
            $reservs = \App\Reserv::All();
        }


        return view('admin.reserv.index', compact('reservs'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //TODO REF to belongsTo
        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            $coins = \App\Coin::whereIn('id', [89,90,145,149,158]);
        }else{
            $coins = \App\Coin::All();
        }

        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            $reservs = \App\Reserv::whereIn('coin_id',[89,90,145,149,158])->get();
        }else{
            $reservs = \App\Reserv::All();
        }

        return view('admin.reserv.create', compact('reservs','coins'));
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
        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            if( in_array($request->coin_id, [89,90,145,149,158]) )
            {
                session()->flash('warning', "У вас нету доступа");
                return back();
            }
        }

        if( \App\Reserv::where('coin_id','=',$request->coin_id)->count() != 0 )
        {
            session()->flash('warning', "Эта валюта уже есть");
            return back();
        }

        $reserv = new Reserv;
        $reserv->coin_id  = $request->coin_id;
        $reserv->amount = $request->amount;
        $reserv->save();

        session()->flash('success', "Резерв создан");

        return redirect('/admin/reserv');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Reserv  $reserv
    * @return \Illuminate\Http\Response
    */
    public function show(Reserv $reserv)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Reserv  $reserv
    * @return \Illuminate\Http\Response
    */
    public function edit(Reserv $reserv)
    {
        //
        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            if( in_array($reserv->coin_id, [89,90,145,149,158]) )
            {
                session()->flash('warning', "У вас нету доступа");
                return back();
            }
        }

        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            $coins = \App\Coin::whereIn('id', [89,90,145,149,158]);
        }else{
            $coins = \App\Coin::All();
        }
        //$reservs = \App\Reserv::All();

        return view('admin.reserv.edit', compact('reserv','coins'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Reserv  $reserv
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Reserv $reserv)
    {
        //
        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            if( in_array($reserv->coin_id, [89,90,145,149,158]) )
            {
                session()->flash('warning', "У вас нету доступа");
                return back();
            }
        }
        $reserv->amount = $request->amount;
        $reserv->save();
        session()->flash('success', "Ok");

        return redirect('/admin/reserv');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Reserv  $reserv
    * @return \Illuminate\Http\Response
    */
    public function destroy(Reserv $reserv)
    {
        //
    }
}
