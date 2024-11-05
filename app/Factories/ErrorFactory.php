<?php

declare(strict_types=1);

namespace App\Factories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;
use Treblle\ApiResponses\Data\ApiError;
use Treblle\ApiResponses\Responses\ErrorResponse;
use JustSteveKing\Tools\Http\Enums\Status;

final class ErrorFactory
{
    public static function create(Throwable $exception, Request $request): ErrorResponse
    {
        return match ($exception::class) {
            NotFoundHttpException::class,
            ModelNotFoundException::class => new ErrorResponse(
                data: new ApiError(
                    title: 'Something went wrong',
                    detail: 'The resource you are looking for does not exist.',
                    instance: $request->fullUrl(),
                    code: 'HTTP-404',
                    link: 'https://docs.treblle.com/errors/404'
                ),
                status: Status::NOT_FOUND,
            ),
            MethodNotAllowedException::class,
            MethodNotAllowedHttpException::class => new ErrorResponse(
                data: new ApiError(
                    title: 'Method Not Allowed',
                    detail: "You are trying to do a {$request->method()} request on an endpoint that only allows {$exception->getAllowedMethods()}.",
                    instance: $request->fullUrl(),
                    code: 'HTTP-405',
                    link: 'https://docs.treblle.com/errors/405'
                ),
                status: Status::METHOD_NOT_ALLOWED,
            ),

            default => new ErrorResponse(
                data: new ApiError(
                    title: 'Something went wrong.',
                    detail: 'Something went wrong.',
                    instance: $request->fullUrl(),
                    code: 'SER-500',
                    link: 'https://docs.treblle.com/errors/500'
                ),
                status: Status::INTERNAL_SERVER_ERROR,
            )
        };
    }
}
