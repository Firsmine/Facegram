<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $followingIds = $user->following()->pluck('users.id');

        $posts = Post::with('user')->where(function($query) use ($user, $followingIds){
            $query->where('user_id', $user->id)->orWhereIn('user_id', $followingIds);

        })->latest()->get();

        return response()->json([
            'success'=>true,
            'message'=>'Post retrieved success',
            'data'=>$posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'caption'=>'nullable|string|max:1000',
            'image'=>'required|string'
        ]);
        $image = $request->image;

        $post = Post::create([
            'user_id'=>$request->user()->id,
            'caption'=>$request->caption,
            'image'=>$image
        ]);
        $post->load('user');

        return response()->json([
            'success'=>true,
            'message'=>'Post Success',
            'data'=>$post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id){
            return response()->json([
                'success'=>false,
                'message'=>'You are not allowed to delete this post'
            ]);
        }
        $post->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Post Deleted',
        ]);
    }
}
