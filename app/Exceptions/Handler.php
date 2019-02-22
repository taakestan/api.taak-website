<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws \Exception
     */
    public function report(\Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $exception)
    {
        if ($exception instanceof AuthenticationException)
            return response()->json([
                'message' => Lang::trans('exceptions.authentication')
            ], 401);

        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException)
            return response()->json([
                'message' => Lang::trans('exceptions.not-found')
            ], 404);

        if ($exception instanceof ValidationException)
            return response()->json([
                'message' => Lang::trans('exceptions.validation'),
                'errors' => $exception->validator->getMessageBag()
            ], 422);

        if ($exception instanceof ThrottleRequestsException)
            return response()->json([
                'message' => Lang::trans('exceptions.throttle')
            ] , 429);

        return parent::render($request, $exception);
    }
}
