<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    //this lang middleware that recive from request header the lang and store in Locale
    public function handle(Request $request, Closure $next): Response
    {
        $headerLanguage = $request->header("lang");
        $acceptedLanguages = ['en', 'ar'];
        if (isset($headerLanguage) && in_array($headerLanguage, $acceptedLanguages)) {
            App::setLocale($headerLanguage);
            Carbon::setLocale($headerLanguage);
        } else {
            App::setLocale('en');
            Carbon::setLocale('en');
        }
        return $next($request);
    }
}