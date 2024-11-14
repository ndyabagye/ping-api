<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SunsetMiddleware
{

    public function handle(Request $request, Closure $next, string $date): Response
    {
        /**
         * @var Response $response
         */
        $response = $next($request);

        $deprecated = now()->gte(Carbon::parse($date)) ? 'true' : 'false';

        $response->headers->set(
            key: 'Sunset',
            values: $date,
            replace: true
        );
        $response->headers->set(
            key: 'Deprecated',
            values: $deprecated,
            replace: true
        );

        return $response;
    }
}
