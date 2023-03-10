<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
            // return response
        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        else if($e instanceof ValidationException) {
            return response()->json($e->errors(), 422);
        }

        Log::error($e);

        return response()->json([
            'message' => 'Unknown error'
        ], 500);
    }
}
