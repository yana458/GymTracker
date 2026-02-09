<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gym Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-white text-slate-800">
    <header class="border-b bg-white/80 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold">
                    GT
                </div>
                <div>
                    <p class="font-semibold leading-5">Gym Tracker</p>
                    <p class="text-xs text-slate-500">Rutinas, ejercicios y progreso</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                    Entrar
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                        Registrarse
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10">
        {{-- Hero --}}
        <section class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Simple · Rápido · Bonito
                </div>

                <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900">
                    Organiza tus rutinas sin complicarte.
                </h1>

                <p class="mt-4 text-lg text-slate-600">
                    Crea categorías, añade ejercicios y monta rutinas con series, repeticiones y descansos.
                    Todo en un panel claro y fácil.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex justify-center items-center px-5 py-3 rounded-2xl bg-slate-900 text-white font-semibold hover:bg-slate-800">
                        Empezar ahora
                    </a>

                    <a href="#features"
                       class="inline-flex justify-center items-center px-5 py-3 rounded-2xl border border-slate-200 font-semibold hover:bg-slate-50">
                        Ver cómo funciona
                    </a>
                </div>

                <div class="mt-6 flex items-center gap-3 text-sm text-slate-500">
                    <div class="flex -space-x-2">
                        <div class="h-8 w-8 rounded-full bg-slate-200"></div>
                        <div class="h-8 w-8 rounded-full bg-slate-300"></div>
                        <div class="h-8 w-8 rounded-full bg-slate-400"></div>
                    </div>
                    <span>Panel pensado para prácticas y demo rápida</span>
                </div>
            </div>

            {{-- “Mock” panel --}}
            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-r from-emerald-100 via-sky-100 to-violet-100 blur-2xl opacity-60 rounded-[2rem]"></div>
                <div class="relative bg-white border border-slate-100 shadow-sm rounded-[2rem] p-6">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold">Vista previa</p>
                        <span class="text-xs text-slate-500">Gym Tracker</span>
                    </div>

                    <div class="mt-5 grid grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Categorías</p>
                            <p class="text-2xl font-extrabold mt-1">3</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Ejercicios</p>
                            <p class="text-2xl font-extrabold mt-1">12</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Rutinas</p>
                            <p class="text-2xl font-extrabold mt-1">5</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-sm font-semibold">Rutina Pecho</p>
                            <p class="text-xs text-slate-500 mt-1">Press banca · 4x10 · 90s</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-sm font-semibold">Rutina Espalda</p>
                            <p class="text-xs text-slate-500 mt-1">Remo · 4x12 · 60s</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="text-sm font-semibold">Rutina Pierna</p>
                            <p class="text-xs text-slate-500 mt-1">Sentadilla · 5x8 · 120s</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="mt-12">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <p class="font-semibold">Categorías</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Pecho, espalda, pierna… organiza los ejercicios por grupo muscular.
                    </p>
                </div>

                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <p class="font-semibold">Ejercicios</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Guarda nombre e instrucción para tenerlo todo claro.
                    </p>
                </div>

                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <p class="font-semibold">Rutinas</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Elige ejercicios y define series/reps/descanso con pivote.
                    </p>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-12">
            <div class="bg-slate-900 text-white rounded-[2rem] p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <p class="text-xl font-extrabold">¿Lista para entrar?</p>
                    <p class="mt-1 text-white/80">Inicia sesión y tendrás tu panel con accesos rápidos.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('login') }}"
                       class="px-5 py-3 rounded-2xl bg-white text-slate-900 font-semibold hover:bg-white/90">
                        Entrar
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-5 py-3 rounded-2xl border border-white/30 font-semibold hover:bg-white/10">
                            Registrarse
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <footer class="py-10 text-center text-sm text-slate-500">
        Gym Tracker · Laravel + Breeze + Tailwind
    </footer>
</body>
</html>
