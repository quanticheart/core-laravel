<?php

namespace Quanticheart\Laravel\Helpers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Quanticheart\Laravel\Constants\ResponseCodes;
use Quanticheart\Laravel\Helpers\Base\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('responseOk')) {
    /**
     * @param string $msg
     * @param null $data
     * @param int $code
     * @return JsonResponse
     */
    function responseOk(string $msg, $data = null, int $code = -1)
    {
        $create = new BaseResponse();
        $response = $create->ok($msg, $data, $code);
        return response()->json($response, Response::HTTP_OK);
    }
}

if (!function_exists('responseError')) {
    /**
     * @param string $msg
     * @param int $code
     * @param int $serverStatus
     * @param null $data
     * @return JsonResponse
     */
    function responseError(string $msg, int $code, int $serverStatus, $data = null)
    {
        $create = new BaseResponse();
        $response = $create->error($msg, $code, $data);
        return response()->json($response, $serverStatus);
    }
}

if (!function_exists('responseTokenError')) {
    /**
     * @param string $msg
     * @param int $code
     * @param null $data
     * @return JsonResponse
     */
    function responseTokenError(string $msg, int $code = -1, $data = null)
    {
        $create = new BaseResponse();
        $response = $create->error($msg, $code, $data);
        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }
}

if (!function_exists('responseExceptionError')) {
    /**
     * @param string $msg
     * @param null $data
     * @return JsonResponse
     */
    function responseExceptionError(string $msg, $data = null)
    {
        $create = new BaseResponse();
        $response = $create->error($msg, 110, $data);
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

if (!function_exists('responseValidationError')) {
    /**
     * @param ValidationException $e
     * @return JsonResponse
     */
    function responseValidationError(ValidationException $e)
    {
        $errors = '';
        foreach ($e->errors() as $key => $value) {
            if ($key == 0)
                $errors = $value[0];
        }
        $create = new BaseResponse();
        $response = $create->error($errors, ResponseCodes::RESPONSE_CODE_VALIDATE_FAILED);
        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }
}

if (!function_exists('responseQueryError')) {
    /**
     * @param QueryException $e
     * @return JsonResponse
     */
    function responseQueryError(QueryException $e)
    {
        $create = new BaseResponse();
        $response = $create->error("Erro ao conectar com o servidor" . (config('app.debug') ? " ::::::::::: " . $e : ""), ResponseCodes::RESPONSE_CODE_QUERY_FAILED);
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

if (!function_exists('responseCsrfError')) {
    /**
     * @param TokenMismatchException $e
     * @return array
     */
    function responseCsrfError(TokenMismatchException $e)
    {
        $create = new BaseResponse();
        return $create->error("Oops! Seems you couldn't submit form for a long time. Please try again." . (config('app.debug') ? " ::::::::::: " . $e : ""), ResponseCodes::RESPONSE_CODE_CSRF_TOKEN_OUT);
    }
}
