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
        'name' => ['required','string','max:255'],
        'description' => ['nullable','string'],

        // ✅ exercises como ARRAY DE OBJETOS
        'exercises' => ['required','array','min:1'],
        'exercises.*.id' => ['required','integer','exists:exercises,id'],
        'exercises.*.sets' => ['required','integer','min:1'],
        'exercises.*.reps' => ['required','integer','min:1'],
        'exercises.*.rest_seconds' => ['required','integer','min:0'],
    ]);

    $routine = Routine::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
    ]);

    // ✅ (recomendado) si la crea un usuario, la añadimos a "mis rutinas"
    $request->user()->routines()->attach($routine->id);

    // ✅ sync del pivot exercise_routine con sets/reps/rest_seconds
    $sync = collect($validated['exercises'])->mapWithKeys(function ($e) {
        return [
            $e['id'] => [
                'sets' => $e['sets'],
                'reps' => $e['reps'],
                'rest_seconds' => $e['rest_seconds'],
            ]
        ];
    })->toArray();

    $routine->exercises()->sync($sync);

    return new RoutineResource($routine->load('exercises'));
}

    /**
     * PROTECTED: actualizar rutina (solo si es del usuario)
     * PUT /api/routines/{routine}
     */
    public function update(Request $request, Routine $routine)
{
    $validated = $request->validate([
        'name' => ['sometimes','required','string','max:255'],
        'description' => ['sometimes','nullable','string'],

        'exercises' => ['sometimes','array','min:1'],
        'exercises.*.id' => ['required_with:exercises','integer','exists:exercises,id'],
        'exercises.*.sets' => ['required_with:exercises','integer','min:1'],
        'exercises.*.reps' => ['required_with:exercises','integer','min:1'],
        'exercises.*.rest_seconds' => ['required_with:exercises','integer','min:0'],
    ]);

    $routine->update([
        'name' => $validated['name'] ?? $routine->name,
        'description' => $validated['description'] ?? $routine->description,
    ]);

    if (isset($validated['exercises'])) {
        $sync = collect($validated['exercises'])->mapWithKeys(function ($e) {
            return [
                $e['id'] => [
                    'sets' => $e['sets'],
                    'reps' => $e['reps'],
                    'rest_seconds' => $e['rest_seconds'],
                ]
            ];
        })->toArray();

        $routine->exercises()->sync($sync);
    }

    return new RoutineResource($routine->fresh()->load('exercises'));
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
