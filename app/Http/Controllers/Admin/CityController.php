<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
        $cityes = City::orderBy( 'active','DESC')->orderBy('code','asc')->with('country','ref_city')->get();

        return view('admin.city.index', compact('cityes'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
        // $client = new \GuzzleHttp\Client();
        //
        //     $request = $client->get('https://www.bestchange.ru/wiki/rates.html');
        //
        // $doc = new \DOMDocument();
        //
        //     libxml_use_internal_errors(true);
        //     $doc->loadHTML( $request->getBody() );
        //     libxml_use_internal_errors(false);
        //
        //
        //     $dom_table_nodelist = $doc->getElementsByTagName('table');
        //     $table_coin = $dom_table_nodelist[1];
        //     $table_cash = $dom_table_nodelist[2];
        //     $table_city = $dom_table_nodelist[3];
        //
        //     $table_row = $table_city->getElementsByTagName('tr');
        //
        //     for($i=1;$i<$table_row->length;$i++)
        //     {
        // 	    $city = new City;
        //     $city->id = $i;
        //     $city->code = $table_row[$i]->firstChild->nodeValue;
        //     $city->name = $table_row[$i]->lastChild->nodeValue;
        //     $city->active = 0;
        //     $city->save();
        //     }

    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(City $city)
    {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\City  $city
    * @return \Illuminate\Http\Response
    */
    public function show(City $city)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\City  $city
    * @return \Illuminate\Http\Response
    */
    public function edit(City $city)
    {
        $data = $city;

        $cityes = City::orderBy( 'active','DESC')->orderBy('name','asc')->where('id','!=',$city->id)->get();
        $cityes[] = new City;
        return view('admin.city.edit', compact('data','cityes'));
    }

    /**
    * Set active
    *
    * @param  \App\City  $city
    * @return \Illuminate\Http\Response
    */
    public function set_active(City $city)
    {
        //REF TO AJAX
        if( isset($_GET['toggle'] ))
        {
            $city->active = $_GET['toggle'];
            $city->save();
	    \App\Pair::where('city_id','=',$city->id)->update(['active'=>$city->active]);



        }
        return redirect('/admin/city');
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\City  $city
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, City $city)
    {
        //
        $city->ref_bid_coef = $request->ref_bid_coef;
        $city->ref_ask_coef = $request->ref_ask_coef;
        $city->ref_city_id = $request->ref_city_id;
        $city->save();
        return redirect('/admin/city');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\City  $city
    * @return \Illuminate\Http\Response
    */
    public function destroy(City $city)
    {
        //
    }
}
