<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\Libraries\BinaryDrawException;
use App\Exceptions\Third\ThirdException;
use App\Exceptions\Auth\JwtException;
use App\Exceptions\Auth\DockingException;
use App\Exceptions\Binary\UserException;
use App\Exceptions\Binary\BinaryException;
use App\Exceptions\Binary\BettingException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        JwtException::class,
        UserException::class,
        BinaryException::class,
        BettingException::class,
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

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BinaryDrawException) {
            return $exception->render($request, $exception);
        }
        if ($exception instanceof ThirdException) {
            return $exception->render($request, $exception);
        }
        if ($exception instanceof JwtException) {
            return $exception->render($request, $exception);
        }
        if ($exception instanceof DockingException) {
            return $exception->render($request, $exception);
        }
        if ($exception instanceof UserException) {
            return $exception->render($request, $exception);
        }
        if ($exception instanceof BinaryException) {
            return $exception->render($request, $exception);
        }
        if ($exception instanceof BettingException) {
            return $exception->render($request, $exception);
        }

        return parent::render($request, $exception);
    }
}
