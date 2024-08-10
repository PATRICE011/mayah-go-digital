<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->is_admin) {
            return $next($request);
        }

        // return redirect('/'); // Redirect if not an admin
        return redirect()->route('home.login')
                     ->withErrors(['error' => 'Invalid credentials.']);
    }
}
