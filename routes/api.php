<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::post('login', [UserController::class, 'auth'])->name('login');

    Route::prefix('register-user')->group(function () {
        Route::post('create', [UserController::class, 'store']);
    });
});

Route::middleware('api')->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('post/{post}', [PostController::class, 'show']);
        Route::get('author/{authorId}', [PostController::class, 'list']);
        Route::post('create', [PostController::class, 'store']);
        Route::post('{post}', [PostController::class, 'update']);
        Route::delete('{post}', [PostController::class, 'destroy']);
    });

    Route::prefix('comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('{comment}', [CommentController::class, 'show']);
        Route::post('/create', [CommentController::class, 'store']);
        Route::post('{comment}', [CommentController::class, 'update']);
        Route::delete('{comment}', [CommentController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('{user}', [UserController::class, 'show']);
        Route::post('{user}', [UserController::class, 'update']);
        Route::delete('{user}', [UserController::class, 'destroy']);
    });

    Route::prefix('friends')->group(function () {
        Route::get('{friend}', [FriendController::class, 'show']);
        Route::get('request/{id}', [FriendController::class, 'showrequest']);
        Route::post('friend', [FriendController::class, 'store']);
        Route::post('response', [FriendController::class, 'response']);
        Route::delete('{friend}', [FriendController::class, 'destroy']);
    });

    Route::prefix('/conversation')->group(function () {
        Route::get('{sender}', [ConversationController::class, 'list']);
        Route::get('{sender}/{receiver}', [ConversationController::class, 'show']);
        Route::post('send', [ConversationController::class, 'store']);
        Route::delete('{conversation}', [ConversationController::class, 'destroy']);
    });
});
