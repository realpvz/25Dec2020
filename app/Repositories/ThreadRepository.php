<?php


namespace App\Repositories;

use App\Models\User;
use App\Models\Thread;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;




class ThreadRepository {

    
    public function getAllAvailableThreads()
    {
        return Thread::whereFlag(1)->latest()->get();
    }


    public function getThreadBySlug($slug)
    {
        return Thread::whereSlug($slug)->whereFlag(1)->first();
    }


    public function store(Request $request): void
    {
        Thread::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('slug')),
            'content' => $request->input('content'),
            'channel_id' => $request->input('channel_id'),
            'user_id' => auth()->user()->id
        ]);
    }


    public function edit(Thread $thread, Request $request): void
    {
        if (!$request->has('best_answer_id')){

            $thread->update([
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('slug')),
                'content' => $request->input('content'),
                'channel_id' => $request->input('channel_id'),
            ]);
            
        } else {

            $thread->update([
                'best_answer_id' => $request->input('best_answer_id'),
            ]);

        }
    }

    public function destroy($id)
    {
        Thread::destroy($id);
    }
    
}