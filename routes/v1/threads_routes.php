<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V01\Thread\ThreadController;



Route::resource('threads', ThreadController::class);