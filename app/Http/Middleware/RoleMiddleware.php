<?php

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
        // Ensure user is authenticated
        if (Auth::check()) {
            // Check if the user's role_id matches any of the passed roles
            if (in_array(Auth::user()->role_id, $roles)) {
                return $next($request); // Proceed to the next request
            }
        }

        // Redirect if the user doesn't have the required role
        return redirect('/')->with('error', 'Unauthorized Access');
    }
}

