<?php

namespace App\Http\Middleware;

use Closure;
use Api;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->guard('api')->user()){
            return $next($request);
        }

        return Api::apiRespond(401);
    }
}
