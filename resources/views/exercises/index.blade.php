<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ejercicios</h2>
            <span class="text-sm text-gray-500">Crea y gestiona ejercicios</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">

                {{-- Crear --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-900">Nuevo ejercicio</h3>
                        <p class="text-sm text-gray-500">Define nombre e instrucción</p>
                    </div>

                    <form method="POST" action="{{ route('exercises.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select
                                name="category_id"
                                required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                            >
                                <option value="">-- selecciona --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                                placeholder="Press banca"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instrucción</label>
                            <textarea
                                name="instruction"
                                rows="4"
                                required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                                placeholder="Baja controlado, empuja fuerte..."
                            >{{ old('instruction') }}</textarea>
                            @error('instruction')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 text-white py-2.5 font-semibold hover:bg-gray-800">
                            Crear ejercicio
                        </button>
                    </form>
                </div>

                {{-- Listado (cards) --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Listado</h3>
                        <p class="text-sm text-gray-500">Sin scroll interno, lectura cómoda</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        @forelse($exercises as $e)
                            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5 hover:bg-gray-50/40">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $e->name }}</p>
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 mt-2">
                                            {{ $e->category?->name }}
                                        </span>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('exercises.edit', $e) }}"
                                           class="inline-flex items-center rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('exercises.destroy', $e) }}"
                                              onsubmit="return confirm('¿Borrar ejercicio?');">
                                            @csrf @method('DELETE')
                                            <button class="inline-flex items-center rounded-xl border border-rose-200 px-3 py-2 text-sm text-rose-700 hover:bg-rose-50">
                                                Borrar
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-3 text-sm text-gray-600 whitespace-normal break-words">
                                    {{ $e->instruction }}
                                </div>
                            </div>
                        @empty
                            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 text-gray-500">
                                No hay ejercicios todavía.
                            </div>
                        @endforelse
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-4">
                        {{ $exercises->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
