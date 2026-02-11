<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bienvenida</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="rounded-3xl bg-white border border-gray-100 p-8 shadow-sm">
            <h1 class="text-3xl font-extrabold text-gray-900">GymTracker</h1>
            <p class="mt-3 text-gray-600">
                Puedes ver categorías, ejercicios y rutinas públicas. Para crear y gestionar tus rutinas, inicia sesión.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('categories.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Ver categorías
                </a>
                <a href="{{ route('exercises.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Ver ejercicios
                </a>
                <a href="{{ route('routines.index') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Ver rutinas
                </a>

                <a href="{{ route('login') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Login
                </a>
                <a href="{{ route('register') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Registro
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
