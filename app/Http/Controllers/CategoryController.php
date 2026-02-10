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
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'required|file|max:2048|mimes:png,jpg,jpeg,svg', // 2MB
        ]);

        $file = $request->file('icon');
        $ext = $file->getClientOriginalExtension();

        $filename = Str::slug($validated['name']) . '-' . time() . '.' . $ext;
        $path = $file->storeAs('icons', $filename, 'public');

        Category::create([
            'name' => $validated['name'],
            'icon_path' =>  $path, 
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|file|max:2048|mimes:png,jpg,jpeg,svg',
        ]);

        $data = ['name' => $validated['name']];

        if ($request->hasFile('icon')) {
            // (Opcional) borrar icono anterior si existe en disk public
            if ($category->icon_path && Storage::disk('public')->exists($category->icon_path)) {
                Storage::disk('public')->delete($category->icon_path);
            }

            $file = $request->file('icon');
            $ext = $file->getClientOriginalExtension();
            $filename = Str::slug($validated['name']) . '-' . time() . '.' . $ext;
           

            $data['icon_path'] =  $file->storeAs('icons', $filename, 'public');
        }

        $category->update($data);

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
