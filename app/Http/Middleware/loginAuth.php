<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class loginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        } else {
            if (isset($_COOKIE['user_route'])) {
                return redirect($_COOKIE['user_route']);
            } else {
                auth()->logout();
                return $next($request);
            }
        }
    }
}
