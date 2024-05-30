<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Quanticheart\Laravel\Constants\ResponseCodes;
use Quanticheart\Laravel\Constants\ValidationRules;
use Quanticheart\Laravel\Helpers\JwtHelper;
use Quanticheart\Laravel\Helpers\RequestValidation;
use Quanticheart\Laravel\Models\User\User;
use Symfony\Component\HttpFoundation\Response;
use function Quanticheart\Laravel\Helpers\responseError;
use function Quanticheart\Laravel\Helpers\responseOk;

/**
 * @method validate(Request $request, array $UPDATE_USER)
 */
class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
//        $this->validateRoutes();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(ValidationRules::LOGIN);
        // Find the user by email
//            $user = User::where('email', $request->input('email'))->first();

        $email = $request->input('email');
        $password = $request->input('password');
//        dd($email, $password);

        if ($email !== "test@test.com" || $password !== "@!123456") {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // different kind of responses. But let's return the
            // below response for now.
            return responseError('user not exists', ResponseCodes::RESPONSE_CODE_LOGIN_USER_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        // Verify the password and generate the token
//        if (Hash::check($request->input('password'), $password)) {
        $data = JwtHelper::encodeEmail($email);
        if ($data['status'] === true) {
            return responseOk($data['msg'], $data['data'], ResponseCodes::RESPONSE_CODE_LOGIN_SUCCESS);
        } else {
            return responseError($data['msg'], $data['code'], $data['http']);
        }
//        }

        // Bad Request response
//        return responseError('user name or password incorrect', ResponseCodes::RESPONSE_CODE_LOGIN_ERROR, Response::HTTP_NOT_FOUND);
    }

    public function getSessionData(Request $request)
    {

        return responseOk('user data', ["email" => $request->auth]);

    }

    public function logout(Request $request)
    {
        return responseOk('user logout');
    }

    public function refresh(Request $request)
    {
        $data = JwtHelper::refresh($request->header('User-Token'));
        if ($data['status'] === true) {
            return responseOk($data['msg'], $data['data'], ResponseCodes::RESPONSE_CODE_LOGIN_SUCCESS);
        } else {
            return responseError($data['msg'], $data['code'], $data['http']);
        }
    }

    public function deleteUser(Request $request)
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $user = User::find($request->auth);
            $user->delete();
            return responseOk('user deleted');
        });
    }

    /**
     * Register api.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return responseError($validator->errors(), ResponseCodes::RESPONSE_CODE_LOGIN_USER_BLOCKED, Response::HTTP_UNAUTHORIZED);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('appToken')->accessToken;

        $data = JwtHelper::encode($input['email']);
        if ($data['status'] === true) {
            return responseOk($data['msg'], $data['data'], ResponseCodes::RESPONSE_CODE_LOGIN_SUCCESS);
        } else {
            return responseError($data['msg'], $data['code'], $data['http']);
        }
    }
}
