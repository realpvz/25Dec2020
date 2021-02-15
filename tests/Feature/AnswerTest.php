<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Answer;
use App\Models\Thread;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;
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
        $response->assertJson([
            'message' => 'answers created successfully'
        ]);
        $this->assertTrue($thread->answers()->where('content', 'Foo')->exists());
    }

    public function test_user_score_will_increase_by_submit_new_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $thread = Thread::factory()->create();
        
        $response = $this->postJson(route('answers.store'), [
            'content' => 'Foo',
            'thread_id' => $thread->id,
        ]);
        
        $response->assertStatus(Response::HTTP_CREATED);
        $user->refresh();
        $this->assertEquals(10, $user->score);

    }


    public function test_update_answer_should_be_validated()
    {
        $answer = Answer::factory()->create();
        
        $response = $this->putJson(route('answers.update', [$answer]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(['content']);
    }

    

    public function test_can_update_own_answer_for_thread()
    {
        $user = User::factory()->create();
        
        Sanctum::actingAs($user);
        
        $answer = Answer::factory()->create([
            'content' => 'Foo',
            'user_id' => $user->id,
        ]);
        
        $response = $this->putJson(route('answers.update', [$answer]), [
            'content' => 'Bar',
        ]);
        
        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'answer updated successfully'
            ]);

        $answer->refresh();
        $this->assertEquals('Bar', $answer->content);
    }


    public function test_can_delete_own_answer()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        
        $answer = Answer::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete(route('answers.destroy',[$answer]));

        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'answer deleted successfully'
            ]);

        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());
    }
}
