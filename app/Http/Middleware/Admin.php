<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle($request, Closure $next)
    {
         if (Auth::user() &&  Auth::user()->role_id == 1 || Auth::user()->role_id == 18) {
                return $next($request);
         }

        return response()->json('Unauthorized!, You are not an admin', 403);
    }
}
