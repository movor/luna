<?php

namespace App\Exceptions;

use App\Exceptions\AppExceptions\AppErrorException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \App\Exceptions\AppExceptions\AppErrorException::class,
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
     * @param Exception $exception
     *
     * @throws Exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Exception                $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Handle different type of exceptions here
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.error', [
                'title' => 'Page Not Found',
                'body' => "We can't find the page you're looking for",
            ], 404);
        } elseif ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.error', [
                'title' => 'Record Not Found',
                'body' => "We can't find the record you're looking for",
            ], 404);
        } elseif ($exception instanceof AppErrorException) {
            return response()->view('errors.error', [
                'body' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request                 $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $routePrefix = $request->route()->getPrefix();

        // Redirect to login page based on route prefix
        return redirect($routePrefix . '/login');
    }
}
