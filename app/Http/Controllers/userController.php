<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=> 'required|string',
            'username'=>'required|string|max:50|unique:users,username',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>$request->password
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'=>true,
            'message'=>'Registration Success',
            'data'=>[
                'user'=>$user,
                'token'=>$token,
            ],
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success'=>false,
                'message'=>'Login failed. Email or password is wrong.'
            ], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'=>true,
            'message'=>'Login Success',
            'data'=>[
                'user'=>$user,
                'token'=>$token,
            ]
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Logout Success'
        ]);
    }

    public function me(Request $request){
        return response()->json([
            'success'=>true,
            'data'=>$request->user()
        ]);
    }
}
