<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Bienvenid@, {{ auth()->user()->name }}
                </h2>
                <p class="text-l text-gray-500">Tu panel rápido de Gym Tracker</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('routines.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800">
                    Crear rutina
                </a>
                <a href="{{ route('exercises.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 font-semibold hover:bg-gray-50">
                    Añadir ejercicio
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Mis rutinas</p>
                    <p class="text-3xl font-extrabold mt-2 text-gray-900">{{ $stats['my_routines'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Rutinas asociadas a tu usuario</p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Ejercicios totales</p>
                    <p class="text-3xl font-extrabold mt-2 text-gray-900">{{ $stats['total_exercises'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Disponibles para tus rutinas</p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Categorías</p>
                    <p class="text-3xl font-extrabold mt-2 text-gray-900">{{ $stats['total_categories'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Grupos musculares</p>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Accesos rápidos</h3>
                    <p class="text-sm text-gray-500 mb-4">Lo más usado</p>

                    <div class="space-y-3">
                        <a href="{{ route('categories.index') }}"
                           class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                            <p class="font-semibold text-gray-900">Categorías</p>
                            <p class="text-sm text-gray-500">Crear/editar grupos musculares</p>
                        </a>

                        <a href="{{ route('exercises.index') }}"
                           class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                            <p class="font-semibold text-gray-900">Ejercicios</p>
                            <p class="text-sm text-gray-500">Añadir nuevos ejercicios</p>
                        </a>

                        <a href="{{ route('routines.index') }}"
                           class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                            <p class="font-semibold text-gray-900">Rutinas</p>
                            <p class="text-sm text-gray-500">Montar rutinas con pivote</p>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Últimas rutinas</h3>
                        <p class="text-sm text-gray-500">Tus rutinas más recientes</p>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($recentRoutines as $r)
                            <div class="p-6 hover:bg-gray-50/50 flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $r->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $r->exercises_count }} ejercicios · creada {{ $r->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ route('routines.edit', $r) }}"
                                   class="px-4 py-2 rounded-xl border border-gray-200 font-semibold hover:bg-gray-50">
                                    Editar
                                </a>
                            </div>
                        @empty
                            <div class="p-8 text-gray-500">
                                Aún no tienes rutinas. Ve a “Rutinas” y crea la primera.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
