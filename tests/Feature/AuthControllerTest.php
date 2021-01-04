<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_new_user_can_register()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => "ImanPvZ",
            'email' => "Imanpvz@yahoo.com",
            'password' => "123456789",
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(); 

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => "password",
        ]);

        $response->assertStatus(200);
    }

    public function test_show_user_info_if_logged_in()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('auth.user'));
        
        $response->assertStatus(200);
    }

    public function test_logged_in_user_can_logout()
    {
        $user = User::factory()->create(); 
    
        $response = $this->actingAs($user)->postJson(route('auth.logout'));
    
        $response->assertStatus(200);
    }
}
