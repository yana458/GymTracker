<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ejercicios — {{ $category->name }}
            </h2>
            <a href="{{ route('categories.show', $category) }}" class="underline text-sm text-gray-600">Volver</a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nombre</th>
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
                            <td class="px-6 py-4 text-gray-600">{{ $e->instruction }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('exercises.show', $e) }}"
                                   class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-6 text-gray-600" colspan="3">No hay ejercicios en esta categoría.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $exercises->links() }}
        </div>
    </div>
</x-app-layout>
