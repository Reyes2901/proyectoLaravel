<x-app-layout>
    {{-- Header personalizado a todo el ancho --}}
    <div class="bg-[#14552d] px-6 py-4 flex justify-between items-center text-white shadow w-full">
        <h2 class="text-xl font-bold"></h2>
        <div>
            <!-- Aquí podrías poner el nombre del usuario o un menú de perfil -->
            <span class="font-semibold">Hola, {{ Auth::user()->name ?? 'Invitado' }}</span>
        </div>
    </div>

    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        {{-- Sidebar --}}
        <aside class="w-64 bg-[#14552d] text-white flex flex-col shadow-lg">
            <div class="p-6 text-2xl font-bold border-b border-green-900">
                Panel
            </div>
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('graficadores.index') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded bg-green-700 text-white font-semibold shadow hover:bg-green-600 transition">
                           Mis Diagramadores
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/graficador/portal') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded bg-green-700 text-white font-semibold shadow hover:bg-green-600 transition">
                            Diagramador UML
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4">Bienvenido al Dashboard</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    Selecciona una opción del panel lateral para comenzar.
                </p>
            </div>
        </main>
    </div>
</x-app-layout>
