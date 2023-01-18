<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class maliManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() ) {
            $role = Role::query()
                ->where('user_id',auth()->user()->id)
                ->where('roles',6)
                ->first();
            if ($role) {
                return $next($request);
            }
            else{
                auth()->logout();
                return redirect()->route('login');
            }
        } else {
            auth()->logout();
            return redirect()->route('login');
        }
    }
}
