<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Picks the visitor language: ?lang=xx  >  session  >  cookie  >  browser  >  default.
 * The choice is stored in the session AND a 1-year cookie so it persists.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('site.locales'));
        $locale = $request->query('lang');

        if (! in_array($locale, $available, true)) {
            $locale = session('locale') ?: $request->cookie('locale');
        }
        if (! in_array($locale, $available, true)) {
            $locale = $request->getPreferredLanguage($available) ?: config('site.default_locale');
        }

        if ($request->query('lang') === $locale) {
            session(['locale' => $locale]);
            Cookie::queue('locale', $locale, 60 * 24 * 365);
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        view()->share('dir', config("site.locales.$locale.dir", 'ltr'));

        return $next($request);
    }
}
