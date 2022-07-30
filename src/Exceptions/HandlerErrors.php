<?php

namespace Quanticheart\Laravel\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Quanticheart\Laravel\Constants\ResponseCodes;
use Throwable;
use function Quanticheart\Laravel\Helpers\responseCsrfError;
use function Quanticheart\Laravel\Helpers\responseError;

class HandlerErrors extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     *
     * @return JsonResponse|RedirectResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->is('api.*')) {
            return $this->handleApi($request, $e);
        } else {
            return $this->handleWeb($request, $e);
        }
    }

    /**
     * @throws Throwable
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private function handleWeb($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            $view = [
                "code" => $exception->getStatusCode() ?? null,
                "test" => "test"
            ];
            return response()->view('errors.http', $view, $exception->getStatusCode());
        }

        if ($exception instanceof TokenMismatchException) {
            $json = responseCsrfError($exception);
            return redirect()->back()->with($json);
        }
        return parent::render($request, $exception);
    }

    /**
     * @throws Throwable
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private function handleApi($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if (env("APP_DEBUG")) {
                $data['status'] = $exception->getStatusCode();
                $data['errors'] = [Arr::get($data, 'exception', 'Something went wrong!')];
                $data['message'] = Arr::get($data, 'message', '');
            } else {
                $data = null;
            }
            $msg = !env("APP_DEBUG") ? "error with requisition" : "error with requisition on debug";

            if ($exception->getStatusCode() >= 500) {
                return responseError($msg, ResponseCodes::RESPONSE_CODE_500, $exception->getStatusCode(), $data);
            }
            if ($exception->getStatusCode() >= 400) {
                return responseError($msg, ResponseCodes::RESPONSE_CODE_400, $exception->getStatusCode(), $data);
            }
        }

        return parent::render($request, $exception);
    }
}
