<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ProtoneMedia\Splade\SpladeCore;
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
        $this->renderable(\ProtoneMedia\Splade\SpladeCore::exceptionHandler($this));

        $this->renderable(SpladeCore::exceptionHandler($this));
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
