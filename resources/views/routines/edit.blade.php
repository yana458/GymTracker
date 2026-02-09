<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar rutina</h2>
            <a href="{{ route('routines.index') }}"
               class="inline-flex items-center rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </x-slot>

    @php
        $selected = $routine->exercises->keyBy('id');
        $checkedIds = old('exercises', $selected->keys()->map(fn($v) => (string)$v)->all());
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">✏️ {{ $routine->name }}</h3>
                    <p class="text-sm text-gray-500">Modifica ejercicios y valores del pivote</p>
                </div>

                <form method="POST" action="{{ route('routines.update', $routine) }}" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input name="name" value="{{ old('name', $routine->name) }}" required
                                   class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                            @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input name="description" value="{{ old('description', $routine->description) }}"
                                   class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                            @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-900">Ejercicios</p>
                            <span class="text-xs text-gray-500">Marca/desmarca para incluir</span>
                        </div>

                        @error('exercises')
                            <p class="mb-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror

                        <div class="rounded-2xl border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="text-left font-semibold px-4 py-3">Usar</th>
                                            <th class="text-left font-semibold px-4 py-3">Ejercicio</th>
                                            <th class="text-left font-semibold px-4 py-3">Categoría</th>
                                            <th class="text-left font-semibold px-4 py-3">Series</th>
                                            <th class="text-left font-semibold px-4 py-3">Reps</th>
                                            <th class="text-left font-semibold px-4 py-3">Descanso (s)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($exercises as $e)
                                            @php $sel = $selected->get($e->id); @endphp
                                            <tr class="hover:bg-gray-50/60">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="exercises[]" value="{{ $e->id }}"
                                                           class="rounded border-gray-300"
                                                           @checked(in_array((string)$e->id, $checkedIds))>
                                                </td>
                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $e->name }}</td>
                                                <td class="px-4 py-3 text-gray-500">{{ $e->category?->name }}</td>
                                                <td class="px-4 py-3">
                                                    <input type="number" min="1"
                                                           name="pivot[{{ $e->id }}][target_sets]"
                                                           value="{{ old("pivot.$e->id.target_sets", $sel?->pivot->target_sets ?? 3) }}"
                                                           class="w-24 rounded-xl border-gray-200">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" min="1"
                                                           name="pivot[{{ $e->id }}][target_reps]"
                                                           value="{{ old("pivot.$e->id.target_reps", $sel?->pivot->target_reps ?? 10) }}"
                                                           class="w-24 rounded-xl border-gray-200">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" min="0"
                                                           name="pivot[{{ $e->id }}][rest_seconds]"
                                                           value="{{ old("pivot.$e->id.rest_seconds", $sel?->pivot->rest_seconds ?? 60) }}"
                                                           class="w-32 rounded-xl border-gray-200">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <button class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 text-white py-2.5 font-semibold hover:bg-gray-800">
                        Guardar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
