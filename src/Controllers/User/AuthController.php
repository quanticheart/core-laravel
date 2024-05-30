<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Quanticheart\Laravel\Constants\ResponseCodes;
use Symfony\Component\HttpFoundation\Response;
use function Quanticheart\Laravel\Helpers\responseError;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if ($email !== "test@test.com" || $password !== "@!123456") {
            return responseError('user not exists', ResponseCodes::RESPONSE_CODE_LOGIN_USER_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        $credentials = request(['email', 'password']);

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        return $this->createNewToken($token);
    }

//    /**
//     * Register a User.
//     *
//     * @return JsonResponse
//     */
//    public function register(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string|between:2,100',
//            'email' => 'required|string|email|max:100|unique:users',
//            'password' => 'required|string|confirmed|min:6',
//        ]);
//        if ($validator->fails()) {
//            return response()->json($validator->errors()->toJson(), 400);
//        }
//        $user = User::create(array_merge(
//            $validator->validated(),
//            ['password' => bcrypt($request->password)]
//        ));
//        return response()->json([
//            'message' => 'User successfully registered',
//            'user' => $user
//        ], 201);
//    }
//
//    /**
//     * Log the user out (Invalidate the token).
//     *
//     * @return JsonResponse
//     */
//    public function logout()
//    {
//        auth()->logout();
//        return response()->json(['message' => 'User successfully signed out']);
//    }
//
//    /**
//     * Refresh a token.
//     *
//     * @return JsonResponse
//     */
//    public function refresh()
//    {
//        return $this->createNewToken(auth()->refresh());
//    }
//
//    /**
//     * Get the authenticated User.
//     *
//     * @return JsonResponse
//     */
//    public function userProfile()
//    {
//        return response()->json(auth()->user());
//    }
//
//    /**
//     * Get the token array structure.
//     *
//     * @param string $token
//     *
//     * @return JsonResponse
//     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
