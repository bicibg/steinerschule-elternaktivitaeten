<?php

namespace App\Http\Middleware;

use App\Models\BulletinPost;
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

        $helpRequest = BulletinPost::where('slug', $slug)->first();

        if (!$helpRequest || $helpRequest->edit_token !== $token) {
            abort(403, 'Zugriff verweigert. Ungültiger Bearbeitungstoken.');
        }

        $request->attributes->set('helpRequest', $helpRequest);

        return $next($request);
    }
}
