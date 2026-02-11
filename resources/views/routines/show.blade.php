<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle rutina</h2>
            <a href="{{ route('routines.index') }}" class="underline text-sm text-gray-600">Volver</a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-6 py-8">
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
            <h1 class="text-2xl font-extrabold text-gray-900">{{ $routine->name }}</h1>
            <p class="mt-2 text-gray-600">{{ $routine->description }}</p>

            <div class="mt-4 flex items-center gap-3">
                <a href="{{ route('routines.exercises', $routine) }}"
                   class="rounded-xl bg-white border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Ver ejercicios
                </a>

                @auth
                    @if(!$isSubscribed)
                        <form method="POST" action="{{ route('my-routines.subscribe') }}">
                            @csrf
                            <input type="hidden" name="routine_id" value="{{ $routine->id }}">
                            <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                Añadir a mis rutinas
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('my-routines.unsubscribe', $routine) }}">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-xl bg-white border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Quitar de mis rutinas
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
