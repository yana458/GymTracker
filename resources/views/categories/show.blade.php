<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categoría</h2>
            <a href="{{ route('categories.index') }}" class="underline text-sm text-gray-600">Volver</a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-6 py-8 space-y-6">
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                @if($category->icon_path)
                    <div class="mt-3 flex items-center gap-3">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->icon_path) }}"
                            class="h-10 w-10 rounded-xl"
                            alt="icon"
                        >
                    </div>
                @else
                    <div class="h-12 w-12 rounded-xl bg-gray-100"></div>
                @endif

                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">{{ $category->name }}</h1>
                    <p class="text-sm text-gray-600">Ejercicios asociados a esta categoría.</p>
                </div>
            </div>

            @auth
                <div class="mt-5 flex items-center gap-2">
                    <a href="{{ route('categories.edit', $category) }}"
                       class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Editar
                    </a>

                    <form method="POST" action="{{ route('categories.destroy', $category) }}"
                          onsubmit="return confirm('¿Eliminar categoría?');">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Borrar
                        </button>
                    </form>
                </div>
            @endauth

            @guest
                <p class="mt-5 text-sm text-gray-600">
                    Para editar/borrar, <a class="underline" href="{{ route('login') }}">inicia sesión</a>.
                </p>
            @endguest
        </div>

        {{-- ejercicios de la categoría (si ya cargas relación o pasas $exercises) --}}
        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Ejercicios</h3>
            </div>

            <table class="min-w-full">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Indicaciones</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @php
                    $items = isset($exercises) ? $exercises : ($category->exercises ?? collect());
                @endphp

                @forelse($items as $e)
                    <tr>
                        <td class="px-6 py-4">
                            <a href="{{ route('exercises.show', $e) }}"
                               class="font-semibold text-gray-900 hover:underline">
                                {{ $e->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $e->instruction }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-6 text-gray-600" colspan="2">No hay ejercicios en esta categoría.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if(isset($exercises))
                <div class="p-4">
                    {{ $exercises->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
