<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Models\Answer;
use App\Models\Thread;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AnswerRepository;
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

        return response()->json([
            'message' => 'answers created successfully',
        ], Response::HTTP_CREATED);
    }


    public function update(Request $request, Answer $answer)
    {
        //
    }


    public function destroy(Answer $answer)
    {
        //
    }
}
