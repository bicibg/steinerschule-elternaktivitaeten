<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Strict Transport Security (HTTPS only in production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Referrer Policy - don't leak URLs to external sites
        $response->headers->set('Referrer-Policy', 'same-origin');

        // Permissions Policy - disable unnecessary features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy - prevent XSS and data injection
        // Allow localhost for development (Vite)
        $csp = "default-src 'self' http://localhost:* http://127.0.0.1:*; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*; " .
               "style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:*; " .
               "img-src 'self' data: https: http://localhost:* http://127.0.0.1:*; " .
               "font-src 'self' data: http://localhost:* http://127.0.0.1:*; " .
               "connect-src 'self' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*; " .
               "frame-ancestors 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}