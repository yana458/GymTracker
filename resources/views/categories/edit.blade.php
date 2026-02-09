<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar categoría</h2>
            <a href="{{ route('categories.index') }}"
               class="inline-flex items-center rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">📌 {{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">Actualiza los datos y guarda</p>
                </div>

                <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            required
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Icon path</label>
                        <input
                            name="icon_path"
                            value="{{ old('icon_path', $category->icon_path) }}"
                            required
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                        >
                        @error('icon_path')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 text-white py-2.5 font-semibold hover:bg-gray-800">
                        Guardar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
