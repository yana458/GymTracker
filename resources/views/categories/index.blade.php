<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categorías</h2>
            <span class="text-sm text-gray-500">Gestiona grupos musculares</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
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
                {{-- Create card --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-900">Nueva categoría</h3>
                        <p class="text-sm text-gray-500">Ej: Pecho, Espalda, Pierna…</p>
                    </div>

                    <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-gray-300 focus:ring-0"
                                placeholder="Pecho"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icono (PNG/JPG/SVG)</label>
                            <input
                                type="file"
                                name="icon"
                                accept=".png,.jpg,.jpeg,.svg"
                                required
                                class="mt-1 block w-full text-sm text-gray-700
                                    file:mr-4 file:rounded-xl file:border-0
                                    file:bg-gray-900 file:px-4 file:py-2 file:text-white file:font-semibold
                                    hover:file:bg-gray-800"
                            >
                            @error('icon')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 text-white py-2.5 font-semibold hover:bg-gray-800">
                            Crear categoría
                        </button>
                    </form>
                </div>

                {{-- List card --}}
                <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Listado</h3>
                        <p class="text-sm text-gray-500">Crea y gestiona categorías</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-left font-semibold px-6 py-3">Icono</th>
                                    <th class="text-left font-semibold px-6 py-3">Nombre</th>
                                    <th class="text-right font-semibold px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($categories as $c)
                                    <tr class="hover:bg-gray-50/60">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img
                                                    src="{{ asset($c->icon_path) }}"
                                                    alt="icon"
                                                    class="h-9 w-9 rounded-xl border border-gray-200 bg-white p-1 object-contain"
                                                    onerror="this.onerror=null;this.src='{{ asset('icons/default.svg') }}';"
                                                >
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $c->name }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a
                                                    href="{{ route('categories.edit', $c) }}"
                                                    class="inline-flex items-center rounded-xl border border-gray-200 px-3 py-2 text-gray-700 hover:bg-gray-50"
                                                >
                                                    Editar
                                                </a>

                                                <form method="POST" action="{{ route('categories.destroy', $c) }}"
                                                      onsubmit="return confirm('¿Borrar categoría?');">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="inline-flex items-center rounded-xl border border-rose-200 px-3 py-2 text-rose-700 hover:bg-rose-50"
                                                    >
                                                        Borrar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-6 py-8 text-gray-500" colspan="3">
                                            No hay categorías todavía.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
