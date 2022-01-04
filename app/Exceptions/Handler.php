<?php

namespace App\Exceptions;

use App\base\IResponseCode;
use App\Helpers\ApiHelper;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ApiException::class,
        TokenExpiredException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     * @throws \Throwable
     *
     */
    public function report(\Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $exception)
    {
        if($exception instanceof ApiException){
            return api_response($exception->data,$exception->errors,$exception->message,$exception->responseCode);
        }

        if ($exception instanceof TokenExpiredException) {
            return api_error(['error' => 'Session has expired'],IResponseCode::USER_NOT_LOGGED_IN);
        }

        return response(ApiHelper::apiResponse(null,['Message' => $exception->getMessage(),'Trace' => $exception->getTrace()]),IResponseCode::INTERNAL_SERVER_ERROR);
    }
}
