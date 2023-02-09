<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function(AccessDeniedHttpException $e, $request) {
            $previous = $e->getPrevious();
            if($previous instanceof AuthorizationException && $request->expectsJson()) {
                return response()->json([
                    "errors" => ["message" => 'You are not authorized to access this resource!']
                ], 403);
            }
        });

        $this->renderable(function(NotFoundHttpException $e, $request) {
            $previous = $e->getPrevious();
            if($previous instanceof ModelNotFoundException && $request->expectsJson()) {
                return response()->json([
                    "errors" => ["message" => 'The resource was not found in the database!']
                ], 404);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
