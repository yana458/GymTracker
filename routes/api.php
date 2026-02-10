<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoutineController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExerciseController;


Route::name('api.')->group(function () {

    // Auth API (públicas)
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protegidas por Sanctum
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthController::class, 'me'])->name('me');

        // Resources API (pi.categories.index, api.exercises.index, etc.)
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('exercises', ExerciseController::class);
        Route::apiResource('routines', RoutineController::class);
    });
});
