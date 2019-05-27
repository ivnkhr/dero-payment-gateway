<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Closure;

class ValidApiAllowedIP
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

        if(config('deropay.apiaccess.ip') == '*'){
            return $next($request);
        }else{
            $mask = config('deropay.apiaccess.ip');
            $list_of_ips = explode(',',$mask);
            foreach($list_of_ips as $ip){
                if( $request->ip() == $ip ) return $next($request);
            }
        }
        return response()->json(['error' => 'Unauthorized IP'], 401);
    }
}
