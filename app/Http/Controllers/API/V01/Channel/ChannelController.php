<?php

namespace App\Http\Controllers\API\V01\Channel;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Repositories\ChannelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{
    
    public function getAllChannelsList()
    {
        $all_channels = resolve(ChannelRepository::class)->all();
        return response()->json($all_channels, Response::HTTP_OK);
    }

    public function createNewChannel(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        resolve(ChannelRepository::class)->create($request->name);

        return response()->json([
            'message' => 'channel created successfully',
        ], Response::HTTP_CREATED);
    }

    public function updateChannel(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        resolve(ChannelRepository::class)->update($request->id, $request->name);

        return response()->json([
            'message' => 'channel edited successfully'
        ], 200);
    }
    
    public function deleteChannel(Request $request)
    {
        $request->validate([
            'id' => ['required'],
        ]);

        resolve(ChannelRepository::class)->delete($request->id);
        
        return response()->json([
            'message' => 'channel deleted successfully'
        ], Response::HTTP_OK);

    }
}
