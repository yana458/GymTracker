<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categorías</h2>
            @auth
                <a href="{{ route('my-routines.index') }}" class="underline text-sm text-gray-600">Mis rutinas</a>
            @endauth
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        @guest
            <div class="rounded-2xl bg-white border border-gray-100 p-5 text-gray-700">
                Puedes ver las categorías. Para crear/editar/borrar, <a class="underline" href="{{ route('login') }}">inicia sesión</a>.
            </div>
        @endguest

        @auth
            <div class="rounded-2xl bg-white border border-gray-100 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900">Crear categoría</h3>
                <form method="POST" action="{{ route('categories.store') }}" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4" enctype="multipart/form-data">
                    @csrf
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input name="name" value="{{ old('name') }}" required
                               class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0">
                        @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Icono (opcional)</label>
                        <input type="file" name="icon" accept="image/*"
                               class="mt-1 w-full rounded-xl border-gray-200 bg-white">
                        @error('icon') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1 flex items-end">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Icono</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $c)
                        <tr>
                            <td class="px-6 py-4">
                                @php
                                    $src = $c->icon_path
                                        ? asset('storage/' . $c->icon_path)   
                                        : asset('icons/default.svg');        
                                @endphp

                                <img src="{{ $src }}" alt="icono {{ $c->name }}"
                                    class="h-10 w-10 rounded-lg object-contain border border-gray-100 bg-white">
                            </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('categories.show', $c) }}" class="font-semibold text-gray-900 hover:underline">
                                    {{ $c->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                       href="{{ route('categories.show', $c) }}">
                                        Ver
                                    </a>

                                    @auth
                                        <a class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                           href="{{ route('categories.edit', $c) }}">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('categories.destroy', $c) }}"
                                              onsubmit="return confirm('¿Eliminar categoría?');">
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
