<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccess
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
    if ( ( \Auth::user()->role == 'admin' ) || ( \Auth::user()->role == 'superadmin' ) ) {
      return $next($request);
    }

      \App::abort(404);
    }
}
