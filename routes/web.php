<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostAccessRequestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;

Route::get('/', [PostController::class, 'index'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/followers', [UserController::class, 'followers'])->name('users.followers');
Route::get('/users/{user}/following', [UserController::class, 'following'])->name('users.following');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/feed', FeedController::class)->name('feed.index');
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.mine');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])->where('post', '^(?!create$).*')->name('posts.edit');
    Route::put('/posts/{post:slug}', [PostController::class, 'update'])->where('post', '^(?!create$).*')->name('posts.update');
    Route::delete('/posts/{post:slug}', [PostController::class, 'destroy'])->where('post', '^(?!create$).*')->name('posts.destroy');
    Route::post('/posts/{post:slug}/access-requests', [PostAccessRequestController::class, 'store'])
        ->middleware('throttle:access-requests')
        ->name('posts.access-requests.store');
    Route::get('/access-requests', [PostAccessRequestController::class, 'index'])->name('access-requests.index');
    Route::patch('/access-requests/{accessRequest}', [PostAccessRequestController::class, 'update'])->name('access-requests.update');
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->middleware('throttle:follows')->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('users.unfollow');
});

Route::get('/posts/{post:slug}', [PostController::class, 'show'])->where('post', '^(?!create$).*')->name('posts.show');
