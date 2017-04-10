<?php

namespace App\Http\Middleware;

use Closure;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  Role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if($request->user()->role == $role){
            return $next($request);
        }
        return response()->json('Forbidden Access',401);
    }
}
