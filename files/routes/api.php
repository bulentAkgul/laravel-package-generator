<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::middleware(['api', 'auth:sanctum'])->group(function () {
        Route::apiResources([]);
    });
});