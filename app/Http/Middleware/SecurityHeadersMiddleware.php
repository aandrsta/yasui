<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Determine if we should redirect to HTTPS
        $enforceHttps = env('ENFORCE_HTTPS', env('APP_ENV') === 'production');
        
        if ($enforceHttps && !$request->secure()) {
            $response = redirect()->secure($request->getRequestUri());
        } else {
            // 2. Generate and store CSP nonce if it does not exist yet
            if (!app()->bound('csp-nonce')) {
                app()->instance('csp-nonce', Str::random(32));
            }
            
            // 3. Force session cookie to be secure if request is secure
            if ($request->secure()) {
                config(['session.secure' => true]);
            }

            $response = $next($request);
        }

        // 4. Add security headers to the response (both redirect and normal responses)
        if ($response instanceof Response) {
            $isFilamentOrLivewire = $request->is('admin*') || $request->is('livewire*');

            if ($isFilamentOrLivewire) {
                // Relaxed CSP for Filament and Livewire to prevent breaking admin dashboards
                $cspDirectives = [
                    "default-src 'self'",
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://www.googletagmanager.com https://app.sandbox.midtrans.com https://app.midtrans.com",
                    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
                    "img-src 'self' data: https://www.google-analytics.com https://*.google-analytics.com",
                    "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
                    "connect-src 'self' https://app.sandbox.midtrans.com https://app.midtrans.com https://www.google-analytics.com https://*.google-analytics.com",
                    "frame-src 'self' https://app.sandbox.midtrans.com https://app.midtrans.com",
                    "object-src 'none'",
                    "base-uri 'self'",
                    "form-action 'self'",
                    "upgrade-insecure-requests"
                ];
            } else {
                // Strict Nonce-based CSP for public front-end (Removed 'unsafe-eval')
                $nonce = app()->bound('csp-nonce') ? app('csp-nonce') : '';
                $cspDirectives = [
                    "default-src 'self'",
                    "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://www.googletagmanager.com https://app.sandbox.midtrans.com https://app.midtrans.com",
                    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
                    "img-src 'self' data: https://www.google-analytics.com https://*.google-analytics.com",
                    "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
                    "connect-src 'self' https://app.sandbox.midtrans.com https://app.midtrans.com https://www.google-analytics.com https://*.google-analytics.com",
                    "frame-src 'self' https://app.sandbox.midtrans.com https://app.midtrans.com",
                    "object-src 'none'",
                    "base-uri 'self'",
                    "form-action 'self'",
                    "frame-ancestors 'none'",
                    "upgrade-insecure-requests"
                ];
            }

            $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            // Send HSTS header (only over HTTPS, or if in production, or if redirecting to HTTPS)
            if ($request->secure() || env('APP_ENV') === 'production' || ($enforceHttps && !$request->secure())) {
                $maxAge = env('HSTS_MAX_AGE', 31536000);
                $response->headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains; preload");
            }

            $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }

        return $response;
    }
}
