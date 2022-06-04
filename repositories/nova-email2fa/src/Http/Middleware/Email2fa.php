<?php

namespace AlogicProjects\Email2fa\Http\Middleware;

use Closure;

class Email2fa
{
    public function handle($request, Closure $next)
    {

        if ( $request->path() === 'alogic/email2fa/authenticate' ) {
            if( ($request->user()->email_2fa == null) || ($request->user()->email2fa_enabled == false)  )
            {
                return response()->redirectTo(config('nova.path'));
            }
            return $next($request);
        }

        if ( auth()->guest() || ( $request->user()->email_2fa == null ) || ($request->user()->email2fa_enabled == false) ) {
            return $next($request);
        }

        return response(view('alogicemail2fa::authenticate'));
    }
}
