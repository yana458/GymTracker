<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Category;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::with('category')->orderBy('name')->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('exercises.index', compact('exercises', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'instruction' => 'required|string',
        ]);

        Exercise::create($validated);

        return redirect()->route('exercises.index')
            ->with('success', 'Ejercicio creado correctamente.');
    }

    public function edit(Exercise $exercise)
    {
        $categories = Category::orderBy('name')->get();
        return view('exercises.edit', compact('exercise', 'categories'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'instruction' => 'required|string',
        ]);

        $exercise->update($validated);

        return redirect()->route('exercises.index')
            ->with('success', 'Ejercicio actualizado correctamente.');
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return redirect()->route('exercises.index')
            ->with('success', 'Ejercicio eliminado correctamente.');
    }

    public function show(Exercise $exercise)
    {
        $exercise->load('category');
        return view('exercises.show', compact('exercise'));
    }

}
