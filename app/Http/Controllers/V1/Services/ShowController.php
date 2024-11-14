<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Services;

use App\Enums\CacheKey;
use App\Http\Resources\V1\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

final class ShowController
{
    public function __invoke(Request $request, string $ulid): Response
    {
        Cache::forever(
            key: CacheKey::Service->value . '_' . $ulid,
            value: $service = Service::query()->findOrFail(
                id: $ulid,
            ),
        );

        if (!Gate::allows('view', $service)) {
            throw new UnauthorizedException(
                message: __('services.v1.show.failure')
            );
        }

        return new JsonResponse(
            data: new ServiceResource(
                resource: $service,
            )
        );
    }
}
