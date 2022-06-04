<?php

namespace App\Http\Middleware;

use Closure;

class AdminToFactor
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
        logger('4');
        if( $request->user()->to_factor != null )
        {
            return response()->view('admin.to_factor');
        }
        return $next($request);
    }
}
