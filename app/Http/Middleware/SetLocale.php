<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;

class SetLocale
{
  /**
  *
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle($request, Closure $next)
  {
    if (isset(\Auth::user()->locale)) {
      App::setLocale(\Auth::user()->locale);
    }else{
      if (Session::has('locale')) {
        $locale = Session::get('locale', Config::get('app.locale'));
      } else {
        $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        if ( !in_array($locale,array('ru','en','uk') ) ) {
          $locale = 'ru';
        }
        Session::put('locale',$locale);
        Session::save();
      }
      App::setLocale($locale);
    }
    return $next($request);
  }

}
