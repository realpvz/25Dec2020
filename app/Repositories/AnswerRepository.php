<?php


namespace App\Repositories;

use App\Models\Answer;
use App\Models\Thread;
use Illuminate\Support\Str;
use Illuminate\Http\Request;




class AnswerRepository {

    
    public function getAllAnswers()
    {
        return Answer::query()->latest()->get();
    }


    public function store(Request $request)
    {
        Thread::find($request->thread_id)->answers()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);
    }


    public function update(Thread $thread, Request $request): void
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

    public function destroy(Thread $thread)
    {
        $thread->delete();
    }
    
}