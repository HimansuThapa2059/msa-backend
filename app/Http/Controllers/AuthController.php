<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller 
{
    public function register(Request $req){

        $fields = $req->validate([
            'name' => 'required | max:255',
            'email' => 'required | email | unique:users',
            'password' => 'required | confirmed',
        ]);


        $user = User::create($fields);

        $token = $user->createToken($req->name);

    return ['user' => $user, 'token' => $token->plainTextToken];
    }

    public function login(Request $req){
        $valid = Validator::make($req->all(), [
            'email' => 'required | email | exists:users',
            'password' => 'required',
        ]);

        if($valid->fails()){
            return response()->json([
                'message' => 'Email not found',
                'error' => $valid->messages()
            ], 400);
        }

        $user = User::where('email', $req->email)->first();

        if(!$user || !Hash::check($req->password, $user->password)){
            return ['message' => 'Provided credentials is incorrect'];
        }

        $token = $user->createToken($user->name);

        return ['user' => $user, 'token' => $token->plainTextToken];
    }

    public function logout(Request $req){

        $req->user()->tokens()->delete();

        return ['message' => 'Logout sucessfully'];
    }
}
