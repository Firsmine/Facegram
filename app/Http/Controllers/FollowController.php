<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class FollowController extends Controller
{
    public function follow(Request $request, string $username){
        $user = $request->user();
        $targetUser = User::where('username', $username)->first();
        if(!$targetUser){
            return response()->json([
                'success'=>false,
                'message'=>'User not found',
            ], 404);
        }
        if ($targetUser->id === $user->id){
            return response()->json([
                'success'=>false,
                'message'=>'You cannot follow yourself'
            ], 422);
        }
        if ($user->following()->where('users.id', $targetUser->id)->exists()){
            return response()->json([
                'success'=>false,
                'message'=>'You already follow this user',
            ], 409);
        }
        $user->following()->attach($targetUser->id);
        return response()->json([
            'success'=>true,
            'message'=>'User followed success',
            'data'=>[
                'username'=>$targetUser->username,
            ]
        ], 201);
    }
    public function unfollow(Request $request, string $username){
        $user = $request->user();
        $targetUser = User::where('username', $username)->first();

        if(!$targetUser){
            return response()->json([
                'success'=>false,
                'message'=>'User not found'
            ], 404);
        }
        $deleted = $user->following()->detach($targetUser->id);

        if($deleted === 0){
            return response()->json([
                'success'=>false,
                'message'=>'You do not follow this user',
            ], 404);
        }
        return response()->json([
            'success'=>true,
            'message'=>'User unfollowed success'
        ]);
    }
    public function following(Request $request){
        $users = $request->user()->following()->select([
            'users.id', 'users.name', 'users.username', 'users.email',
        ])->get();

        return response()->json([
            'success'=>true,
            'message'=>'Following list retrieved success',
            'data'=>$users,
        ]);
    }
}
