<?php /** @noinspection PhpUndefinedFieldInspection */

namespace Quanticheart\Laravel\Helpers;

use App\Models\User;
use Firebase\JWT\JWT;

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

    static function decode(string $payload): object
    {
        return JWT::decode($payload, env('JWT_SECRET'), ['HS256']);
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
}
