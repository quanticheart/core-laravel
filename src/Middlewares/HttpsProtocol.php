<?php

namespace Quanticheart\Laravel\Middlewares;

use Closure;

class HttpsProtocol
{

    public function handle($request, Closure $next)
    {
        if (!env("APP_DEBUG"))
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }

        return $next($request);
    }
}
