<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ejercicios</h2>
            @auth
                <a href="{{ route('my-routines.index') }}" class="underline text-sm text-gray-600">Mis rutinas</a>
            @endauth
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

        @guest
            <div class="rounded-2xl bg-white border border-gray-100 p-5 text-gray-700">
                Puedes ver los ejercicios. Para crear/editar/borrar, <a class="underline" href="{{ route('login') }}">inicia sesión</a>.
            </div>
        @endguest

        @auth
            <div class="rounded-2xl bg-white border border-gray-100 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900">Crear ejercicio</h3>

                <form method="POST" action="{{ route('exercises.store') }}" class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input name="name" value="{{ old('name') }}" required
                               class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                        @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Indicaciones</label>
                        <input name="instruction" value="{{ old('instruction') }}" required
                               class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                        @error('instruction') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="category_id" required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-4">
                        <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            Crear
                        </button>
                    </div>
                </form>
            </div>
        @endauth

        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Indicaciones</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Acciones</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($exercises as $e)
                    <tr>
                        <td class="px-6 py-4">
                            <a href="{{ route('exercises.show', $e) }}" class="font-semibold text-gray-900 hover:underline">
                                {{ $e->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $e->category?->name ?? 'Sin categoría' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $e->instruction }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                   href="{{ route('exercises.show', $e) }}">Ver</a>

                                @auth
                                    <a class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                       href="{{ route('exercises.edit', $e) }}">Editar</a>

                                    <form method="POST" action="{{ route('exercises.destroy', $e) }}"
                                          onsubmit="return confirm('¿Eliminar ejercicio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                            Borrar
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-6 text-gray-600" colspan="4">No hay ejercicios.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $exercises->links() }}
        </div>
    </div>
</x-app-layout>
