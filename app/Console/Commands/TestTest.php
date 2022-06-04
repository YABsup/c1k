<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Pair;

class TestTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

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
        $pairs = Pair::get();
        $test = [];
        foreach( $pairs as $pair)
        {
            $key_a = $pair->city_id.$pair->base_currency_id.$pair->quote_currency_id;
            $key_b = $pair->city_id.$pair->quote_currency_id.$pair->base_currency_id;

            if(!in_array($key_a, $test) && !in_array($key_b, $test))
            {
                $test[] = $key_a;
            }else{
                $this->info($pair->city_id.'--'.$pair->id);
            }

        }




        echo 'Test:'."\n";
    }
}
