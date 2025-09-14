<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEditToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $token = $request->query('token');

        if (!$slug || !$token) {
            abort(403, 'Zugriff verweigert. Kein gültiger Bearbeitungstoken.');
        }

        $activity = Activity::where('slug', $slug)->first();

        if (!$activity || $activity->edit_token !== $token) {
            abort(403, 'Zugriff verweigert. Ungültiger Bearbeitungstoken.');
        }

        $request->attributes->set('activity', $activity);

        return $next($request);
    }
}
