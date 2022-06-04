<?php

namespace AlogicProjects\Email2fa\Http\Middleware;

use AlogicProjects\Email2fa\Email2fa;

class Authorize
{

    public function handle($request, $next)
    {
        return resolve(Email2fa::class)->authorize($request) ? $next($request) : abort(403);
    }
}
