<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('v1')->group(static function (): void {

    Route::get('/', fn() => response()->json(request()->route()))->middleware(['sunset:' . now()->subDays(3)]);

    Route::middleware(['throttle:api'])->group(static function (): void {
        Route::get('user', static fn(Request $request) => $request->user())->name('user');

        Route::prefix('services')->as('services')->group(base_path(
            path: "routes/api/v1/services.php",
        ))->middleware(['throttle:100,1']);

        Route::prefix('credentials')->as('credentials')->group(base_path(
            path: "routes/api/v1/credentials.php",
        ));

        Route::prefix('checks')->as('checks')->group(base_path(
            path: "routes/api/v1/checks.php",
        ));
    });

});

Route::prefix('v2')->as('v2')->group(static function (): void {
    Route::get('/', fn() => response()->json(request()->route()));

});


