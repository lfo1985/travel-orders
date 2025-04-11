<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e) {
            return sendError(401, $e->getMessage(), []);
        });
        
        $exceptions->render(function (ValidationException $e) {
            return sendError(422, 'The provided data is invalid.', $e->errors());
        });
        
        $exceptions->render(function (Throwable $e) {
            return sendError(400, $e->getMessage(), []);
        });

        $exceptions->render(function (Exception $e) {
            return sendError(500, $e->getMessage(), []);
        });
    })->create();
