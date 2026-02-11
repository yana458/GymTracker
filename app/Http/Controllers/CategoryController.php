<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'icon' => ['nullable','file','mimes:png,jpg,jpeg,svg,webp','max:2048'],
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public'); // icons/xxx.png
        }

        \App\Models\Category::create([
            'name' => $validated['name'],
            'icon_path' => $iconPath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, \App\Models\Category $category)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'icon' => ['nullable','file','mimes:png,jpg,jpeg,svg,webp','max:2048'],
        ]);

        if ($request->hasFile('icon')) {
            // borrar icono anterior si existe
            if ($category->icon_path) {
                Storage::disk('public')->delete($category->icon_path);
            }
            $category->icon_path = $request->file('icon')->store('icons', 'public');
        }

        $category->name = $validated['name'];
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        // opción “segura”: no borrar si tiene ejercicios
        if ($category->exercises()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'No puedes borrar una categoría con ejercicios asociados.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function exercises(Category $category)
    {
        $exercises = $category->exercises()->orderBy('name')->paginate(10);
        return view('categories.exercises', compact('category','exercises'));
    }

}
