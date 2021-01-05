<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ChannelRepository;
use App\Repositories\ThreadRepository;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = resolve(ThreadRepository::class)->getAllAvailableThreads();

        return response()->json($threads, Response::HTTP_OK);
    }


    public function show($slug)
    {
        $thread = resolve(ThreadRepository::class)->getThreadBySlug($slug);
        
        return response()->json($thread, Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'channel_id' => 'required'
        ]);

        resolve(ThreadRepository::class)->store($request);

        return response()->json([
            'message' => 'thread created successfully',
        ], Response::HTTP_CREATED);
    }


    public function update(Request $request, Thread $thread)
    {
            $request->has('best_answer_id')
               ? $request->validate([
                'best_answer_id' => 'required',
            ])

               : $request->validate([
                'title' => 'required',
                'content' => 'required',
                'channel_id' => 'required'
            ]);
        resolve(ThreadRepository::class)->edit($thread, $request);

        return response()->json([
            'message' => 'thread updated successfully',
        ], Response::HTTP_OK);
    }


    public function destroy($id)
    {
        resolve(ThreadRepository::class)->destroy($id);

        return response()->json([
            'message' => 'thread deleted successfully',
        ], Response::HTTP_OK);
    }



}
