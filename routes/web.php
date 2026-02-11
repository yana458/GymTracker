<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use App\Models\Exercise;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\RoutineController;

/*
|--------------------------------------------------------------------------
| HOME invitado vs logueado
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        $stats = [
            'my_routines' => $user->routines()->count(),
            'total_exercises' => Exercise::count(),
            'total_categories' => Category::count(),
        ];

        $recentRoutines = $user->routines()
            ->withCount('exercises')
            ->orderByDesc('routines.created_at')
            ->take(5)
            ->get();

        return view('home-auth', compact('stats', 'recentRoutines'));
    }

    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard / Perfil (Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rutas PÚBLICAS (solo lectura) 
|--------------------------------------------------------------------------
| GET /categories, /exercises, /routines y sub-listados
*/
Route::resource('categories', CategoryController::class)->only(['index','show']);
Route::get('categories/{category}/exercises', [CategoryController::class, 'exercises'])
    ->name('categories.exercises');

Route::resource('exercises', ExerciseController::class)->only(['index','show']);

Route::resource('routines', RoutineController::class)->only(['index','show']);
Route::get('routines/{routine}/exercises', [RoutineController::class, 'exercises'])
    ->name('routines.exercises');

/*
|--------------------------------------------------------------------------
| Rutas PROTEGIDAS (crear/editar/borrar + my-routines) — “Token” => sesión auth
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified'])->group(function () {

    // Categories (solo escritura)
    Route::resource('categories', CategoryController::class)->except(['index','show','create']);

    // Exercises (solo escritura)
    Route::resource('exercises', ExerciseController::class)->except(['index','show','create']);

    // Routines (solo escritura)
    Route::resource('routines', RoutineController::class)->except(['index','show','create']);

    // Añadir / quitar ejercicio a una rutina
    Route::post('routines/{routine}/exercises', [RoutineController::class, 'attachExercise'])
        ->name('routines.exercises.attach');
    Route::delete('routines/{routine}/exercises/{exercise}', [RoutineController::class, 'detachExercise'])
        ->name('routines.exercises.detach');

    // My-routines (suscribirse / desuscribirse)
    Route::get('/my-routines', [RoutineController::class, 'myIndex'])->name('my-routines.index');
    Route::post('/my-routines', [RoutineController::class, 'subscribe'])->name('my-routines.subscribe');
    Route::delete('/my-routines/{routine}', [RoutineController::class, 'unsubscribe'])->name('my-routines.unsubscribe');
});

require __DIR__.'/auth.php';
