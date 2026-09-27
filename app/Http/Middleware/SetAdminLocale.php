<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the signed-in admin's preferred_locale (fallback: app locale) to the Filament panel.
 * Filament derives dir="rtl" from the active locale, so ur/fa render right-to-left.
 */
class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->user()?->preferred_locale;

        if (is_string($locale) && in_array($locale, config('app.locales'), true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
