<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'icon_path' => $request->input('icon_path') ?: 'icons/default.svg',
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon_path' => 'required|string|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->merge([
            'icon_path' => $request->input('icon_path') ?: 'icons/default.svg',
        ]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon_path' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
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
}
