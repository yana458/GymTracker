<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ejercicio</h2>
            <a href="{{ route('exercises.index') }}" class="underline text-sm text-gray-600">Volver</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
            <h1 class="text-2xl font-extrabold text-gray-900">{{ $exercise->name }}</h1>
            <p class="mt-2 text-gray-600">{{ $exercise->instruction }}</p>

            <div class="mt-4 text-sm text-gray-600">
                Categoría:
                <a href="{{ route('categories.show', $exercise->category) }}" class="underline font-semibold">
                    {{ $exercise->category?->name ?? 'Sin categoría' }}
                </a>
            </div>

            @auth
                <div class="mt-5 flex items-center gap-2">
                    <a href="{{ route('exercises.edit', $exercise) }}"
                       class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Editar
                    </a>

                    <form method="POST" action="{{ route('exercises.destroy', $exercise) }}"
                          onsubmit="return confirm('¿Eliminar ejercicio?');">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Borrar
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</x-app-layout>
