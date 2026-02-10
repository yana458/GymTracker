<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis rutinas</h2>
            <span class="text-sm text-gray-500">Crea rutinas y asigna ejercicios</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
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

            {{-- Layout: create (5/12) + list (7/12) --}}
            <div class="grid lg:grid-cols-12 gap-6">

                {{-- CREATE ROUTINE --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 lg:col-span-5 lg:sticky lg:top-6 self-start">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-900">Nueva rutina</h3>
                        <p class="text-sm text-gray-500">Elige ejercicios por categoría (plegable)</p>
                    </div>

                    <form method="POST" action="{{ route('routines.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                                placeholder="Rutina Pecho + Tríceps"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input
                                name="description"
                                value="{{ old('description') }}"
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                                placeholder="Hipertrofia / fuerza / fullbody..."
                            >
                            @error('description')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- EXERCISES: grouped + collapsible categories --}}
                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-gray-900">Ejercicios</p>
                                <span class="text-xs text-gray-500">S=series · R=reps</span>
                            </div>

                            @error('exercises')
                                <p class="mb-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror

                            @php
                                $grouped = $exercises->groupBy(fn($e) => $e->category?->name ?? 'Sin categoría');
                                $oldExercises = array_map('strval', old('exercises', []));
                            @endphp

                            <div class="space-y-3">
                                @foreach($grouped as $catName => $items)
                                    <details class="rounded-2xl border border-gray-100 bg-gray-50/60">
                                        <summary class="cursor-pointer select-none px-4 py-3 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-900">{{ $catName }}</span>
                                                <span class="text-xs text-gray-500">({{ $items->count() }})</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Abrir</span>
                                        </summary>

                                        <div class="px-4 pb-4 space-y-3">
                                            @foreach($items as $e)
                                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                                    <label class="flex items-start gap-3 cursor-pointer">
                                                        <input
                                                            type="checkbox"
                                                            name="exercises[]"
                                                            value="{{ $e->id }}"
                                                            class="mt-1 rounded border-gray-300"
                                                            @checked(in_array((string)$e->id, $oldExercises))
                                                        >
                                                        <div>
                                                            <p class="font-semibold text-gray-900">{{ $e->name }}</p>
                                                            <p class="text-xs text-gray-500 mt-1 whitespace-normal break-words">
                                                                {{ $e->instruction }}
                                                            </p>
                                                        </div>
                                                    </label>

                                                    <div class="mt-3 grid grid-cols-3 gap-2">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600">Series</label>
                                                            <input
                                                                type="number"
                                                                min="1"
                                                                name="pivot[{{ $e->id }}][target_sets]"
                                                                value="{{ old("pivot.$e->id.target_sets", 3) }}"
                                                                class="mt-1 w-full rounded-xl border-gray-200"
                                                            >
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600">Reps</label>
                                                            <input
                                                                type="number"
                                                                min="1"
                                                                name="pivot[{{ $e->id }}][target_reps]"
                                                                value="{{ old("pivot.$e->id.target_reps", 10) }}"
                                                                class="mt-1 w-full rounded-xl border-gray-200"
                                                            >
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600">Descanso (s)</label>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="pivot[{{ $e->id }}][rest_seconds]"
                                                                value="{{ old("pivot.$e->id.rest_seconds", 60) }}"
                                                                class="mt-1 w-full rounded-xl border-gray-200"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                @endforeach
                            </div>

                            <p class="mt-3 text-xs text-gray-500">
                                Tip: abre solo la categoría que necesitas. Así no es una lista infinita.
                            </p>
                        </div>

                        <button class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 text-white py-2.5 font-semibold hover:bg-gray-800">
                            Crear rutina
                        </button>
                    </form>
                </div>

                {{-- LIST ROUTINES --}}
                <div class="lg:col-span-7 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Tus rutinas</h3>
                        <p class="text-sm text-gray-500">Editar o borrar</p>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($routines as $r)
                            <div class="p-6 hover:bg-gray-50/50">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $r->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $r->description ?: 'Sin descripción' }}</p>
                                    </div>

                                    <div class="flex gap-2">
                                        <a
                                            href="{{ route('routines.edit', $r) }}"
                                            class="inline-flex items-center rounded-xl border border-gray-200 px-3 py-2 text-gray-700 hover:bg-gray-50"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('routines.destroy', $r) }}"
                                            onsubmit="return confirm('¿Borrar la rutina?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex items-center rounded-xl border border-rose-200 px-3 py-2 text-rose-700 hover:bg-rose-50">
                                                Borrar
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-4 grid sm:grid-cols-2 gap-2">
                                    @foreach($r->exercises as $ex)
                                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-3">
                                            <div class="flex items-center justify-between gap-2">
                                                <p class="text-sm font-medium text-gray-900">{{ $ex->name }}</p>
                                                <span class="text-xs text-gray-500">{{ $ex->category?->name }}</span>
                                            </div>

                                            <p class="mt-1 text-xs text-gray-600">
                                                {{ $ex->pivot->target_sets ?? 3 }}x{{ $ex->pivot->target_reps ?? 10 }}
                                                · Descanso {{ $ex->pivot->rest_seconds ?? 60 }}s
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-gray-500">
                                Todavía no tienes rutinas. Crea una desde el formulario.
                            </div>
                        @endforelse
                    </div>

                    <div class="p-6">
                        {{ $routines->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
