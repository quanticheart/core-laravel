<?php

namespace Quanticheart\Laravel\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check header request and determine localization
        $local = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : (App::getLocale() !== null ? App::getLocale() : "en");
        app()->setLocale($local);
        return $next($request);
    }
}
