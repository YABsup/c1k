<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
  * The Artisan commands provided by your application.
  *
  * @var array
  */
  protected $commands = [
    //
  ];

  /**
  * Define the application's command schedule.
  *
  * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
  * @return void
  */
  protected function schedule(Schedule $schedule)
  {
    // $schedule->command('inspire')
    //          ->hourly();
    $schedule->call('App\Http\Controllers\Rates\ReferenceRates@update_privat24')->everyMinute();
//    $schedule->call('App\Http\Controllers\Rates\ReferenceRates@update_unexbank')->everyMinute();
    $schedule->call('App\Http\Controllers\Rates\ForexCourseController@create')->everyMinute();

    $schedule->command('parse:huobi')->everyMinute();

    // $schedule->call('App\Http\Controllers\BinanceCourseController@create')->everyMinute();
    // $schedule->call('App\Http\Controllers\BitfinexCourseController@create')->everyMinute();
    // $schedule->call('App\Http\Controllers\BitstampCourseController@create')->everyMinute();
    // $schedule->call('App\Http\Controllers\BittrexCourseController@create')->everyMinute();

    $schedule->call('App\Http\Controllers\Rates\ReferenceRates@clear_old_rates')->hourly();
    //$schedule->call('App\PurePHP::get_bestchange')->everyMinute();
  }

  /**
  * Register the commands for the application.
  *
  * @return void
  */
  protected function commands()
  {
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
  }
}
