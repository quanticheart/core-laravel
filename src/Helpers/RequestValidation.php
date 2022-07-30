<?php

namespace Quanticheart\Laravel\Helpers;

use Closure;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Quanticheart\Laravel\Constants\ResponseCodes;

class RequestValidation
{
    static function verifyResponse($rawResponse, Closure $block)
    {
        try {
            if ($rawResponse->getData()->status) {
                return responseOk($rawResponse->getData()->msg, $block());
            }
        } catch (Exception $e) {
            return responseExceptionError($e);
        }
        return $rawResponse;
    }

    /** @noinspection PhpRedundantCatchClauseInspection */
    static function tryCatch(Closure $block)
    {
        DB::beginTransaction();
        try {
            $response = $block();
            DB::commit();
            return $response;
        } catch (ValidationException $e) {
            DB::rollBack();
            return responseValidationError($e);
        } catch (QueryException $e) {
            DB::rollBack();
            return responseQueryError($e);
        } catch (Exception $e) {
            DB::rollBack();
            return responseExceptionError($e);
        }
    }

    static function tryCatchGet(Closure $block)
    {
        try {
            $response = $block();
            if ($response === null) {
                return null;
            } else {
                return $response;
            }
        } catch (QueryException | Exception $e) {
            return null;
        }
    }

    static function tryCatchPost(Closure $block)
    {
        try {
            $response = $block();
            if ($response === null) {
                return null;
            } else {
                return $response;
            }
        } catch (ValidationException $e) {
            return responseValidationError($e);
        } catch (QueryException | Exception $e) {
            return null;
        }
    }

    static function tryCatchAdm(Request $request, Closure $block)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->auth)->get('level')->first();
            if ($user->level <= 3) {
                return responseError('user not have authorization', ResponseCodes::RESPONSE_CODE_LOGIN_USER_NOT_HAVE_ACCESS, Response::HTTP_UNAUTHORIZED);
            } else {
                $response = $block();
                DB::commit();
                return $response;
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            return responseValidationError($e);
        } catch (QueryException $e) {
            DB::rollBack();
            return responseQueryError($e);
        } catch (Exception $e) {
            DB::rollBack();
            return responseExceptionError($e);
        }
    }

    static function requiredParams(Closure $block)
    {
        try {
            return $block();
        } catch (ValidationException $e) {
            return responseValidationError($e);
        } catch (Exception $e) {
            return responseExceptionError($e);
        }
    }
}

