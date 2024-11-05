<?php

declare(strict_types=1);

use App\Factories\ErrorFactory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web/routes.php',
        api: __DIR__ . '/../routes/api/routes.php',
        commands: __DIR__ . '/../routes/console/routes.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'sunset' => App\Http\Middleware\SunsetMiddleware::class,
            'verified' => App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(fn(UnprocessableEntityHttpException $exception, Request $request) => new JsonResponse(
            data: $exception->getMessage(),
            status: 422
        ));
        $exceptions->render(fn(Throwable $exception, Request $request) => ErrorFactory::create(
            exception: $exception,
            request: $request,
        ));
    })->create();
