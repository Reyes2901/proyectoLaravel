<!-- resources/views/graficadores/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#14552d] dark:text-green-400 leading-tight">
            {{ __('Crear Graficador') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-8">

                <form action="{{ route('graficadores.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <!-- Campo Titulo -->
                    <div>
                        <label for="titulo" class="block text-[#14552d] dark:text-green-300 font-semibold mb-2">Título:</label>
                        <input type="text" name="Titulo" id="titulo" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#14552d] dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Campo Descripción -->
                    <div>
                        <label for="descripcion" class="block text-[#14552d] dark:text-green-300 font-semibold mb-2">Descripción:</label>
                        <input type="text" name="Descripcion" id="descripcion" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#14552d] dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Campo Fecha de Creación -->
                    <div>
                        <label for="fecha_creacion" class="block text-[#14552d] dark:text-green-300 font-semibold mb-2">Fecha de Creación:</label>
                        <input type="date" name="FechaCreacion" id="fecha_creacion" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#14552d] dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Campo Hora de Creación -->
                    <div>
                        <label for="hora_creacion" class="block text-[#14552d] dark:text-green-300 font-semibold mb-2">Hora de Creación:</label>
                        <input type="time" name="HoraCreacion" id="hora_creacion" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#14552d] dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Campo Estado -->
                    <div>
                        <label for="estado" class="block text-[#14552d] dark:text-green-300 font-semibold mb-2">Estado:</label>
                        <input type="text" name="Estado" id="estado" value="1"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#14552d] dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Campo Contenido -->
                    <div>
                        <label for="contenido" class="block text-[#14552d] dark:text-green-300 font-semibold mb-2">Contenido:</label>
                        <input type="text" name="Contenido" id="contenido" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#14552d] dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Botones -->
                    <div class="col-span-1 md:col-span-2 flex justify-center gap-4 mt-6">
                        <button type="submit"
                            class="bg-[#14552d] hover:bg-green-800 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            Crear
                        </button>

                        <a href="{{ route('graficadores.index') }}"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            Volver al listado
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
