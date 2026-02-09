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
   public function index(Request $request)
    {
        $routines = Auth::user()
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            'exercises' => 'required|array|min:1',
            'exercises.*' => 'exists:exercises,id',

            'pivot' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($validated) {
            $routine = Routine::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            // Suscribir al usuario actual (routine_user)
            Auth::user()->routines()->attach($routine->id);

            // Adjuntar ejercicios con pivote (exercise_routine)
            $attach = [];
            $seq = 1;

            foreach ($validated['exercises'] as $exId) {
                $p = $validated['pivot'][$exId] ?? [];

                $attach[$exId] = [
                    'sequence' => $seq++,
                    'target_sets' => (int)($p['target_sets'] ?? 3),
                    'target_reps' => (int)($p['target_reps'] ?? 10),
                    'rest_seconds' => (int)($p['rest_seconds'] ?? 60),
                ];
            }

            $routine->exercises()->attach($attach);

            return redirect()->route('routines.index')
                ->with('success', 'Rutina creada y asociada a tu usuario.');
        });
    }

    public function edit(Routine $routine)
    {
        // Solo si el usuario está suscrito a la rutina
        $isMine = $routine->users()->where('user_id', Auth::id())->exists();
        if (!$isMine) {
            return redirect()->route('routines.index')->with('error', 'No tienes permiso para editar esta rutina.');
        }

        $routine->load('exercises');
        $exercises = Exercise::with('category')->orderBy('name')->get();

        return view('routines.edit', compact('routine', 'exercises'));
    }

    public function update(Request $request, Routine $routine)
    {
        $isMine = $routine->users()->where('user_id', Auth::id())->exists();
        if (!$isMine) {
            return redirect()->route('routines.index')->with('error', 'No tienes permiso para actualizar esta rutina.');
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

            return redirect()->route('routines.index')
                ->with('success', 'Rutina actualizada correctamente.');
        });
    }

    public function destroy(Routine $routine)
    {
        $isMine = $routine->users()->where('user_id', Auth::id())->exists();
        if (!$isMine) {
            return redirect()->route('routines.index')->with('error', 'No tienes permiso para eliminar esta rutina.');
        }

        // Si hay más usuarios suscritos, en vez de borrar “global”, solo me desuscribo
        if ($routine->users()->count() > 1) {
            $routine->users()->detach(Auth::id());
            return redirect()->route('routines.index')
                ->with('success', 'Te has desuscrito de la rutina (no se borró porque otros usuarios la usan).');
        }

        $routine->delete();

        return redirect()->route('routines.index')
            ->with('success', 'Rutina eliminada correctamente.');
    }
}
