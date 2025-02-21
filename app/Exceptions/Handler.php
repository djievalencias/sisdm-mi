<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Akses tidak diizinkan. Silakan login.',
        ], 401);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof QueryException) {
            if ($this->isDuplicateEntryError($exception)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data sudah ada. Harap gunakan nilai yang berbeda.'
                ], 400);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Check if the exception is a duplicate entry error
     */
    private function isDuplicateEntryError(QueryException $exception)
    {
        return str_contains($exception->getMessage(), 'Duplicate entry');
    }
}
