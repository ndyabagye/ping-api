<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Services;
use Illuminate\Support\Facades\Route;

Route::get("/", Services\IndexController::class)->name("index");
Route::post("/", Services\StoreController::class)->name("store");
Route::get("{ulid}", Services\ShowController::class)->name("show");
Route::put("{service}", Services\UpdateController::class)->name("update");
Route::delete("{service}", Services\DeleteController::class)->name("delete");

