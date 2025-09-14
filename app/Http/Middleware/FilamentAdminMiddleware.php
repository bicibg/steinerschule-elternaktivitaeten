<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for login page
        if ($request->routeIs('filament.admin.auth.login')) {
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        return $next($request);
    }
}
