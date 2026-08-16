<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

        // spatie's role middleware throws 403 where the old IsAdmin middleware
        // redirected; keep the redirect behavior for browser requests
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            if (! $request->expectsJson()) {
                return redirect('/home');
            }
        });

        // Policy denials (e.g. CutiPerizinanPolicy) flash an error instead of
        // rendering a bare 403 page in the browser.
        $this->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if (! $request->expectsJson()) {
                // Never bounce back to the URL that was just denied — that
                // would loop. Otherwise back() (falls through to "/" → /home
                // when there is no referer).
                $target = url()->previous() === $request->fullUrl()
                    ? redirect('/home')
                    : redirect()->back();

                return $target->with('error', __('You are not allowed to perform this action.'));
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpFoundation\File\Exception\FileException) {
            // create a validator and validate to throw a new ValidationException
            return Validator::make($request->all(), [
                'your_file_input' => 'required|file|size:5000',
            ])->validate();
        }

        return parent::render($request, $exception);
    }
}
