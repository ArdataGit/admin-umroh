<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                // Ensure no HTML output from PHP warnings/errors leaks into the response
                ini_set('display_errors', '0');
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal: Ukuran total file unggahan terlalu besar (' . ini_get('post_max_size') . '). Silakan kompres foto Anda atau hubungi admin.'
                ], 413);
            }
        });
    })->create();
