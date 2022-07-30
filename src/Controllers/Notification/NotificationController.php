<?php /** @noinspection PhpUndefinedFieldInspection */

namespace Quanticheart\Laravel\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Quanticheart\Laravel\Helpers\RequestValidation;
use Quanticheart\Laravel\Models\User\User;
use Quanticheart\Laravel\Notifications\NotificationAndEmailUser;
use Quanticheart\Laravel\Notifications\NotificationUser;
use function Quanticheart\Laravel\Helpers\responseOk;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        /* excepts */
        $this->validateRoutes();
    }

    /**
     * Notification Code Session
     * @param Request $request
     * @return JsonResponse
     */

    public function newNotification(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $user = User::find($request->auth);
            $user->notify(new NotificationUser($user));
            return responseOk('Notification for ' . $user->email . ' sended');
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function newNotificationWithEmail(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $user = User::find($request->auth);
            $user->notify(new NotificationAndEmailUser($user));
            return responseOk('Notification for ' . $user->email . ' sended');
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function unreadNotificationList(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $user = User::find($request->auth);
            $data = $user->unreadNotifications;
            return responseOk(count($data) . ' unread notifications for ' . $user->email, $data);
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function readNotificationList(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $user = User::find($request->auth);
            $data = $user->readNotifications;
            return responseOk(count($data) . ' read notifications for ' . $user->email, $data);
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function allNotificationList(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $user = User::find($request->auth);
            $data = $user->notifications;
            return responseOk(count($data) . ' notifications for ' . $user->email, $data);
        });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function readNotificationByID(Request $request): JsonResponse
    {
        return RequestValidation::tryCatch(function () use ($request) {
            $nId = $request->id;
            $user = User::find($request->auth);
            $n = $user->notifications()->find($nId);
            $n->markAsRead();
            return responseOk('notification read success');
        });
    }
}
