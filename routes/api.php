<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;

Route::prefix('v1')->group(function(){
    // AUTH
    Route::prefix('auth')->group(function(){
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class,  'login']);

        Route::middleware('auth:sanctum')->group(function(){
            // AUTH
            Route::post('/logout', [UserController::class, 'logout']);
            Route::get('/me', [UserController::class, 'me']);

            // POSTS
            Route::get('/posts', [PostController::class, 'index']);
            Route::post('/posts', [PostController::class, 'store']);
            Route::delete('/posts/{post}', [PostController::class, 'destroy']);

            // FOLLOW
            Route::post('/users/{username}/follow', [FollowController::class, 'follow']);
            Route::delete('/users/{username}/unfollow', [FollowController::class, 'unfollow']);
            Route::get('/following', [FollowController::class, 'following']);
        });
    });
});
