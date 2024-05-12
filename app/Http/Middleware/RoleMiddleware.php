<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Check if the user has any of the specified roles
        foreach ($roles as $role) {
            if (auth()->user()->role == $role) {
                return $next($request);
            }
        }

        // If the user does not have any of the specified roles, redirect them to the home page or show an error message
        return redirect()->route('login')->with('error', 'Unauthorized access.');    }
}
