<?php /** @noinspection PhpUndefinedFieldInspection */

namespace Quanticheart\Laravel\Helpers;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;
use Quanticheart\Laravel\Constants\ResponseCodes;

class JwtHelper
{
    /**
     * Create a new token.
     *
     * @param User $user
     * @return string
     */
    static function encode(User $user): string
    {
        $payload = [
            'sub' => HashHelper::encrypt($user->id), // Subject of the token
            'level' => HashHelper::encrypt($user->level), // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    static function decode(string $token)
    {
        try {
            return self::ok((array)JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256')));
        } catch (InvalidArgumentException $e) {
            // provided key/key-array is empty or malformed.
            return self::error($e->getMessage());
        } catch (DomainException $e) {
            // provided algorithm is unsupported OR
            // provided key is invalid OR
            // unknown error thrown in openSSL or libsodium OR
            // libsodium is required but not available.
            return self::error($e->getMessage());
        } catch (SignatureInvalidException $e) {
            // provided JWT signature verification failed.
            return self::error($e->getMessage());
        } catch (BeforeValidException $e) {
            // provided JWT is trying to be used before "nbf" claim OR
            // provided JWT is trying to be used before "iat" claim.
            return self::error($e->getMessage());
        } catch (ExpiredException $e) {
            // provided JWT is trying to be used after "exp" claim.
            return self::error($e->getMessage());
        } catch (UnexpectedValueException $e) {
            // provided JWT is malformed OR
            // provided JWT is missing an algorithm / using an unsupported algorithm OR
            // provided JWT algorithm does not match provided key OR
            // provided key ID in key/key-array is empty or invalid.
            return self::error($e->getMessage());
        }
    }

//    static function authRequest(Request $request, string $token)
//    {
//        try {
//            $credentials = JwtHelper::decode($token);
//        } catch (ExpiredException $ex) {
//            return responseTokenError('msgErrorTokenExpired', ResponseCodes::RESPONSE_CODE_TOKEN_EXPIRED);
//        } catch (Exception $ex) {
//            return responseTokenError('msgErrorTokenInvalid', ResponseCodes::RESPONSE_CODE_TOKEN_INVALID);
//        }
//
//        // Now let's put the user in the request class so that you can grab it from there
//        $request->auth = HashHelper::decrypt($credentials->sub);
//        $request->userLevel = HashHelper::decrypt($credentials->level);
//        return $request;
//    }

    static function refresh(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $timeExpiration = $decoded->exp;
        } catch (ExpiredException $e) {
            JWT::$leeway = 720000;
            $time = time();
            $timeExpiration = $time + 30;

            $decoded = (array)JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $decoded['iat'] = $time;
            $decoded['exp'] = $timeExpiration;

            $token = JWT::encode($decoded, env('JWT_SECRET'), 'HS256');
            return self::ok([
                'token' => $token,
                'expire' => $timeExpiration
            ]);

        } catch (Exception $e) {
            return self::error($e);
        }
        return self::ok([
            'token' => $token,
            'expire' => $timeExpiration
        ]);
    }

    private static function ok($payload): array
    {
        return [
            "status" => true,
            "msg" => 'ok',
            "data" => $payload,
            "http" => Response::HTTP_OK
        ];
    }

    private static function error($error, $code = ResponseCodes::RESPONSE_CODE_LOGIN_USER_NOT_FOUND): array
    {
        return [
            "status" => false,
            "msg" => 'unauthorized',
            "code" => $code,
            "http" => Response::HTTP_UNAUTHORIZED
        ];
    }
}
