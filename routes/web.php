<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Exercise;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\RoutineController ;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// HOME invitado vs logueado
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



    // Dashboard (lo que trae Breeze)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');


    // Perfil (lo que trae Breeze)
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });


    // Secciones CRUD (protegidas con auth)
    Route::middleware('auth')->group(function () {

        // Categorías
        Route::resource('categories', CategoryController::class)->except(['create', 'show']);

        // Ejercicios
        Route::resource('exercises', ExerciseController::class)->except(['create', 'show']);

        // Rutinas
        Route::resource('routines', RoutineController::class)->except(['create', 'show']);
    });


    // Rutas de autenticación Breeze (login, register, logout, etc.)
    require __DIR__.'/auth.php';
