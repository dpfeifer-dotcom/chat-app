<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::post('/get_all', [UserController::class, 'allUsers']);
    });
