<?php

namespace App\Http\Controllers\API\V01\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userNotifications()
    {
        return response()->json(auth()->user()->unreadNotifications());
    }  
}
