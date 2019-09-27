<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response(['message' => 'User does not exist.'], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response(['message' => 'Incorrect password.'], 422);
        }

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response(['token' => $token], 200);
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'You have been successfully logged out!'], 200);
    }
}
