<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Channel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelControllerTest extends TestCase
{
    
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

    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channel.all'));

        $response->assertStatus(200);
    }


    public function test_create_channel_should_be_validate()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();

        $user->givePermissionTo('channel management');

        $response = $this->actingAs($user)->postJson(route('channel.create'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    
    public function test_create_new_channel()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();

        $user->givePermissionTo('channel management');

        $response = $this->actingAs($user)->postJson(route('channel.create'),[
           'name' => 'Laravel', 
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }


    public function test_update_channel()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();

        $user->givePermissionTo('channel management');

        $channel = Channel::factory()->create([
            'name' => 'Laravel',
        ]);
        $response = $this->actingAs($user)->json('PUT',route('channel.update'),[
            'id' => $channel->id,
            'name' => 'Vuejs', 
        ]);
            
         $updatedChannel = Channel::find($channel->id);

         $response->assertStatus(Response::HTTP_OK);
         
         $this->assertEquals('Vuejs', $updatedChannel->name);
  
    }

    public function test_delete_channel()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();

        $user->givePermissionTo('channel management');

        $channel = Channel::factory()->create();

        $response = $this->actingAs($user)->json('DELETE', route('channel.delete'), [
            'id' => $channel->id,
        ]);


        $response->assertStatus(Response::HTTP_OK);

    }
}
