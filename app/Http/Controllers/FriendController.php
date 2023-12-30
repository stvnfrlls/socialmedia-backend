<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Http\Requests\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function store(FriendRequest $request)
    {
        $response_data = Friend::create($request->all());

        if ($response_data) {
            $message = 'Friend request sent successfully';
        } else {
            $message = 'Error occurred when sending friend request';
        }

        return response()->json($message);
    }

    public function show($id)
    {
        $friendData = [];
        $transformedData = [];

        $response_data = Friend::select('friend_id')
            ->distinct('friend_id')
            ->where('user_id', $id)
            ->whereNotNull('accepted')
            ->with('friend')
            ->get();

        foreach ($response_data as $friend) {
            $friendData['id'] = $friend['friend']['id'];
            $friendData['name'] = $friend['friend']['name'];
            $transformedData[] = $friendData;
        }

        return response()->json($transformedData);
    }

    public function showrequest($id)
    {
        $response_data = Friend::where('user_id', $id)
            ->where('accepted', null)
            ->with('friend')
            ->latest()
            ->get();

        foreach ($response_data as $friend) {
            $friendData['id'] = $friend['friend']['id'];
            $friendData['name'] = $friend['friend']['name'];
            $transformedData[] = $friendData;
        }

        return response()->json($transformedData);
    }

    public function response(Request $request)
    {
        $userId = $request->input('user_id');
        $friendId = $request->input('friend_id');

        $friendRequest = Friend::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->whereNull('accepted')
            ->first(); // Use first() instead of get()

        if ($friendRequest) {
            // Update the accepted field and save the model
            $friendRequest->accepted = now();
            $friendRequest->save();

            $message = 'Friend request accepted';
        } else {
            $message = 'No pending friend request found';
        }

        return response()->json(['message' => $message]);
    }

    public function destroy(Friend $friend)
    {
        if ($friend) {
            $friend->delete();

            $message = 'Unfriended successfully';
        } else {
            $message = 'User not found';
        }

        return response()->json($message);
    }
}
