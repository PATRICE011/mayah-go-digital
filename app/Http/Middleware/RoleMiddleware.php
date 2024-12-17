<?php

// app/Http/Middleware/RoleMiddleware.php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated and has one of the required roles
        if (Auth::check() && in_array(Auth::user()->role_id, $roles)) {
            return $next($request);
        }

        // Redirect if the user does not have the required role
        return redirect('/')->with('error', 'Unauthorized Access');
    }
}
// MAHSHOSHOW DAPAT TOHHHH

