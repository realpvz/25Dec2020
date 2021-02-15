<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only('user');
    }
    
    public function register(Request $request)
    {
        /* Validate Form Input  */
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
        ]);
        /* Insert user into database */
        $user = resolve(UserRepository::class)->create($request);
        
        $defaultSuperAdminEmail = config('permission.default_super_admin_email');

        $user->email === $defaultSuperAdminEmail ? $user->assignRole('Super Admin') : $user->assignRole('User');



        return response()->json([
            'message' => "user created successfully"
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
         /* Validate Form Input  */
         $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($request->only(['email', 'password']))){
            return response()->json(Auth::user(), Response::HTTP_OK);
        }

        
        throw ValidationException::withMessages([
            'email' => 'incorrect credentials',
        ]);
        
    }


    public function user()
    {
        $data = [
            Auth::user(),
            'notifications' => Auth::user()->unreadNotifications(),
            'message' => 'successful'
        ];
        return response()->json($data, Response::HTTP_OK);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            "message" => "logged out successfully",
        ], Response::HTTP_OK);
    }


}
