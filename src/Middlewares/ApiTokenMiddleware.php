<?php

namespace Quanticheart\Laravel\Middlewares;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Quanticheart\Laravel\Constants\ResponseCodes;
use Quanticheart\Laravel\Jenssegers\Agent\Agent;
use Quanticheart\Laravel\Models\ApiToken\ApiToken;
use Quanticheart\Laravel\Models\ApiToken\ApiTokenFail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function Quanticheart\Laravel\Helpers\responseError;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->header('Api-Token');
        if (isset($apiToken)) {
            $verifyToken = ApiToken::where("token", $apiToken)
                ->with('platformDetails')
                ->first();
            if (isset($verifyToken)) {
                if ($verifyToken->active) {
                    return $this->verifyTokenType($request, $next, $apiToken, $verifyToken->platformDetails->name);
                } else {
                    return $this->returnErrorTokenBlocked($request, $apiToken);
                }
            } else {
                return $this->returnErrorTokenInvalid($request, $apiToken);
            }
        } else {
            return $this->returnErrorTokenIsNull($request);
        }
    }

    private function verifyTokenType(Request $request, Closure $next, $apiToken, $tokenPlatform)
    {
        $auth = false;
        $agent = new Agent();
        $platform = $agent->platform();

        if ($agent->isRobot()) {
            if ($agent->robot() !== "Okhttp")
                if (env("APP_DEBUG")) {
                    if ($tokenPlatform === "debug") {
                        $auth = true;
                    }
                } else {
                    return $this->returnErrorTokenDebugOnProd($request, $apiToken);
                }
        }

        if ($agent->isDesktop()) {

            if ($tokenPlatform === "web") {
                $auth = true;
            }

            if ($tokenPlatform === "test") {
                if (env("APP_DEBUG")) {
                    $auth = true;
                } else {
                    $auth = false;
                }
            }
        }

        if ($agent->isMobile() || $agent->isTablet()) {
            if ($platform === 'iOS') {
                if ($tokenPlatform === "ios") {
                    $auth = true;
                }
            }

            if ($platform === 'AndroidOS') {
                if ($tokenPlatform === "android") {
                    $auth = true;
                }
            }
        }

        if ($agent->robot() === "Okhttp") {
            if ($tokenPlatform === "android") {
                $auth = true;
            }
        }

        /**
         * verify auth is ok
         */
        if ($auth) {
            return $next($request);
        } else {
            return $this->returnErrorTokenPlatform($request, $apiToken);
        }
    }

    private function returnErrorTokenIsNull(Request $request): JsonResponse
    {
        $type = ResponseCodes::RESPONSE_CODE_API_TOKEN_OUT;
        $this->registerErrorOnDatabase($request, null, $type);
        return responseError('api-token invalid', $type, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    private function returnErrorTokenBlocked(Request $request, $apiToken): JsonResponse
    {
        $type = ResponseCodes::RESPONSE_CODE_API_TOKEN_BLOCKED;
        $this->registerErrorOnDatabase($request, $apiToken, $type);
        return responseError('api-token invalid', $type, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    private function returnErrorTokenInvalid(Request $request, $apiToken): JsonResponse
    {
        $type = ResponseCodes::RESPONSE_CODE_API_TOKEN_INVALID;
        $this->registerErrorOnDatabase($request, $apiToken, $type);
        return responseError('api-token invalid', ResponseCodes::RESPONSE_CODE_API_TOKEN_INVALID, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    private function returnErrorTokenPlatform(Request $request, $apiToken): JsonResponse
    {
        $type = ResponseCodes::RESPONSE_CODE_API_TOKEN_PLATFORM;
        $this->registerErrorOnDatabase($request, $apiToken, $type);
        return responseError('api-token invalid', $type, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    private function returnErrorTokenDebugOnProd(Request $request, $apiToken): JsonResponse
    {
        $type = ResponseCodes::RESPONSE_CODE_API_TOKEN_DEBUG_ON_PROD;
        $this->registerErrorOnDatabase($request, $apiToken, $type);
        return responseError('api-token invalid', $type, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    private function registerErrorOnDatabase(Request $request, $token, $type)
    {
        $ip = $request->ip();
        $agent = new Agent();
        // ['nl-nl', 'nl', 'en-us', 'en']
        $arr["languages"] = $agent->languages();
        //
        $arr["device"] = $agent->device();
        $arr["platform"] = $agent->platform();
        $arr["browser"] = $agent->browser();
        //
        $browser = $agent->browser();
        $arr["browser"] = $browser;
        $arr["version"] = $agent->version($browser);
        $arr["robot"] = $agent->robot();
        //
        $platform = $agent->platform();
        $arr["platform"] = $platform;
        $arr["version"] = $agent->version($platform);

        $arr["headers"] = getallheaders();

        $register = new ApiTokenFail();
        $register->ip = $ip;
        $register->token = $token;
        $register->type = $type;
        $register->others = json_encode($arr);
        $register->save();
    }
}
