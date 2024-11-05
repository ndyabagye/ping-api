<?php

declare(strict_types=1);

namespace App\Enums;

enum CacheKey: string
{
    case User_services = 'user_services';
    case Service = 'service';
}
