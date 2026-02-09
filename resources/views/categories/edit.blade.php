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

                <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data" class="space-y-4">
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
                    <label class="block text-sm font-medium text-gray-700">Icono actual</label>
                    <div class="mt-2 flex items-center gap-3">
                        <img
                            src="{{ asset($category->icon_path) }}"
                            alt="icon"
                            class="h-10 w-10 rounded-xl border border-gray-200 bg-white p-1 object-contain"
                            onerror="this.onerror=null;this.src='{{ asset('icons/default.svg') }}';"
                        >
                        <p class="text-sm text-gray-500">{{ $category->icon_path }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cambiar icono (opcional)</label>
                    <input
                        type="file"
                        name="icon"
                        accept=".png,.jpg,.jpeg,.svg"
                        class="mt-1 block w-full text-sm text-gray-700
                            file:mr-4 file:rounded-xl file:border-0
                            file:bg-gray-900 file:px-4 file:py-2 file:text-white file:font-semibold
                            hover:file:bg-gray-800"
                    >
                    @error('icon')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        Si no subes nada, se mantiene el icono actual.
                    </p>
                </div>

                    <button class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 text-white py-2.5 font-semibold hover:bg-gray-800">
                        Guardar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
