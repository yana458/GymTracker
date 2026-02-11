<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar categoría</h2>
            <a href="{{ route('categories.index') }}" class="underline text-sm text-gray-600">Volver</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-6 py-8">
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
            <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input name="name" value="{{ old('name', $category->name) }}" required
                           class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Icono (opcional)</label>
                    <input type="file" name="icon" accept="image/*"
                           class="mt-1 w-full rounded-xl border-gray-200 bg-white">
                    @error('icon') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror

                    @if($category->icon_path)
                        <div class="mt-3 flex items-center gap-3">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->icon_path) }}"
                                class="h-10 w-10 rounded-xl"
                                alt="icon"
                            >
                            <p class="text-xs text-gray-500">{{ $category->icon_path }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Guardar
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
