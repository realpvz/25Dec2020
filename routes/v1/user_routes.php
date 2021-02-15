<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V01\Auth\AuthController;
use App\Http\Controllers\API\V01\User\UserController;



/* Auth Routes */
Route::prefix('users')->group(function(){
    Route::get('/leaderboards', [UserController::class, 'leaderboards'])->name('users.leaderboard');
});