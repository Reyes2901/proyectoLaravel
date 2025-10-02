<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#14552d] dark:text-green-400 leading-tight">
            {{ __('Lista de Graficadores') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-8">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Graficadores Disponibles
                    </h3>
                    <a href="{{ route('graficadores.create') }}"
                       class="bg-[#14552d] hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:scale-105">
                        ➕ Crear Nuevo
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <thead class="bg-[#14552d] text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Título</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Fecha de Creación</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($graficadores as $graficador)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">{{ $graficador->IdGraficador }}</td>
                                    <td class="px-6 py-4">{{ $graficador->Titulo }}</td>
                                    <td class="px-6 py-4">{{ $graficador->FechaCreacion }}</td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <a href="{{ route('graficadores.edit', $graficador->IdGraficador) }}"
                                           class="bg-[#14552d] hover:bg-green-800 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                                            Editar
                                        </a>
                                        <form action="{{ route('graficadores.destroy', $graficador->IdGraficador) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este graficador?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                                                Eliminar
                                            </button>
                                        </form>
                                        <a href="{{ route('graficador.usuarios', $graficador->IdGraficador) }}"
                                           class="bg-yellow-500 hover:bg-yellow-600 text-black text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                                            Ver Usuarios
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
