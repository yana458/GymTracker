<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis rutinas</h2>
                <p class="text-sm text-gray-500 mt-1">Crea rutinas y ajusta series/reps/descanso por ejercicio.</p>
            </div>

            <a href="{{ route('routines.index') }}"
               class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Ver rutinas públicas
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-100 p-4 text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Crear rutina (sidebar) --}}
            <div class="lg:col-span-1 rounded-2xl bg-white border border-gray-100 p-6 shadow-sm h-fit">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-extrabold text-gray-900">Crear rutina</h3>
                        <p class="text-sm text-gray-500 mt-1">Elige ejercicios por categoría y ajusta el detalle.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('routines.store') }}" class="mt-5 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input name="name" value="{{ old('name') }}"
                               class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                               placeholder="Ej: Full Body (Lunes)"
                               required>
                        @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                        <textarea name="description" rows="2"
                                  class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                                  placeholder="Ej: rutina rápida para fuerza..."
                        >{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-1">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-900">Ejercicios</p>
                            <span class="text-xs text-gray-500">Acordeón por categoría</span>
                        </div>

                        @error('exercises')
                            <p class="mb-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror

                        @php
                            $grouped = $exercises->groupBy(fn($e) => $e->category?->name ?? 'Sin categoría');
                            $oldExercises = array_map('strval', old('exercises', []));
                        @endphp

                        <div class="space-y-3 max-h-[520px] overflow-auto pr-1">
                            @foreach($grouped as $catName => $items)
                                @php
                                    // Mantener abierto el acordeón si hay algún ejercicio de esa categoría seleccionado
                                    $shouldOpen = false;
                                    foreach ($items as $it) {
                                        if (in_array((string)$it->id, $oldExercises, true)) { $shouldOpen = true; break; }
                                    }
                                @endphp

                                <details class="rounded-2xl border border-gray-100 bg-gray-50/50 overflow-hidden" @if($shouldOpen) open @endif>
                                    <summary class="cursor-pointer select-none px-4 py-3 flex items-center justify-between">
                                        <span class="font-semibold text-gray-900">{{ $catName }}</span>
                                        <span class="text-xs text-gray-500">{{ $items->count() }} ejercicios</span>
                                    </summary>

                                    <div class="px-4 pb-4 space-y-3">
                                        @foreach($items as $e)
                                            @php
                                                $checked = in_array((string)$e->id, $oldExercises, true);
                                            @endphp

                                            <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                                <label class="flex items-start gap-3 cursor-pointer">
                                                    <input type="checkbox"
                                                           name="exercises[]"
                                                           value="{{ $e->id }}"
                                                           class="mt-1 rounded border-gray-300"
                                                           @checked($checked)>
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-gray-900">{{ $e->name }}</p>
                                                        <p class="text-xs text-gray-500 mt-1 break-words">
                                                            {{ $e->instruction }}
                                                        </p>
                                                    </div>
                                                </label>

                                                {{-- Detalles debajo (sin desplegable) --}}
                                                <div class="mt-3 grid grid-cols-3 gap-2">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600">Series</label>
                                                        <input type="number" min="1"
                                                               name="pivot[{{ $e->id }}][target_sets]"
                                                               value="{{ old("pivot.$e->id.target_sets", 3) }}"
                                                               class="mt-1 w-full rounded-xl border-gray-200">
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600">Reps</label>
                                                        <input type="number" min="1"
                                                               name="pivot[{{ $e->id }}][target_reps]"
                                                               value="{{ old("pivot.$e->id.target_reps", 10) }}"
                                                               class="mt-1 w-full rounded-xl border-gray-200">
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600">Descanso (s)</label>
                                                        <input type="number" min="0"
                                                               name="pivot[{{ $e->id }}][rest_seconds]"
                                                               value="{{ old("pivot.$e->id.rest_seconds", 60) }}"
                                                               class="mt-1 w-full rounded-xl border-gray-200">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endforeach
                        </div>

                        <p class="mt-3 text-xs text-gray-500">
                            Marca ejercicios y ajusta series/reps/descanso. (El acordeón se abre/cierra con click sin JS).
                        </p>
                    </div>

                    <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Crear rutina
                    </button>
                </form>
            </div>

            {{-- Listado mis rutinas --}}
            <div class="lg:col-span-2 space-y-4">
                @forelse($routines as $r)
                    <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-lg font-extrabold text-gray-900">{{ $r->name }}</p>

                                @if($r->description)
                                    <p class="mt-1 text-sm text-gray-600 break-words">{{ $r->description }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-400 italic">Sin descripción</p>
                                @endif

                                <p class="mt-3 text-xs text-gray-500">
                                    Ejercicios: <span class="font-semibold">{{ $r->exercises->count() }}</span>
                                    · <a class="underline" href="{{ route('routines.exercises', $r) }}">ver ejercicios</a>
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('routines.edit', $r) }}"
                                   class="rounded-xl bg-white border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('routines.destroy', $r) }}"
                                      onsubmit="return confirm('¿Eliminar rutina?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white border border-gray-100 p-6 text-gray-600">
                        Aún no tienes rutinas. Crea una usando el formulario.
                    </div>
                @endforelse

                <div class="pt-2">
                    {{ $routines->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
