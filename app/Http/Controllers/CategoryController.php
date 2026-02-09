<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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
        $file->move(public_path('icons/uploads'), $filename);

        Category::create([
            'name' => $validated['name'],
            'icon_path' => 'icons/uploads/' . $filename, // ✅ string en BD
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
            // (Opcional) borrar icono anterior si existe y era de uploads
            if ($category->icon_path && str_starts_with($category->icon_path, 'icons/uploads/')) {
                $old = public_path($category->icon_path);
                if (File::exists($old)) File::delete($old);
            }

            $file = $request->file('icon');
            $ext = $file->getClientOriginalExtension();
            $filename = Str::slug($validated['name']) . '-' . time() . '.' . $ext;
            $file->move(public_path('icons/uploads'), $filename);

            $data['icon_path'] = 'icons/uploads/' . $filename;
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
