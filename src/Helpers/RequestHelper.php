<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Quanticheart\Laravel\Helpers;

use Closure;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class RequestHelper
{
    /**
     * @param $requestDispatch
     * @param Request|null $request
     * @return mixed
     */
    static function setHeaders($requestDispatch, Request $request = null)
    {
        if (isset($request)) {
            $requestDispatch->headers->add($request->headers->all());
        }
        $requestDispatch->headers->set('X-localization', App::getLocale());
        $requestDispatch->headers->set('api-token', env("TOKEN_WEB"));
        return $requestDispatch;
    }

    /**
     * @param Request|null $request
     * @param null $validations
     * @return Request|null
     */
    static function validation(Request $request = null, $validations = null): ?Request
    {
        if (isset($validations)) {
            $request->validate($validations);
            return $request;
        }
        return null;
    }

    /** @noinspection PhpRedundantCatchClauseInspection
     * @noinspection PhpUnusedLocalVariableInspection
     */
    static function tryCatch(Closure $block)
    {
        $errors = '';
        try {
            return $block();
        } catch (ValidationException | GuzzleException $e) {
            foreach ($e->errors() as $key => $value) {
                $errors .= $value[0];
            }
        }
        return null;
    }
}
