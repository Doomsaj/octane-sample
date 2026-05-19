<?php

use App\Http\Controllers\BenchmarkController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [BenchmarkController::class, 'health']);
Route::get('/users', [BenchmarkController::class, 'users']);
Route::get('/users/{user}', [BenchmarkController::class, 'user']);
Route::get('/posts', [BenchmarkController::class, 'posts']);
Route::get('/stats', [BenchmarkController::class, 'stats']);
