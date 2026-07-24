<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // Let Laravel handle validation errors normally (redirect back + $errors)
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        // Let Laravel handle normal HTTP exceptions (404, 403, etc.) normally too
        if ($this->isHttpException($e)) {
            return parent::render($request, $e);
        }

        // Custom fallback for everything else
        if (!config('app.debug')) {
            return response()->view('errors.500', [
                'errorMessage' => $e->getMessage(),
                'errorFile'    => $e->getFile(),
                'errorLine'    => $e->getLine(),
            ], 500);
        }

        return parent::render($request, $e);
    }
}