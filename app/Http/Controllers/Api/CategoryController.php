<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::orderBy('name')->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'icon' => ['required', 'file', 'max:2048', 'mimes:png,jpg,jpeg,svg'],
        ]);

        $file = $request->file('icon');
        $filename = Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();

        // Guarda en storage/app/public/icons/...
        $path = $file->storeAs('icons', $filename, 'public');

        $category = Category::create([
            'name' => $validated['name'],
            'icon_path' => $path, // ej: icons/pecho-123.svg
        ]);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'icon' => ['nullable', 'file', 'max:2048', 'mimes:png,jpg,jpeg,svg'],
        ]);

        $data = ['name' => $validated['name']];

        if ($request->hasFile('icon')) {
            // borrar icono anterior si existe
            if ($category->icon_path && Storage::disk('public')->exists($category->icon_path)) {
                Storage::disk('public')->delete($category->icon_path);
            }

            $file = $request->file('icon');
            $filename = Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $data['icon_path'] = $file->storeAs('icons', $filename, 'public');
        }

        $category->update($data);

        return new CategoryResource($category->fresh());
    }

    public function destroy(Category $category)
    {
        if ($category->icon_path && Storage::disk('public')->exists($category->icon_path)) {
            Storage::disk('public')->delete($category->icon_path);
        }

        $category->delete();

        return response()->json(['message' => 'Eliminado correctamente'], 200);
    }

    public function exercises(\App\Models\Category $category)
    {
        $exercises = $category->exercises()->orderBy('name')->paginate(10);
        return response()->json($exercises);
    }

}
