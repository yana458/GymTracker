<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoutineResource;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutineController extends Controller
{
        public function index(Request $request)
    {
        $routines = $request->user()
            ->routines()
            ->with('exercises.category')
            ->orderBy('name')
            ->paginate(10);

        return RoutineResource::collection($routines);
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],

            // Vue envía: exercises: [{id, target_sets, target_reps, rest_seconds}, ...]
            'exercises' => ['required', 'array', 'min:1'],
            'exercises.*.id' => ['required', 'exists:exercises,id'],
            'exercises.*.target_sets' => ['nullable', 'integer', 'min:1', 'max:50'],
            'exercises.*.target_reps' => ['nullable', 'integer', 'min:1', 'max:200'],
            'exercises.*.rest_seconds' => ['nullable', 'integer', 'min:0', 'max:3600'],
        ]);

        // 1) Crear la rutina (solo name/description)
        $routine = Routine::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // 2) Asociarla al usuario en routine_user
        // (puedes usar $request->user()->id si prefieres)
        $routine->users()->attach(Auth::id());

        // 3) Asociar ejercicios en exercise_routine con pivote
        $pivot = [];
        foreach ($validated['exercises'] as $i => $e) {
            $pivot[$e['id']] = [
                'sequence' => $i + 1,                 // opcional, si tu tabla lo tiene
                'target_sets' => $e['target_sets'] ?? 3,
                'target_reps' => $e['target_reps'] ?? 10,
                'rest_seconds' => $e['rest_seconds'] ?? 60,
            ];
        }

        $routine->exercises()->attach($pivot);

        return (new RoutineResource($routine->load('exercises.category')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para ver esta rutina'], 403);
        }

        return new RoutineResource($routine->load('exercises.category'));
    }

    public function update(Request $request, Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) {
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
        foreach ($validated['exercises'] as $e) {
            $pivot[$e['id']] = [
                'target_sets' => $e['target_sets'] ?? 3,
                'target_reps' => $e['target_reps'] ?? 10,
                'rest_seconds' => $e['rest_seconds'] ?? 60,
            ];
        }

        $routine->exercises()->sync($pivot);

        return new RoutineResource($routine->fresh()->load('exercises.category'));
    }

    public function destroy(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta rutina'], 403);
        }

        $routine->exercises()->detach();
        $routine->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);
    }
}
