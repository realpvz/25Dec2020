<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channel.all'));

        $response->assertStatus(200);
    }

    public function test_create_new_channel()
    {
        $response = $this->postJson(route('channel.create'),[
           'name' => 'Laravel', 
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }


    public function test_update_channel()
    {
        $channel = Channel::factory()->create([
            'name' => 'Laravel',
        ]);
        $response = $this->json('PUT',route('channel.update'),[
            'id' => $channel->id,
            'name' => 'Vuejs', 
        ]);
            
         $updatedChannel = Channel::find($channel->id);

         $response->assertStatus(Response::HTTP_OK);
         
         $this->assertEquals('Vuejs', $updatedChannel->name);
  
    }

    public function test_delete_channel()
    {
        $channel = Channel::factory()->create();

        $response = $this->json('DELETE', route('channel.delete'), [
            'id' => $channel->id,
        ]);


        $response->assertStatus(Response::HTTP_OK);

    }
}
