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
        if (Auth::check()) {
            \Log::info('Middleware Role Check', [
                'user_id' => Auth::user()->id,
                'role_id' => Auth::user()->role_id,
                'expected_roles' => $roles
            ]);
        
            if (in_array(Auth::user()->role_id, $roles)) {
                return $next($request);
            }
        }
        
        \Log::warning('Unauthorized Admin Access Attempt', [
            'user_id' => Auth::user()->id ?? 'Guest',
            'role_id' => Auth::user()->role_id ?? 'Guest'
        ]);
        
        return redirect()->route('/')->with('error', 'Unauthorized Access');
        
    }
}


