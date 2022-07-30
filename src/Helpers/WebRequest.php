<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Quanticheart\Laravel\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WebRequest
{
    static function get($route, Request $request = null, $validations = null)
    {
        return RequestHelper::tryCatch(function () use ($route, $request, $validations) {
            RequestHelper::validation($request, $validations);
            $requestDispatch = Request::create("/api/" . $route, 'GET', $request ? $request->all() : []);
            $requestDispatch = RequestHelper::setHeaders($requestDispatch, $request);
            return json_decode(Route::dispatch($requestDispatch)->getContent());
        });
    }

    static function post($route, Request $request = null, $validations = null)
    {
        return RequestHelper::tryCatch(function () use ($route, $request, $validations) {
            RequestHelper::validation($request, $validations);
            $requestDispatch = Request::create("/api/" . $route, 'POST', $request ? $request->all() : []);
            $requestDispatch = RequestHelper::setHeaders($requestDispatch, $request);
            return json_decode(Route::dispatch($requestDispatch)->getContent());
        });
    }
}

