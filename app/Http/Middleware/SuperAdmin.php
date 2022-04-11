<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class SuperAdmin
{

    public function handle($request, Closure $next)
    {
         if (Auth::user() &&  Auth::user()->role_id == 18) {
                return $next($request);
         }

        return response()->json('Unauthorized!, You are not a super admin', 403);
    }
}
