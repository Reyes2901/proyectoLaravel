<!-- resources/views/graficadores/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Graficador') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('graficadores.update', $graficador->IdGraficador) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Campo Titulo -->
                    <div>
                        <label for="titulo" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Título:</label>
                        <input type="text" name="Titulo" id="titulo" value="{{ old('Titulo', $graficador->Titulo) }}" required
                            class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Campo Descripcion -->
                    <div>
                        <label for="descripcion" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Descripción:</label>
                        <textarea name="Descripcion" id="descripcion" required
                            class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('Descripcion', $graficador->Descripcion) }}</textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4">
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Actualizar
                        </button>

                        <a href="{{ route('graficadores.index') }}" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver al listado
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
