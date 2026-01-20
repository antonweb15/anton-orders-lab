<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // API middleware group
        $middleware->group('api', [
            \App\Http\Middleware\ForceJson::class,
        ]);

        // activate stateful API (Sanctum)
        $middleware->statefulApi();

    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Business exceptions
        $exceptions->render(function (BusinessException $e, Request $request) {
            logger()->warning('Business exception', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }

            return response(
                "<h2>Business error</h2><p>{$e->getMessage()}</p>",
                $e->getStatusCode()
            );
        });

        // 🔹 Add AuthenticationException
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Let Filament handle its own authentication exceptions
            if ($request->is('admin') || $request->is('admin/*')) {
                return null;
            }

            if (Route::has('login')) {
                return redirect()->guest(route('login'));
            }

            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        });

        // System / programmer errors
        $exceptions->render(function (Throwable $e, Request $request) {
            // Skip standard exceptions to let Laravel handle them (422, 403, 401, etc.)
            if ($e instanceof \Illuminate\Validation\ValidationException ||
                $e instanceof \Illuminate\Auth\Access\AuthorizationException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return null;
            }

            logger()->error('System error', [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => $request->fullUrl(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Internal server error',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // In debug mode Laravel will show Whoops
            return null;
        });


    })->create();
