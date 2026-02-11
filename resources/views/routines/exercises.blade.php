<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ejercicios — {{ $routine->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Click en un ejercicio para desplegar detalles.</p>
            </div>

            <a href="{{ url()->previous() }}"
               class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-6 py-8 space-y-4">
        @php
            $grouped = $routine->exercises->groupBy(fn($e) => $e->category?->name ?? 'Sin categoría');
        @endphp

        @foreach($grouped as $catName => $items)
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <p class="font-bold text-gray-900">{{ $catName }}</p>
                    <p class="text-xs text-gray-500">{{ $items->count() }} ejercicios</p>
                </div>

                <div class="p-5 space-y-3">
                    @foreach($items as $e)
                        <details class="rounded-2xl border border-gray-100 bg-gray-50/50 overflow-hidden">
                            <summary class="cursor-pointer select-none px-4 py-3 flex items-center justify-between">
                                <span class="font-semibold text-gray-900">{{ $e->name }}</span>
                                <span class="text-xs text-gray-500">
                                    S: {{ $e->pivot->target_sets ?? '-' }} · R: {{ $e->pivot->target_reps ?? '-' }} · D: {{ $e->pivot->rest_seconds ?? '-' }}s
                                </span>
                            </summary>

                            <div class="px-4 pb-4">
                                <p class="text-sm text-gray-600">{{ $e->instruction }}</p>

                                <div class="mt-3 grid grid-cols-3 gap-2">
                                    <div class="rounded-xl bg-white border border-gray-100 p-3">
                                        <p class="text-xs text-gray-500">Series</p>
                                        <p class="font-bold text-gray-900">{{ $e->pivot->target_sets ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-100 p-3">
                                        <p class="text-xs text-gray-500">Reps</p>
                                        <p class="font-bold text-gray-900">{{ $e->pivot->target_reps ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-100 p-3">
                                        <p class="text-xs text-gray-500">Descanso</p>
                                        <p class="font-bold text-gray-900">{{ $e->pivot->rest_seconds ?? '-' }}s</p>
                                    </div>
                                </div>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
