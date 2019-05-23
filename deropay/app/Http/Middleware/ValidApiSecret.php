<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Closure;

class ValidApiSecret
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
        //var_dump(env('DEROPAY_API_SECRET'));
        //die();
        if($request->header('Api-Secret') != env('DEROPAY_API_SECRET')){
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
