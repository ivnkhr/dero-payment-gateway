<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Closure;

class ValidApiAllowData
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
        if(config('deropay.apiaccess.data') != true){
            //abort(405, 'Endpoint disabled.');
            return response()->json(['error' => 'Data endpoint disabled'], 401);
        }
        return $next($request);
    }
}
