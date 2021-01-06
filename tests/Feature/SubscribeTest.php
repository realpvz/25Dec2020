<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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
}
