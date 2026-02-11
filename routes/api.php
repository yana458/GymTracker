<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\RoutineController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::name('api.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PUBLIC (sin token)
    |--------------------------------------------------------------------------
    */
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login',    [AuthController::class, 'login'])->name('login');

    // Categories (público)
    Route::get('/categories',                       [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}',            [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/exercises',  [CategoryController::class, 'exercises'])->name('categories.exercises');

    // Exercises (público)
    Route::get('/exercises',            [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');

    // Routines (público)
    Route::get('/routines',                   [RoutineController::class, 'publicIndex'])->name('routines.index');
    Route::get('/routines/{routine}',         [RoutineController::class, 'publicShow'])->name('routines.show');
    Route::get('/routines/{routine}/exercises',[RoutineController::class, 'exercises'])->name('routines.exercises');


    /*
    |--------------------------------------------------------------------------
    | PROTECTED (con token)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me',      [AuthController::class, 'me'])->name('me');

        // CRUD protegido (solo store/update/destroy para no duplicar index/show públicos)
        Route::apiResource('categories', CategoryController::class)->only(['store','update','destroy']);
        Route::apiResource('exercises',  ExerciseController::class)->only(['store','update','destroy']);
        Route::apiResource('routines',   RoutineController::class)->only(['store','update','destroy']);

        // Añadir / quitar ejercicio a rutina (pivot)
        Route::post('/routines/{routine}/exercises', [RoutineController::class, 'attachExercise'])
            ->name('routines.exercises.attach');

        Route::delete('/routines/{routine}/exercises/{exercise}', [RoutineController::class, 'detachExercise'])
            ->name('routines.exercises.detach');

        // Mis rutinas (tabla)
        Route::get('/my-routines', [RoutineController::class, 'myRoutines'])->name('my-routines.index');
        Route::post('/my-routines', [RoutineController::class, 'subscribe'])->name('my-routines.store');
        Route::delete('/my-routines/{routine}', [RoutineController::class, 'unsubscribe'])->name('my-routines.destroy');
    });
});
