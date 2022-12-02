<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string|required',
                'email' => 'email|required|unique:users',
                'password' => 'required|confirmed'
            ]
        );



        if ($validator->fails()) {
            return response($validator->errors(), 406);
        }
        $validate = $validator->validated();

        $validate['password'] = Hash::make($validate['password']);
        $user = User::create($validate);
        if (isset($user)) {
            return response($user, 201);
        } else {
            return response(['message' => "error in user creation"], 417);
        }
    }


    public function login(Request $request)
    {
        $validated = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            // creation du token
            $token = $request->user()->createToken('ShopApi')->plainTextToken;
            return response(['user' => $user, 'token' => $token]);

        } else {
            return response(["status" => "error", "message" => "Wrong credentials"]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        // suppression du token
        $request->user()->tokens()->delete();
        $request->session()->regenerateToken();

    }
}