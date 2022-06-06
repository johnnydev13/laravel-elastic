<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TodoController;

/**
 * Post Routes - Without Authentication
 */
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::post('posts', [PostController::class, 'store'])->name('posts.store');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('posts/{post}', [PostController::class, 'delete'])->name('posts.delete');
Route::get('posts-search', [PostController::class, 'search'])->name('posts.search');

/**
 * Todo Routes - Without Authentication
 */
Route::get('todos', [TodoController::class, 'index'])->name('todos.index');
Route::post('todos', [TodoController::class, 'store'])->name('todos.store');
Route::get('todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
Route::put('todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
Route::delete('todos/{todo}', [TodoController::class, 'delete'])->name('todos.delete');
Route::get('todos-search', [TodoController::class, 'search'])->name('todos.search');
