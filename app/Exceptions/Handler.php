<?php

namespace App\Exceptions;

use http\Env\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception as Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Http\Controllers\Api\BaseController as BaseController;
use Config as config;
use App\Exceptions\ApiExceptionHandler as APIException;
class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     * Here I added
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Here we can report or write error into Logging file or database table
        });
        /*$this->renderable(function (NotFoundHttpException $e, Request $request, Exception $exception) {
            if ($request->is('api/*')) {
                $baseController = new BaseController; // Object BaseController so that we can access method for errors
                $statusCode = 404;
                return $baseController->sendApiError('Not Fund', $statusCode);
            }


        });*/

    }

}
