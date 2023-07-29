<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use MaxDev\Modules\APITrait;
use Throwable;

class Handler extends ExceptionHandler
{
    use APITrait;
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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Throwable $exception)
    {
        // handle not found exception with JSON response
        if ($exception instanceof ModelNotFoundException) {
            if(request()->routeIs('admin.*')) {
                abort(404);
            }
            return $this->respondNotFound('','Resource not found');
        }

        if($exception instanceof \Illuminate\Auth\AuthenticationException) {
            if(request()->routeIs('api.*')){
                return $this->respondUnAuthenticated('');
            }
            if(request()->routeIs('admin.*')){
                return redirect()->route('admin.login');
            }
        }
        return parent::render($request, $exception);
    }
}
