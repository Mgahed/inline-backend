<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()) {
            return redirect()->route('login')->with('error','please login first as admin');
        }
        if (Auth::user()->user_role != 'admin') {
            Auth::logout();
            return redirect()->route('login')->with('error','Only admins are allowed to login');
        }
        return $next($request);
    }
}
