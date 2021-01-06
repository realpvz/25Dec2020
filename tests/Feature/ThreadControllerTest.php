<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use App\Models\Channel;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *  
     * @return void
     */
    public function test_all_threads_list_should_be_accessible()
    {
        $response = $this->get(route('threads.index'));

        $response->assertStatus(Response::HTTP_OK);
    }


    public function test_show_detail_thread()
    {   
        $thread = Thread::factory()->create();
        
        $response = $this->get(route('threads.show', [$thread->slug]));

        $response->assertStatus(Response::HTTP_OK);
    }


    public function test_thread_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('threads.store', []));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_create_threads()
    {   
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('threads.store', [
            'title' => 'Foo',
            'content' => 'Bar',
            'channel_id' => Channel::factory()->create()->id,
        ]));

        $response->assertStatus(Response::HTTP_CREATED);
    }


    public function test_edit_thread_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());
        
        $thread = Thread::factory()->create(); 
        
        $response = $this->putJson(route('threads.update', [$thread]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    

    public function test_update_thread()
    {   
        $user = User::factory()->create();
        
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create([
            'title' => 'Foo',
            'content' => 'Bar',
            'channel_id' => Channel::factory()->create()->id,
            'user_id' => $user->id,

        ]);
        
        $response = $this->putJson(route('threads.update', [$thread]),[
            'title' => 'Bar',
            'content' => 'Bar',
            'channel_id' => Channel::factory()->create()->id,
        ])->assertSuccessful();

        $thread->refresh();
        
        $this->assertSame('Bar', $thread->title);
    }


    public function test_can_add_best_answer_id_for_thread()
    {   
        $user = User::factory()->create();
        
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $response = $this->putJson(route('threads.update', [$thread]),[
            'best_answer_id' => 1,
        ])->assertSuccessful();

        $thread->refresh();
        
        $this->assertSame('1', $thread->best_answer_id);
    }


    public function test_can_delete_thread()
    {
        $user = User::factory()->create();
        
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $response = $this->deleteJson(route('threads.destroy', [$thread->id]));

        $response->assertStatus(Response::HTTP_OK);
    }
}
