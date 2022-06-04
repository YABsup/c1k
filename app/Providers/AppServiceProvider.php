<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//use App\Validators\ReCaptcha;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        //
    }

    /**
    * Bootstrap any application services.
    * @param ReCaptcha
    * @return void
    */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');

        if( config('app.env') == 'alogic' )
        {
            DB::listen(function($query) {
                Log::debug(
                    $query->time.' sec : '.( request()->path().': '.$query->sql),
                    $query->bindings,
                    $query->time
                );
            });
        }
    }

}
