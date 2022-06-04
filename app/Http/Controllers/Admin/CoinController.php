<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Coin;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $coins = Coin::orderBy('active', 'DESC')->orderBy('code','ASC');
        if( \Auth::user()->email == 'inkovalexey@gmail.com')
        {
            $coins = $coins->whereIn('id', [89,90,145,149,158]);
        }
        $coins = $coins->get();


        return view('admin.coin.index', compact('coins'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $client = new \GuzzleHttp\Client();

        $request = $client->get('https://www.bestchange.ru/wiki/rates.html');

        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML( $request->getBody() );
        libxml_use_internal_errors(false);


        $dom_table_nodelist = $doc->getElementsByTagName('table');
        $table_coin = $dom_table_nodelist[1];
        $table_cash = $dom_table_nodelist[2];
        $table_city = $dom_table_nodelist[3];

        $table_row = $table_coin->getElementsByTagName('tr');

        for($i=1;$i<$table_row->length;$i++)
        {
            $coin = new Coin;
            $coin->id = $i;
            $coin->code = $table_row[$i]->firstChild->nodeValue;
            $coin->name = $table_row[$i]->lastChild->nodeValue;
            $coin->active = 0;
            $coin->save();
        }

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
     * @param  \App\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function show(Coin $coin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function edit(Coin $coin)
    {
        //
	//REF TO AJAX
        if( isset($_GET['toggle'] ))
        {
            $coin->active = $_GET['toggle'];
            $coin->save();
        }
        return redirect('/admin/coin');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coin $coin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coin  $coin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coin $coin)
    {
        //
    }
}
