<?php

use App\Http\Controllers\API\V01\Thread\AnswerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V01\Thread\ThreadController;


/* Threads */
Route::resource('threads', ThreadController::class);

/* Answers */
Route::prefix('/threads')->group(function(){
    Route::resource('answers', AnswerController::class);
});