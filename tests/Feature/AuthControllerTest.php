<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
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
        $this->registerRolesAndPermissions();
        $response = $this->postJson(route('auth.register'), [
            'name' => "ImanPvZ",
            'email' => "ipvz@yahoo.com",
            'password' => "123456789",
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(); 

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => "password",
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_show_user_info_if_logged_in()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('auth.user'));
        
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_logged_in_user_can_logout()
    {
        $user = User::factory()->create(); 
    
        $response = $this->actingAs($user)->postJson(route('auth.logout'));
    
        $response->assertStatus(Response::HTTP_OK);
    }

    public function registerRolesAndPermissions()
    {
        $roleInDatabase = Role::where('name', config('permission.default_roles')[0]);
        if ($roleInDatabase->count() < 1){
            foreach (config('permission.default_roles') as $role) {
                Role::create([
                    'name' => $role,
                ]);
            }
        }

        $permissionInDatabase = Permission::where('name', config('permission.default_permissions')[0]);
        if ($permissionInDatabase->count() < 1){
            foreach(config('permission.default_permissions') as $permission) {
                Permission::create([
                    'name' => $permission,
                ]);
            }
        }
    }
}
