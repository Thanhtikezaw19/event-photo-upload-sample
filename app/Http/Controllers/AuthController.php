<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $validatedData = $request->validated();
        //dd($validatedData);

        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'email_verified_at' => now(),
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        // email verification for later
        //$user->sendEmailVerificationNotification();

        return response()->json(['message' => 'User registered successfully. Please verify your email address.'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            if (!$user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Please verify your email address.'], 403);
            }

            $token = $user->createToken('authToken')->accessToken;
            $data['token_type'] = 'Bearer';
            $data['access_token'] = $token;
            $data['user'] = $user;

            $response = [
                'status' => 200,
                'message' => 'Successfully Logged In!',
                'data' => $data,
            ];

            return response()->json($response);
            //return response()->json([['message' => "Successfully Logged In"], $data, 200]);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json(['status'=> true, 'message' => 'Successfully logged out!']);
    }

}
