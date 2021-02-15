<?php

namespace App\Http\Controllers\API\V01\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function userNotifications()
    {
        return response()->json(auth()->user()->unreadNotifications(), Response::HTTP_OK);
    }


    public function leaderboards()
    {
        return resolve(UserRepository::class)->leaderboards();
    }
}
