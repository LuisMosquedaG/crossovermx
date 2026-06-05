<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Contenedor Flex -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        
                        <!-- Botón Crear -->
                        <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                            Crear Nueva Fuerza
                        </button>

                        <!-- Formulario de Búsqueda -->
                        <form action="{{ route('strengths.index') }}" method="GET" class="relative w-full md:flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Buscar fuerzas por nombre...">
                        </form>
                    </div>

                    @if (session('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline font-bold">Error:</span>
                            <ul class="list-disc list-inside mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                     <!-- Tabla -->
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- CAMBIO: Columna Acciones movida al inicio -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de la Fuerza</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Equipos Asignados</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($strengths as $strength)
                                    <tr>
                                        <!-- CAMBIO: Columna Acciones movida al inicio -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-center space-x-3">
                                                
                                                <!-- Editar -->
                                                <button onclick="openEditModal({{ $strength->toJson() }})" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </button>
                                                
                                                <!-- Eliminar -->
                                                <form action="{{ route('strengths.destroy', $strength) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta fuerza? Esta acción no se puede deshacer.');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <!-- Resto de las columnas -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">{{ $strength->name }}</td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $strength->teams_count ?? $strength->teams()->count() }} Equipos
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- colspan se mantiene en 3 porque seguimos teniendo 3 columnas -->
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay fuerzas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $strengths->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

    <!-- MODAL: Crear/Editar Fuerza -->
    <div id="strengthModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="strengthForm" onsubmit="submitForm(event)">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">
                                        Crear Nueva Fuerza
                                    </h3>
                                    <div class="mt-2">
                                        <x-input-label for="modal_name" :value="__('Nombre de la Fuerza')" />
                                        <x-text-input id="modal_name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="name" required placeholder="Ej. 1ra Fuerza, 2da Fuerza, Infantil" />
                                        <p class="mt-2 text-sm text-gray-500">Este nombre aparecerá al registrar equipos en los torneos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <x-primary-button type="submit" id="saveButton" class="w-full sm:ml-3 sm:w-auto">
                                Guardar
                            </x-primary-button>
                            <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- LÓGICA MODAL FUERZAS ---
        function openCreateModal() {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Crear Nueva Fuerza';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('strengthForm').action = '{{ route("strengths.store") }}';
            document.getElementById('strengthModal').classList.remove('hidden');
        }

        function openEditModal(strength) {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Editar Fuerza';
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('strengthForm').action = '{{ route("strengths.update", ":id") }}'.replace(':id', strength.id);
            document.getElementById('modal_name').value = strength.name;
            document.getElementById('strengthModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('strengthModal').classList.add('hidden');
        }

        function resetForm() {
            document.getElementById('strengthForm').reset();
        }

        async function submitForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const saveButton = document.getElementById('saveButton');
            const originalButtonText = saveButton.innerText;

            saveButton.disabled = true;
            saveButton.innerText = 'Guardando...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    window.location.reload(); 
                } else {
                    const data = await response.json();
                    alert('Error: ' + (data.message || 'Ocurrió un error inesperado.'));
                }
            } catch (error) {
                console.error('Error de red:', error);
                alert('Ocurrió un error de red.');
            } finally {
                saveButton.disabled = false;
                saveButton.innerText = originalButtonText;
            }
        }

        // --- LÓGICA BÚSQUEDA ---
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let timeout = null;
            searchInput.addEventListener('keyup', function (e) {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    e.target.form.submit();
                }, 500);
            });
        }
    </script>