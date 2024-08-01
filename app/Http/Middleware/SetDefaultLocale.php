<?php
// V58
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class SetDefaultLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('app_locale', config('app.locale'));
        // dd($locale);

        App::setLocale($locale);
        if(is_null(Session::get('app_locale'))){
            Session::put('app_locale', $locale);
        } 
        // dd(Session::get('app_locale'));
        return $next($request);
    }
}
