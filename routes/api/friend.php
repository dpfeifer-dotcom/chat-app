<?php

use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;

Route::prefix('friend')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::post('/set', [FriendController::class, 'setFriend']);
    });
