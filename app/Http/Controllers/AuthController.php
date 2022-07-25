<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'user',
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('MyApp')->plainTextToken;
        $res = [
            'token' => $token,
            'user' => $user
        ];
        return response()->json($res, 201);
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('MyApp')->plainTextToken;
                $res = [
                    'token' => $token,
                    'user' => $user
                ];
                return response()->json($res, 200);
            } else
                return response()->json(['error' => 'Invalid password'], 401);
        } else
            return response()->json(['error' => 'User not found'], 404);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    public function test()
    {
        # code...
        return response()->json(['message' => 'Welcome to the API']);
    }
}
