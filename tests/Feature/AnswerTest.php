<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_all_answers_list()
    {
        $response = $this->get(route('answers.index'));

        $response->assertSuccessful();
    }


    public function test_create_answer_should_be_validated()
    {
        $response = $this->postJson(route('answers.store'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(['content', 'thread_id']);
    }


    public function test_can_submit_new_answer_for_thread()
    {
        Sanctum::actingAs(User::factory()->create());
        
        $thread = Thread::factory()->create();
        
        $response = $this->postJson(route('answers.store'), [
            'content' => 'Foo',
            'thread_id' => $thread->id,
        ]);
        
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertTrue($thread->answers()->where('content', 'Foo')->exists());
    }
}
