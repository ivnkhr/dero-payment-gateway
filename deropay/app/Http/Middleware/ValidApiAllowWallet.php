<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Closure;

class ValidApiAllowWallet
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
        if(config('deropay.apiaccess.wallet') != true){
            return response()->json(['error' => 'Wallet endpoint disabled'], 401);
        }
        return $next($request);
    }
}
