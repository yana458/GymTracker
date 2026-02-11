<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Bienvenid@, {{ auth()->user()->name }}
                </h2>
                <p class="text-sm text-gray-500">Tu panel rápido de Gym Tracker</p>
            </div>

            <div class="flex flex-wrap gap-2">
                {{-- Mis rutinas (si existe la ruta nueva) --}}
                @if(\Illuminate\Support\Facades\Route::has('my-routines.index'))
                    <a href="{{ route('my-routines.index') }}"
                       class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Mis rutinas
                    </a>
                @endif

                {{-- Rutinas públicas --}}
                <a href="{{ route('routines.index') }}"
                   class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Rutinas públicas
                </a>

                {{-- Ejercicios / Categorías (si tu web los tiene con auth, igual funciona porque ya estás logueada) --}}
                <a href="{{ route('exercises.index') }}"
                   class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Ejercicios
                </a>

                <a href="{{ route('categories.index') }}"
                   class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Categorías
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 space-y-6">

            {{-- Alerts --}}
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

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Mis rutinas</p>
                    <p class="text-3xl font-extrabold mt-2 text-gray-900">{{ $stats['my_routines'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Rutinas asociadas a tu usuario</p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Ejercicios</p>
                    <p class="text-3xl font-extrabold mt-2 text-gray-900">{{ $stats['total_exercises'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Disponibles para tus rutinas</p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Categorías</p>
                    <p class="text-3xl font-extrabold mt-2 text-gray-900">{{ $stats['total_categories'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Grupos musculares</p>
                </div>
            </div>

            {{-- Panel principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Accesos rápidos --}}
                <div class="lg:col-span-1 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900">Accesos rápidos</h3>
                    <p class="text-sm text-gray-500 mt-1">Lo más usado del proyecto</p>

                    <div class="mt-5 space-y-3">
                        @if(\Illuminate\Support\Facades\Route::has('my-routines.index'))
                            <a href="{{ route('my-routines.index') }}"
                               class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                                <p class="font-semibold text-gray-900">Crear / gestionar mis rutinas</p>
                                <p class="text-sm text-gray-500">Añade ejercicios y define series/reps/descanso</p>
                            </a>
                        @endif

                        <a href="{{ route('routines.index') }}"
                           class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                            <p class="font-semibold text-gray-900">Explorar rutinas públicas</p>
                            <p class="text-sm text-gray-500">Ver rutinas y sus ejercicios</p>
                        </a>

                        <a href="{{ route('exercises.index') }}"
                           class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                            <p class="font-semibold text-gray-900">Gestionar ejercicios</p>
                            <p class="text-sm text-gray-500">Crear, editar y filtrar ejercicios</p>
                        </a>

                        <a href="{{ route('categories.index') }}"
                           class="block rounded-2xl border border-gray-100 p-4 hover:bg-gray-50">
                            <p class="font-semibold text-gray-900">Gestionar categorías</p>
                            <p class="text-sm text-gray-500">Subir iconos y organizar grupos</p>
                        </a>
                    </div>

                    <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-sm font-semibold text-slate-900">Tip rápido</p>
                        <p class="text-sm text-slate-600 mt-1">
                            Para crear rutinas, entra en <span class="font-semibold">Mis rutinas</span>.
                            Las <span class="font-semibold">Rutinas públicas</span> son para explorar.
                        </p>
                    </div>
                </div>

                {{-- Últimas rutinas --}}
                <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Tus últimas rutinas</h3>
                                <p class="text-sm text-gray-500">Acceso rápido a lo más reciente</p>
                            </div>

                            @if(\Illuminate\Support\Facades\Route::has('my-routines.index'))
                                <a href="{{ route('my-routines.index') }}"
                                   class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                    Crear rutina
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($recentRoutines as $r)
                            @php
                                $showUrl = \Illuminate\Support\Facades\Route::has('routines.show')
                                    ? route('routines.show', $r)
                                    : url('/routines/'.$r->id);

                                $editUrl = \Illuminate\Support\Facades\Route::has('routines.edit')
                                    ? route('routines.edit', $r)
                                    : null;
                            @endphp

                            <div class="p-6 hover:bg-gray-50/50 flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $r->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $r->exercises_count }} ejercicios · creada {{ $r->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ $showUrl }}"
                                       class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        Ver
                                    </a>

                                    @if($editUrl)
                                        <a href="{{ $editUrl }}"
                                           class="rounded-xl bg-white border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                            Editar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-gray-600">
                                Aún no tienes rutinas recientes. Crea la primera desde <span class="font-semibold">Mis rutinas</span>.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
