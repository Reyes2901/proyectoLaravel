<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#14552d] dark:text-green-400 leading-tight">
            {{ __('Lista de Diagramador') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-8">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">
                    Diagramador
                </h3>

                <!-- Tabla de Graficadores -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <thead class="bg-[#14552d] text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Título</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Descripción</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Fecha</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Hora</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Estado</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Contenido</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($graficadores as $graficador)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">{{ $graficador->IdGraficador }}</td>
                                    <td class="px-6 py-4">{{ $graficador->Titulo }}</td>
                                    <td class="px-6 py-4">{{ $graficador->Descripcion }}</td>
                                    <td class="px-6 py-4">{{ $graficador->FechaCreacion }}</td>
                                    <td class="px-6 py-4">{{ $graficador->HoraCreacion }}</td>
                                    <td class="px-6 py-4">
                                        @if($graficador->Estado == 1)
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Activo</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $graficador->Contenido }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($graficador->IdUser !== auth()->user()->id)
                                            <button
                                                class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2 px-4 rounded-lg shadow transition"
                                                onclick="openModal({{ $graficador->IdGraficador }})"
                                            >
                                                Invitación
                                            </button>
                                        @else
                                            <span class="text-gray-500 text-sm italic">Eres el creador</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div id="collaboratorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md mx-4">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 text-center">
                            ¿Desea ser colaborador de este proyecto?
                        </h3>
                        <input type="hidden" id="graficadorId">
                        <div class="flex justify-center gap-4">
                            <button
                                class="bg-[#14552d] hover:bg-green-800 text-white font-bold py-2 px-6 rounded-lg shadow-md transition"
                                onclick="acceptCollaborator()"
                            >
                                Sí, quiero colaborar
                            </button>
                            <button
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition"
                                onclick="closeModal()"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Abrir modal
        function openModal(id) {
            document.getElementById('graficadorId').value = id;
            document.getElementById('collaboratorModal').classList.remove('hidden');
        }

        // Cerrar modal
        function closeModal() {
            document.getElementById('collaboratorModal').classList.add('hidden');
        }

        // Aceptar colaborador
        function acceptCollaborator() {
            const graficadorId = document.getElementById('graficadorId').value;

            fetch('/graficador/colaborador', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ graficadorId: graficadorId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ¡Ahora eres colaborador!');
                    location.reload();
                } else {
                    alert('❌ Error al intentar ser colaborador.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('⚠️ Error de conexión.');
            });

            closeModal();
        }
    </script>
</x-app-layout>
