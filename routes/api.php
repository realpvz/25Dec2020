<?php

use App\Http\Controllers\API\V01\Channel\ChannelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::prefix('v1')->group(function(){
    /* auth */
    include __DIR__ . '/v1/auth_routes.php';

    /* user */
    include __DIR__ . '/v1/user_routes.php';

    /* channels */
    include __DIR__ . '/v1/channels_routes.php';

    /* threads */
    include __DIR__ . '/v1/threads_routes.php';
    
});