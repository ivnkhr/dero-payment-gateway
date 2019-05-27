<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Closure;

class ValidApiAllowInvoice
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
        if(config('deropay.apiaccess.invoice') != true){
            return response()->json(['error' => 'Invoice endpoint disabled'], 401);
        }
        return $next($request);
    }
}
