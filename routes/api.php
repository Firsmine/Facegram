<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('v1')->group(function(){
    // AUTH
    Route::prefix('auth')->group(function(){
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class,  'login']);

        Route::middleware('auth:sanctum')->group(function(){
            // AUTH
            Route::post('/logout', [UserController::class, 'logout']);
            Route::get('/me', [UserController::class, 'me']);
        });


        // POSTS

    });
});
