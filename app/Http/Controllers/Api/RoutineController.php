<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoutineResource;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoutineController extends Controller
{
    /**
     * PUBLIC: listado general de rutinas
     * GET /api/routines
     */
    public function index()
    {
        $routines = Routine::with('exercises.category')
            ->orderBy('name')
            ->paginate(10);

        return RoutineResource::collection($routines);
    }

    /**
     * PUBLIC: ver una rutina
     * GET /api/routines/{routine}
     */
    public function show(Routine $routine)
    {
        return new RoutineResource($routine->load('exercises.category'));
    }

    /**
     * PUBLIC: ejercicios de una rutina
     * GET /api/routines/{routine}/exercises
     */
    public function exercises(Routine $routine)
    {
        return response()->json(
            $routine->load('exercises.category')->exercises
        );
    }

    /**
     * PROTECTED: mis rutinas (usuario autenticado)
     * GET /api/my-routines
     */
    public function myRoutines(Request $request)
    {
        $routines = $request->user()
            ->routines()
            ->with('exercises.category')
            ->orderBy('name')
            ->paginate(10);

        return RoutineResource::collection($routines);
    }

    /**
     * PROTECTED: crear rutina (y asociarla al usuario)
     * POST /api/routines
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

    return DB::transaction(function () use ($validated) {

        $routine = Routine::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(), 
        ]);

        // Suscribir al usuario (si tu enunciado usa routine_user)
        $routine->users()->attach(Auth::id());

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
            ->with('success', 'Rutina creada correctamente.');
    });
}

    /**
     * PROTECTED: actualizar rutina (solo si es del usuario)
     * PUT /api/routines/{routine}
     */
    public function update(Request $request, Routine $routine)
    {
        // El usuario debe tener esa rutina en routine_user
        if (!$routine->users()->where('users.id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'No tienes permiso para modificar esta rutina'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'exercises' => ['required', 'array', 'min:1'],
            'exercises.*.id' => ['required', 'exists:exercises,id'],
            'exercises.*.target_sets' => ['nullable', 'integer', 'min:1', 'max:50'],
            'exercises.*.target_reps' => ['nullable', 'integer', 'min:1', 'max:200'],
            'exercises.*.rest_seconds' => ['nullable', 'integer', 'min:0', 'max:3600'],
        ]);

        $routine->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $pivot = [];
        foreach ($validated['exercises'] as $i => $e) {
            $pivot[$e['id']] = [
                'sequence' => $i + 1,
                'target_sets' => $e['target_sets'] ?? 3,
                'target_reps' => $e['target_reps'] ?? 10,
                'rest_seconds' => $e['rest_seconds'] ?? 60,
            ];
        }

        $routine->exercises()->sync($pivot);

        return new RoutineResource($routine->fresh()->load('exercises.category'));
    }

    /**
     * PROTECTED: borrar rutina (solo si es del usuario)
     * DELETE /api/routines/{routine}
     */
    public function destroy(Request $request, Routine $routine)
    {
        if (!$routine->users()->where('users.id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta rutina'], 403);
        }

        $routine->exercises()->detach();
        $routine->users()->detach();   // también se quita de routine_user
        $routine->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);
    }

    /**
     * PROTECTED: suscribirse a una rutina (añadir a mis rutinas)
     * POST /api/my-routines  (o /api/my-routines/{routine} según tus rutas)
     */
    public function subscribe(Request $request, Routine $routine)
    {
        $routine->users()->syncWithoutDetaching([$request->user()->id]);
        return response()->json(['message' => 'Añadida a mis rutinas'], 200);
    }

    /**
     * PROTECTED: quitar de mis rutinas
     * DELETE /api/my-routines/{routine}
     */
    public function unsubscribe(Request $request, Routine $routine)
    {
        $routine->users()->detach($request->user()->id);
        return response()->json(['message' => 'Eliminada de mis rutinas'], 200);
    }
}
