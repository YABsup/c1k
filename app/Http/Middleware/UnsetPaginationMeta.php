<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class UnsetPaginationMeta
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'per_page'=>'integer|min:1|max:100',
            'page'=>'integer|min:0|max:1000',
        ]);

        if( $request->lang == 'ua' )
        {
            $lang = 'ua';
        }elseif( $request->lang == 'en' )
        {
            $lang = 'en';
        }else{
            $lang = 'ru';

        }
        $request->merge([
            'lang' => $lang,
        ]);


        $response = $next($request);

        if( $response instanceof JsonResponse )
        {
            $data = $response->getData(true);

            if (isset($data['links'])) {
                unset($data['links']);
            }
            if (isset($data['meta'])) {
                unset($data['meta']);
            }

            $response->setData($data);
        }
        return $response;
    }
}
