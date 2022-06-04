<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Referal;
use Illuminate\Http\Request;

class ReferalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //
      $data = array();
      $data = \App\User::with('referer')->get();
      return view('admin.referals.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Referal  $referal
     * @return \Illuminate\Http\Response
     */
    public function show(Referal $referal)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Referal  $referal
     * @return \Illuminate\Http\Response
     */
    public function edit(Referal $referal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Referal  $referal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Referal $referal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Referal  $referal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Referal $referal)
    {
        //
    }
}
