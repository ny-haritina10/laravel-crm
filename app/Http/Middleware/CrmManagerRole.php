<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CrmManagerRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('roles') || !in_array('ROLE_MANAGER', session('roles'))) {
            return redirect()->route('dashboard')->with('error', 'You do not have manager permissions');
        }

        return $next($request);
    }
}