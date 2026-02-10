<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $q = Exercise::with('category')->orderBy('name');

        // Filtro por categoría: /api/exercises?category_id=3
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->query('category_id'));
        }

        // Búsqueda simple: /api/exercises?search=press
        if ($request->filled('search')) {
            $s = $request->query('search');
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                  ->orWhere('instruction', 'like', "%{$s}%");
            });
        }

        return ExerciseResource::collection(
            $q->paginate(10)->withQueryString()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'instruction' => ['required', 'string'],
        ]);

        $exercise = Exercise::create($validated);

        return (new ExerciseResource($exercise->load('category')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Exercise $exercise)
    {
        return new ExerciseResource($exercise->load('category'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'instruction' => ['required', 'string'],
        ]);

        $exercise->update($validated);

        return new ExerciseResource($exercise->fresh()->load('category'));
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);
    }
}
