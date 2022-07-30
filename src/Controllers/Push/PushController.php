<?php /** @noinspection PhpUndefinedFieldInspection */

namespace Quanticheart\Laravel\Controllers\Push;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Quanticheart\Laravel\Constants\ValidationRules;
use Quanticheart\Laravel\Firebase\Push\PushSender;
use Quanticheart\Laravel\Helpers\RequestValidation;
use Quanticheart\Laravel\Models\User\User;
use Quanticheart\Laravel\Models\User\UsersNotificationToken;
use function Quanticheart\Laravel\Helpers\responseOk;

class PushController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveToken(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $request->validate(ValidationRules::INSERT_USER_TOKEN);

            $user = new UsersNotificationToken();
            $user->user_id = $request->auth ?? 1;
            $user->token = $request->token;
            $user->save();
            return responseOk('token saved successfully.');
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendNotificationToUser(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $request->validate(ValidationRules::SEND_PUSH_USER);
            $firebaseToken = User::find($request->auth)->usersNotificationToken;
            return $this->sendPush($request, $firebaseToken);
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendNotificationToAllUser(Request $request): JsonResponse
    {
        return RequestValidation::tryCatchAdm($request, function () use ($request) {
            $request->validate(ValidationRules::SEND_PUSH_USER);
            $firebaseToken = UsersNotificationToken::pushTokenList();
            return $this->sendPush($request, $firebaseToken);
        });
    }

    /**
     * @param Request $request
     * @param array $arrayTokens
     * @return JsonResponse
     */
    private function sendPush(Request $request, array $arrayTokens): JsonResponse
    {
        // Number of messages on bulk (2000) exceeds maximum allowed (1000)
        // split array for not exceed limit token for firebase
        $arrSplit = array_chunk($arrayTokens, 500);
        if (count($arrSplit) > 0) {
            foreach ($arrSplit as $array) {
                $arr = PushSender::allUsers(Arr::pluck($array, "token"),
                    $request->title, $request->message, $request->deeplink);
                if (sizeof($arr) > 0) {
                    foreach ($arr as $index) {
                        UsersNotificationToken::find($array[$index]['id'])->delete();
                    }
                }
            }
            return responseOk('send ok');
        } else {
            return responseOk('zero registrations in database');
        }
    }

    /**
     * @param array $arrayTokens
     * @return JsonResponse
     */
    static function sendPushPkmToday(array $arrayTokens): JsonResponse
    {
        // Number of messages on bulk (2000) exceeds maximum allowed (1000)
        // split array for not exceed limit token for firebase
        $arrSplit = array_chunk($arrayTokens, 500);
        if (count($arrSplit) > 0) {
            foreach ($arrSplit as $array) {
                $arr = PushSender::allUsers(Arr::pluck($array, "token"),
                    "PKm", "", "");
                if (sizeof($arr) > 0) {
                    foreach ($arr as $index) {
                        UsersNotificationToken::find($array[$index]['id'])->delete();
                    }
                }
            }
            return responseOk('send ok');
        } else {
            return responseOk('zero registrations in database');
        }
    }
}
