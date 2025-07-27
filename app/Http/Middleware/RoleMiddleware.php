<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // print_r(Auth::user()->user_role);
        // print_r($role);
        // exit;
        if (!Auth::check() || Auth::user()->user_role !== $role) {
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}