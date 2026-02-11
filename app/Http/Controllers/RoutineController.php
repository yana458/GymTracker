<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Routine;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoutineController extends Controller
{
    /**
     * PUBLICO: /routines
     * Lista general de rutinas (sin sidebar de crear).
     */
    public function index(Request $request)
    {
        $routines = Routine::with('exercises.category')
            ->orderBy('name')
            ->paginate(10);

        // Para poder pintar "Suscrito / No suscrito" en la vista (opcional)
        $myRoutineIds = [];
        if (Auth::check()) {
            $myRoutineIds = Auth::user()
                ->routines()
                ->pluck('routines.id')
                ->all();
        }

        return view('routines.public', compact('routines', 'myRoutineIds'));
        // Si quieres reutilizar tu view actual, puedes usar routines.index y esconder el formulario con @auth.
    }

    /**
     * LOGUEADO: /my-routines
     * Este es tu antiguo index: mis rutinas + crear rutina + elegir ejercicios.
     */
    public function myIndex(Request $request)
    {
        $routines = $request->user()
            ->routines()
            ->with('exercises.category')
            ->orderBy('name')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        $exQuery = Exercise::with('category')->orderBy('name');

        if ($request->filled('cat')) {
            $exQuery->where('category_id', $request->query('cat'));
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $exQuery->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('instruction', 'like', "%{$q}%");
            });
        }

        $exercises = $exQuery->get();

        return view('routines.index', compact('routines', 'exercises', 'categories'));
    }

    /**
     * LOGUEADO: crear rutina (POST /routines)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            'exercises' => 'required|array|min:1',
            'exercises.*' => 'exists:exercises,id',

            'pivot' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $routine = Routine::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            // Suscribir al usuario actual (routine_user)
            $request->user()->routines()->syncWithoutDetaching([$routine->id]);

            // Adjuntar ejercicios con pivote (exercise_routine)
            $attach = [];
            $seq = 1;

            foreach ($validated['exercises'] as $exId) {
                $p = $validated['pivot'][$exId] ?? [];

               $attach[$exId] = [
                'sequence' => $seq++,
                'target_sets' => (int)($p['target_sets'] ?? $request->input('default_sets', 3)),
                'target_reps' => (int)($p['target_reps'] ?? $request->input('default_reps', 10)),
                'rest_seconds' => (int)($p['rest_seconds'] ?? $request->input('default_rest', 60)),
                ];

            }

            $routine->exercises()->attach($attach);

            return redirect()->route('my-routines.index')
                ->with('success', 'Rutina creada y asociada a tu usuario.');
        });
    }

    /**
     * PUBLICO: /routines/{routine}
     * Mostrar una rutina.
     */
    public function show(Routine $routine)
    {
        $routine->load('exercises.category');

        $isSubscribed = false;
        if (Auth::check()) {
            $isSubscribed = $routine->users()
                ->whereKey(Auth::id())
                ->exists();
        }

        return view('routines.show', compact('routine', 'isSubscribed'));
    }

    /**
     * PUBLICO: /routines/{routine}/exercises
     * Lista de ejercicios de una rutina.
     */
    public function exercises(Routine $routine)
    {
        $routine->load('exercises.category');
        return view('routines.exercises', compact('routine'));
    }

    /**
     * LOGUEADO: suscribirse a una rutina existente (POST /my-routines)
     * Espera routine_id en el formulario.
     */
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'routine_id' => ['required', 'exists:routines,id'],
        ]);

        $request->user()->routines()->syncWithoutDetaching([$data['routine_id']]);

        return back()->with('success', 'Rutina añadida a mis rutinas.');
    }

    /**
     * LOGUEADO: desuscribirse (DELETE /my-routines/{routine})
     */
    public function unsubscribe(Request $request, Routine $routine)
    {
        $request->user()->routines()->detach($routine->id);
        return back()->with('success', 'Rutina eliminada de mis rutinas.');
    }

    /**
     * LOGUEADO: editar rutina (solo si el usuario está suscrito)
     */
    public function edit(Routine $routine)
    {
        $isMine = $routine->users()
            ->wherePivot('user_id', Auth::id())
            ->exists();

        if (!$isMine) {
            return redirect()->route('my-routines.index')
                ->with('error', 'No tienes permiso para editar esta rutina.');
        }

        $routine->load('exercises');
        $exercises = Exercise::with('category')->orderBy('name')->get();

        return view('routines.edit', compact('routine', 'exercises'));
    }

    /**
     * LOGUEADO: actualizar rutina (solo si está suscrito)
     */
    public function update(Request $request, Routine $routine)
    {
        $isMine = $routine->users()
            ->wherePivot('user_id', Auth::id())
            ->exists();

        if (!$isMine) {
            return redirect()->route('my-routines.index')
                ->with('error', 'No tienes permiso para actualizar esta rutina.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            'exercises' => 'required|array|min:1',
            'exercises.*' => 'exists:exercises,id',
            'pivot' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($routine, $validated) {
            $routine->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            $sync = [];
            $seq = 1;

            foreach ($validated['exercises'] as $exId) {
                $p = $validated['pivot'][$exId] ?? [];

                $sync[$exId] = [
                    'sequence' => $seq++,
                    'target_sets' => (int)($p['target_sets'] ?? 3),
                    'target_reps' => (int)($p['target_reps'] ?? 10),
                    'rest_seconds' => (int)($p['rest_seconds'] ?? 60),
                ];
            }

            $routine->exercises()->sync($sync);

            return redirect()->route('my-routines.index')
                ->with('success', 'Rutina actualizada correctamente.');
        });
    }

    /**
     * LOGUEADO: borrar rutina (solo si está suscrito)
     * Si otros usuarios la usan, solo desuscribe al usuario actual.
     */
    public function destroy(Routine $routine)
    {
        $isMine = $routine->users()
            ->wherePivot('user_id', Auth::id())
            ->exists();

        if (!$isMine) {
            return redirect()->route('my-routines.index')
                ->with('error', 'No tienes permiso para eliminar esta rutina.');
        }

        if ($routine->users()->count() > 1) {
            $routine->users()->detach(Auth::id());
            return redirect()->route('my-routines.index')
                ->with('success', 'Te has desuscrito de la rutina (otros usuarios la usan).');
        }

        // Si eres el único usuario, borrado "global"
        $routine->exercises()->detach();
        $routine->users()->detach();
        $routine->delete();

        return redirect()->route('my-routines.index')
            ->with('success', 'Rutina eliminada correctamente.');
    }

    /**
     * LOGUEADO: añadir ejercicio a una rutina existente (POST /routines/{routine}/exercises)
     */
    public function attachExercise(Request $request, Routine $routine)
    {
        $isMine = $routine->users()
            ->wherePivot('user_id', Auth::id())
            ->exists();

        if (!$isMine) {
            return back()->with('error', 'No tienes permiso para modificar esta rutina.');
        }

        $data = $request->validate([
            'exercise_id'  => ['required', 'exists:exercises,id'],
            'target_sets'  => ['nullable', 'integer', 'min:1', 'max:50'],
            'target_reps'  => ['nullable', 'integer', 'min:1', 'max:200'],
            'rest_seconds' => ['nullable', 'integer', 'min:0', 'max:3600'],
            'sequence'     => ['nullable', 'integer', 'min:1'],
        ]);

        $routine->exercises()->syncWithoutDetaching([
            $data['exercise_id'] => [
                'sequence' => $data['sequence'] ?? 1,
                'target_sets' => $data['target_sets'] ?? 3,
                'target_reps' => $data['target_reps'] ?? 10,
                'rest_seconds' => $data['rest_seconds'] ?? 60,
            ],
        ]);

        return back()->with('success', 'Ejercicio añadido a la rutina.');
    }

    /**
     * LOGUEADO: quitar ejercicio (DELETE /routines/{routine}/exercises/{exercise})
     */
    public function detachExercise(Routine $routine, Exercise $exercise)
    {
        $isMine = $routine->users()
            ->wherePivot('user_id', Auth::id())
            ->exists();

        if (!$isMine) {
            return back()->with('error', 'No tienes permiso para modificar esta rutina.');
        }

        $routine->exercises()->detach($exercise->id);

        return back()->with('success', 'Ejercicio eliminado de la rutina.');
    }
}
