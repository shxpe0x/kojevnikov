<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\FriendshipController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Posts
    Route::apiResource('posts', PostController::class)->except(['index']);
    Route::get('/feed', [PostController::class, 'index']);
    Route::post('/posts/{uuid}/like', [PostController::class, 'like']);

    // Comments
    Route::get('/posts/{postUuid}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{postUuid}/comments', [CommentController::class, 'store']);
    Route::delete('/posts/{postUuid}/comments/{commentId}', [CommentController::class, 'destroy']);

    // Friendships
    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendshipController::class, 'friends']);
        Route::get('/pending', [FriendshipController::class, 'pending']);
        Route::post('/request/{friendId}', [FriendshipController::class, 'sendRequest']);
        Route::post('/accept/{friendshipId}', [FriendshipController::class, 'acceptRequest']);
        Route::post('/reject/{friendshipId}', [FriendshipController::class, 'rejectRequest']);
        Route::delete('/{friendshipId}', [FriendshipController::class, 'removeFriend']);
    });

    // Follows
    Route::prefix('follow')->group(function () {
        Route::get('/followers', [FollowController::class, 'followers']);
        Route::get('/following', [FollowController::class, 'following']);
        Route::post('/{userId}', [FollowController::class, 'follow']);
        Route::delete('/{userId}', [FollowController::class, 'unfollow']);
    });

    // Profile
    Route::get('/profile/{username}', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/profile/{username}/posts', [ProfileController::class, 'posts']);

    // Search
    Route::get('/search/users', [SearchController::class, 'users']);
});
