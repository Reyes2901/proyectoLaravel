
<!-- resources/views/graficadores/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Graficadores') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Graficadores</h1>
                    <a href="{{ route('graficadores.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Crear Nuevo
                    </a>
                </div>

                <table class="min-w-full table-auto bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                            <th class="px-4 py-2 border">Título</th>
                            <th class="px-4 py-2 border">Fecha de Creación</th>
                            <th class="px-4 py-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($graficadores as $graficador)
                            <tr class="text-gray-800 dark:text-gray-100">
                                <td class="border px-4 py-2">{{ $graficador->Titulo }}</td>
                                <td class="border px-4 py-2">{{ $graficador->FechaCreacion }}</td>
                                <td class="border px-4 py-2 flex gap-2">
                                    <a href="{{ route('graficadores.edit', $graficador->IdGraficador) }}" 
                                       class="bg-yellow-400 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded">
                                        Editar
                                    </a>
                                    <form action="{{ route('graficadores.destroy', $graficador->IdGraficador) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
