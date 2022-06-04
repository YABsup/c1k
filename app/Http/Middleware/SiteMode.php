<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
class SiteMode
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {

        $site_mode_login_url = $request->server('HTTP_ORIGIN').'/site_off_login';

        if( ($request->path() == 'login') && ($request->method() == 'POST') && ($request->header('referer') == $site_mode_login_url) )
        {
            return $next($request);
        }

        if(\Auth::user())
        {
            if (\Auth::user()->role != 'user') {
                return $next($request);
            }
        }

        $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        $userIp = \App\UserIp::where('user_ip','=',$user_ip)->first();
        if( $userIp != null )
        {
            if( $userIp->blocked )
            {
                session()->flash('warning', "Flood detection");
                return redirect('/site_offline');
            }
        }

        $site_mode = Cache::get( 'site_mode', null );

        if($site_mode != null)
        {
            if($site_mode == 'off')
            {
                return redirect('/site_offline');
            }else{
                return $next($request);
            }
        }else{
            return $next($request);
        }
    }
}
