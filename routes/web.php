<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Auth::routes();

#  Route accessible only to authenticated users
Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/people', [HomeController::class, 'search'])->name('search');

    Route::group(['prefix' => 'post', 'as' => 'post.'], function() {
        // any routes defined within this Route::group will automatically have /post/ prefixed to their URLs and will have aliases with the post. prefix.

        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/show', [PostController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [PostController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'comment', 'as' => 'comment.'], function() {
        Route::post('/{post_id}/store', [CommentController::class, 'store'])->name('store');
        Route::delete('/{id}/destroy', [CommentController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
        Route::get('/{id}/show', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/{id}/followers', [ProfileController::class, 'followers'])->name('followers');
        Route::get('/{id}/following', [ProfileController::class, 'following'])->name('following');
    });

    Route::group(['prefix' => 'like', 'as' => 'like.'], function() {
        Route::post('/{id}/store', [LikeController::class, 'store'])->name('store');
        Route::delete('/{post_id}/destroy', [LikeController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'follow', 'as' => 'follow.'], function() {
        Route::post('/{user_id}/store', [FollowController::class, 'store'])->name('store');
        Route::delete('/{user_id}/destroy', [FollowController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function() {
       # User
       Route::get('/users', [UsersController::class, 'index'])->name('users');
       Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
       Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');

       # Post
       Route::get('/posts', [PostsController::class, 'index'])->name('posts');
       Route::delete('/posts/{id}/hide', [PostsController::class, 'hide'])->name('posts.hide');
       Route::patch('/posts/{id}/unhide', [PostsController::class, 'unhide'])->name('posts.unhide');

       # Category
       Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
       Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
       Route::patch('/categories/{id}/update', [CategoriesController::class, 'update'])->name('categories.update');
       Route::delete('/categories/{id}/destroy', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    });
});