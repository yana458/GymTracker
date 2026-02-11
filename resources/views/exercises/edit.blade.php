<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar ejercicio — {{ $exercise->name }}
            </h2>

            <a href="{{ route('exercises.index') }}"
               class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-6 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
            <form method="POST" action="{{ route('exercises.update', $exercise) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select name="category_id"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                            required>
                        <option value="" disabled>— Selecciona —</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}"
                                @selected(old('category_id', $exercise->category_id) == $c->id)>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input name="name"
                           value="{{ old('name', $exercise->name) }}"
                           class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                           required>
                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Instrucción</label>
                    <textarea name="instruction" rows="4"
                              class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                              placeholder="Descripción breve...">{{ old('instruction', $exercise->instruction) }}</textarea>
                    @error('instruction') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('exercises.index') }}"
                       class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>

                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
