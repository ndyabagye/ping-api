<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\CacheKey;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

final class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $this->forgetServicesForUser(
            id: $service->user_id
        );
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        $this->forgetServicesForUser(
            id: $service->user_id
        );

        $this->forgetService(ulid: $service->id);
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $this->forgetServicesForUser(
            id: $service->user_id
        );

        $this->forgetService(ulid: $service->id);
    }

    private function forgetServicesForUser(string $id): void
    {
        Cache::forget(
            key: CacheKey::User_services->value . '_' . $id,
        );
    }

    private function forgetService(string $ulid): void
    {
        Cache::forget(
            key: CacheKey::Service->value . '_' . $ulid,
        );
    }
}
