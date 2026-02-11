<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rutinas (público)</h2>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('my-routines.index') }}"
                       class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Mis rutinas
                    </a>
                @endauth
                @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Iniciar sesión
                    </a>
                @endguest
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-2xl bg-rose-50 border border-rose-100 p-4 text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                @forelse($routines as $r)
                    <div class="rounded-2xl bg-white border border-gray-100 p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <a href="{{ route('routines.show', $r) }}" class="text-lg font-bold text-gray-900 hover:underline">
                                    {{ $r->name }}
                                </a>
                                <p class="mt-1 text-sm text-gray-600">{{ $r->description }}</p>
                                <p class="mt-2 text-xs text-gray-500">
                                    Ejercicios: <span class="font-semibold">{{ $r->exercises->count() }}</span>
                                    · <a class="underline" href="{{ route('routines.exercises', $r) }}">ver ejercicios</a>
                                </p>
                            </div>

                            @auth
                                @php $subscribed = in_array($r->id, $myRoutineIds ?? []); @endphp

                                @if(!$subscribed)
                                    <form method="POST" action="{{ route('my-routines.subscribe') }}">
                                        @csrf
                                        <input type="hidden" name="routine_id" value="{{ $r->id }}">
                                        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                            Añadir a mis rutinas
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('my-routines.unsubscribe', $r) }}">
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
                @empty
                    <div class="rounded-2xl bg-white border border-gray-100 p-6 text-gray-600">
                        No hay rutinas todavía.
                    </div>
                @endforelse

                <div class="pt-2">
                    {{ $routines->links() }}
                </div>
            </div>

            <aside class="rounded-2xl bg-white border border-gray-100 p-5 shadow-sm h-fit">
                <h3 class="font-bold text-gray-900">Acceso</h3>
                @guest
                    <p class="mt-2 text-sm text-gray-600">
                        Puedes ver las rutinas públicas. Para crear/editar y gestionar “Mis rutinas”, inicia sesión.
                    </p>
                    <a href="{{ route('login') }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Iniciar sesión
                    </a>
                @endguest

                @auth
                    <p class="mt-2 text-sm text-gray-600">
                        Ya estás dentro. Gestiona tus rutinas desde “Mis rutinas”.
                    </p>
                    <a href="{{ route('my-routines.index') }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Ir a Mis rutinas
                    </a>
                @endauth
            </aside>
        </div>
    </div>
</x-app-layout>
