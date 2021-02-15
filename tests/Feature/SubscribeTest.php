<?php

namespace Tests\Feature;

use App\Models\Answer;
use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use App\Notifications\NewReplySubmitted;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class SubscribeTest extends TestCase
{

    
    public function test_user_can_subscribe_thread()
    {
        Sanctum::actingAs(User::factory()->create());
        
        $thread = Thread::factory()->create();
        
        $response = $this->postJson(route('subscribe', [$thread]));

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'user subscribed successfully',
        ]);
    }


    public function test_user_can_unsubscribe_from_a_thread()
    {
        Sanctum::actingAs(User::factory()->create());
        
        $thread = Thread::factory()->create();
        
        $response = $this->postJson(route('unsubscribe', [$thread]));

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'user unsubscribed successfully',
        ]);
    }


    public function test_notification_will_send_to_subscribers_of_a_thread()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Notification::fake();

        $thread = Thread::factory()->create();

        $subscribe_response = $this->postJson(route('subscribe', [$thread]));
        $subscribe_response->assertSuccessful();
        $subscribe_response->assertJson([
            'message' => 'user subscribed successfully',
        ]);

        
        $answer_response = $this->postJson(route('answers.store'), [
            'content' => 'Foo',
            'thread_id' => $thread->id,
            ]);
            
        $answer_response->assertSuccessful();
        $answer_response->assertJson([
            'message' => 'answers created successfully',
        ]);
        
        Notification::assertSentTo($user, NewReplySubmitted::class);
    }
}
