<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;

class Investor
{
    public function handle($request, Closure $next)
    {
         if (Auth::user() &&  Auth::user()->role_id == 9) {
                return $next($request);
         }

        return response()->json('Unauthorized!, You are not an investor', 403);
    }
}
