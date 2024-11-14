<?php

declare(strict_types=1);

use App\Console\Commands\Ping;
use Illuminate\Support\Facades\Schedule;

Schedule::command(
    command: Ping::class,
)->everyMinute()->withoutOverlapping()->onOneServer();
