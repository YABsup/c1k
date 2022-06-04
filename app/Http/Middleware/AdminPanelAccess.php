<?php

namespace App\Http\Middleware;

use Closure;

class AdminPanelAccess
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
    if ( ( \Auth::user()->role != 'user' ) && ( \Auth::user()->role != 'blogger' ) ) {
      return $next($request);
    }

      \App::abort(404);
    }
}
