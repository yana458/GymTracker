<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoutineController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExerciseController;

// Auth API
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Obtener el usuario autenticado
    Route::get('/me', [AuthController::class, 'me']);
    

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('exercises', ExerciseController::class);
    Route::apiResource('routines', RoutineController::class);
});
