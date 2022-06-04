<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseCitiesFromBest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //



         $client = new \GuzzleHttp\Client();
        //
             $request = $client->get('https://www.bestchange.ru/wiki/rates.html');
        //
         $doc = new \DOMDocument();
        //
             libxml_use_internal_errors(true);
             $doc->loadHTML( $request->getBody() );
             libxml_use_internal_errors(false);
        //
        //
             $dom_table_nodelist = $doc->getElementsByTagName('table');
             $table_coin = $dom_table_nodelist[1];
             $table_cash = $dom_table_nodelist[2];
             $table_city = $dom_table_nodelist[3];
        //
             $table_row = $table_city->getElementsByTagName('tr');
        //
             for($i=1;$i<$table_row->length;$i++)
             {
                 $city_id =  $i;
                 $city_name = $table_row[$i]->lastChild->nodeValue;
                 $city_code = $table_row[$i]->lastChild->nodeValue;
                 $city = \App\City::whereCode($city_code)->first();
                 if( $city != null )
                 {
                     echo $city_name.'',$city->name."\n";
                 }
        // 	    $city = new City;
        //     $city->id = $i;
        //     $city->code = $table_row[$i]->firstChild->nodeValue;
        //     $city->name = $table_row[$i]->lastChild->nodeValue;
        //     $city->active = 0;
        //     $city->save();
             }






    }
}
