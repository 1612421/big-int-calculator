<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson() || $request->ajax()) {
                $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;

                $error['error'] = $e->getMessage();
                if ($e instanceof ValidationException) {
                    $code = 422;
                    $error['error'] = collect($e->errors())->first()[0] ?? 'Something validate wrong';
                }

                $error['response_code'] = $code;
                if (env('APP_DEBUG')) {
                    $error['debug'] = [
                        'exception' => get_class($e),
                        'info' => sprintf("File: %s. Line: %s.", $e->getFile(), $e->getLine()),
                        'trace' => $e->getTrace()
                    ];
                }
                return response()->json($error, $code);
            }
        });
    }
}
