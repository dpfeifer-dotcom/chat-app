<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('message')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::post('/make', [MessageController::class, 'makeMessage']);
        Route::post('/get', [MessageController::class, 'getMessages']);
    });
