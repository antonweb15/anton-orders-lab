<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // registration alias
        $middleware->alias([
            'change.order.prices.api' => \App\Http\Middleware\ChangeOrderPricesApi::class,
        ]);

        // API middleware group
        $middleware->group('api', [
            \App\Http\Middleware\ForceJson::class,
        ]);

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

    // System / programmer errors
    $exceptions->render(function (Throwable $e, Request $request) {

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

        // в debug Laravel сам покажет Whoops
        return null;
    });


    })->create();
