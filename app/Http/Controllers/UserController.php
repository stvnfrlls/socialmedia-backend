<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $response_data = User::all();

        return response()->json($response_data);
    }

    public function auth(Request $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = Auth::user();
            $token = $user->createToken('social-account')->accessToken;
            $userData = User::where('id', $user->id)->first();

            $userData['token'] = $token;

            return response()->json($userData);
        }
    }

    public function store(UserRequest $request)
    {
        $response_data = User::create($request->all());

        if ($response_data) {
            $message = 'User registered Successfully';
        } else {
            $message = 'Error occurred when registering';
        }

        return response()->json($message);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(UserRequest $request, User $user)
    {
        if ($user) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->save();

            $message = 'User updated successfully';
        } else {
            $message = 'User not found';
        }

        return response()->json($message);
    }

    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();

            $message = 'User deleted successfully';
        } else {
            $message = 'User not found';
        }

        return response()->json($message);
    }
}
