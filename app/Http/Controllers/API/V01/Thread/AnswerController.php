<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Repositories\AnswerRepository;
use App\Notifications\NewReplySubmitted;
use App\Repositories\SubscribeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = resolve(AnswerRepository::class)->getAllAnswers();

        return response()->json($answers, Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'thread_id' => 'required',
        ]);

        resolve(AnswerRepository::class)->store($request);
        // List users_id which subscribed to a thread id
        $notifiable_users_id = resolve(SubscribeRepository::class)->getNotifiableUser($request->thread_id);

        // Get user instance from id 
        $notifiable_users = resolve(UserRepository::class)->find($notifiable_users_id);
        
        // Send NewReplySubmitted Notification To Subscribed Users
        Notification::send($notifiable_users, new NewReplySubmitted(Thread::find($request->thread_id)));
        
        return response()->json([
            'message' => 'answers created successfully',
        ], Response::HTTP_CREATED);
    }


    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'content' => 'required',
        ]);
        
        if(Gate::forUser(auth()->user())->allows('user-answer', $answer)){

            resolve(AnswerRepository::class)->update($request, $answer);

            return response()->json([
                'message' => 'answer updated successfully',
            ],Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'access denied',
        ],Response::HTTP_FORBIDDEN);
    }


    public function destroy(Answer $answer)
    {
        if(Gate::forUser(auth()->user())->allows('user-answer', $answer)){

            resolve(AnswerRepository::class)->destroy($answer);

            return response()->json([
                'message' => 'answer deleted successfully',
            ],Response::HTTP_OK);
    
        }
        return response()->json([
            'message' => 'access denied',
        ],Response::HTTP_FORBIDDEN);

    }
}
