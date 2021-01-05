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


}
