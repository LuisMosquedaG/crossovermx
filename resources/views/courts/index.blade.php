<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Contenedor Flex -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        
                        <!-- Botón Crear -->
                        <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                            Crear Nueva Cancha
                        </button>

                        <!-- Formulario de Búsqueda -->
                        <form action="{{ route('courts.index') }}" method="GET" class="relative w-full md:flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Buscar canchas por nombre, ubicación o superficie...">
                        </form>
                    </div>

                    @if (session('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                     <!-- Tabla -->
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- CAMBIO: Columna Acciones movida al inicio -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Capacidad</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Superficie</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($courts as $court)
                                    <tr>
                                        <!-- CAMBIO: Columna Acciones movida al inicio -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-center space-x-3">
                                                
                                                <!-- Editar -->
                                                <button onclick="openEditModal({{ $court->toJson() }})" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </button>

                                                <!-- NUEVO BOTÓN: Gestionar Horarios -->
                                                <button onclick="openScheduleModal({{ $court->id }})" class="text-green-600 hover:text-green-900" title="Gestionar Horarios">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>

                                                
                                                <!-- Eliminar -->
                                                <form action="{{ route('courts.destroy', $court) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cancha?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <!-- Resto de columnas en su orden original -->
                                        <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                            @if($court->image_path)
                                                <img src="{{ asset('storage/' . $court->image_path) }}" alt="{{ $court->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold">
                                                    {{ substr($court->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">{{ $court->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $court->location }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $court->capacity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $court->surface_type }}</td>
                                    </tr>
                                @empty
                                    <!-- El colspan debe seguir siendo 6 porque seguimos teniendo 6 columnas -->
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay canchas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $courts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

    <!-- MODAL 1: Crear/Editar Cancha -->
    <div id="courtModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="courtForm" onsubmit="submitForm(event)" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">
                                        Crear Nueva Cancha
                                    </h3>
                                    <div class="mb-4">
                                        <x-input-label for="modal_name" :value="__('Nombre de la Cancha')" />
                                        <x-text-input id="modal_name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="name" required />
                                    </div>
                                    <div class="mb-4">
                                        <x-input-label for="modal_location" :value="__('Ubicación')" />
                                        <x-text-input id="modal_location" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="location"/>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="modal_capacity" :value="__('Capacidad')" />
                                            <x-text-input id="modal_capacity" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="number" name="capacity" min="1" />
                                        </div>
                                        <div>
                                            <x-input-label for="modal_surface_type" :value="__('Tipo de Superficie')" />
                                            <x-text-input id="modal_surface_type" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="surface_type" placeholder="Ej: Parqué, Cemento, Sintético" />
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <x-input-label for="modal_image" :value="__('Foto de la Cancha')" />
                                        <x-text-input id="modal_image" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="file" name="image" />
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

    <!-- MODAL 2: Gestionar Horarios (NUEVO) -->
    <div id="scheduleModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-3xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="scheduleForm" onsubmit="submitSchedules(event)">
                        @csrf
                        <input type="hidden" name="court_id" id="schedule_court_id">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">
                                        Configurar Disponibilidad
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-4">Define los días y horarios en los que esta cancha está abierta para reservas o partidos.</p>
                                    
                                    <!-- Contenedor de filas de horarios -->
                                    <div id="scheduleRows" class="space-y-3">
                                        <!-- Las filas se agregarán aquí con JS -->
                                    </div>

                                    <button type="button" onclick="addScheduleRow()" class="mt-3 flex items-center text-orange-600 hover:text-orange-700 font-medium text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-1">
                                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                        </svg>
                                        Agregar otro horario
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <x-primary-button type="submit" id="saveScheduleButton" class="w-full sm:ml-3 sm:w-auto bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:ring-green-500">
                                Guardar Horarios
                            </x-primary-button>
                            <button type="button" onclick="closeScheduleModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- LÓGICA MODAL CANCHAS (EXISTENTE) ---
        function openCreateModal() {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Crear Nueva Cancha';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('courtForm').action = '{{ route("courts.store") }}';
            document.getElementById('courtModal').classList.remove('hidden');
        }

        function openEditModal(court) {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Editar Cancha: ' + court.name;
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('courtForm').action = '{{ route("courts.update", ":id") }}'.replace(':id', court.id);
            document.getElementById('modal_name').value = court.name;
            document.getElementById('modal_location').value = court.location;
            document.getElementById('modal_capacity').value = court.capacity || '';
            document.getElementById('modal_surface_type').value = court.surface_type || '';
            document.getElementById('courtModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('courtModal').classList.add('hidden');
        }

        function resetForm() {
            document.getElementById('courtForm').reset();
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

                const data = await response.json();

                if (response.ok) {
                    window.location.reload(); 
                } else {
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

        // --- LÓGICA BÚSQUEDA (EXISTENTE) ---
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

        // --- NUEVA LÓGICA: GESTIÓN DE HORARIOS ---

        const daysOfWeek = [
            { value: 1, label: 'Lunes' },
            { value: 2, label: 'Martes' },
            { value: 3, label: 'Miércoles' },
            { value: 4, label: 'Jueves' },
            { value: 5, label: 'Viernes' },
            { value: 6, label: 'Sábado' },
            { value: 0, label: 'Domingo' },
        ];

        let currentScheduleRowIndex = 0;

        // Función actualizada para abrir el modal y CARGAR DATOS
        async function openScheduleModal(courtId) {
            document.getElementById('schedule_court_id').value = courtId;
            const container = document.getElementById('scheduleRows');
            container.innerHTML = ''; // Limpiamos el contenedor
            currentScheduleRowIndex = 0;

            try {
                // 1. Hacemos la petición al servidor para traer los horarios existentes
                const response = await fetch(`{{ route('courts.getSchedules', ':id') }}`.replace(':id', courtId), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const schedules = await response.json();

                // 2. Si hay horarios, recorremos el array y creamos una fila por cada uno
                if (schedules.length > 0) {
                    schedules.forEach(s => {
                        // Mapeamos los datos de la BD (day_of_week, start_time, end_time) 
                        // a como los espera la función addScheduleRow (day, start, end)
                        addScheduleRow({
                            day: s.day_of_week,
                            start: s.start_time.substring(0, 5), // Cortamos los segundos para el input type="time"
                            end: s.end_time.substring(0, 5)      // Cortamos los segundos para el input type="time"
                        });
                    });
                } else {
                    // 3. Si no hay horarios (cancha nueva), agregamos una fila vacía por defecto
                    addScheduleRow();
                }

            } catch (error) {
                console.error('Error cargando horarios:', error);
                // Si falla la carga, al menos dejamos agregar uno manual
                addScheduleRow();
            }

            // Mostramos el modal
            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        // Función para agregar una fila de horario (Día - Inicio - Fin)
        function addScheduleRow(data = null) {
            const container = document.getElementById('scheduleRows');
            const index = currentScheduleRowIndex++;
            
            const row = document.createElement('div');
            row.className = 'schedule-row flex gap-3 items-center';
            
            // Generar opciones de días
            let dayOptions = daysOfWeek.map(day => 
                `<option value="${day.value}" ${data && data.day == day.value ? 'selected' : ''}>${day.label}</option>`
            ).join('');

            row.innerHTML = `
                <div class="w-1/3">
                    <select name="schedules[${index}][day]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                        ${dayOptions}
                    </select>
                </div>
                <div class="w-1/4">
                    <input type="time" name="schedules[${index}][start_time]" value="${data ? data.start : ''}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" required>
                </div>
                <div class="w-1/4">
                    <input type="time" name="schedules[${index}][end_time]" value="${data ? data.end : ''}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" required>
                </div>
                <div class="w-8">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(row);
        }

        // Enviar formulario de horarios (CORREGIDO)
        async function submitSchedules(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const saveButton = document.getElementById('saveScheduleButton');
            const courtId = document.getElementById('schedule_court_id').value;
            
            // Convertir FormData a JSON
            const schedules = [];
            const keys = Array.from(formData.keys()).filter(k => k.startsWith('schedules['));
            
            // Extraer índices únicos
            const indices = [...new Set(keys.map(k => k.match(/\d+/)[0]))];

            indices.forEach(i => {
                schedules.push({
                    day: formData.get(`schedules[${i}][day]`),
                    start_time: formData.get(`schedules[${i}][start_time]`),
                    end_time: formData.get(`schedules[${i}][end_time]`)
                });
            });

            const payload = {
                schedules: schedules
            };

            saveButton.disabled = true;
            saveButton.innerText = 'Guardando...';

            try {
                const response = await fetch(`{{ route('courts.schedules', ':id') }}`.replace(':id', courtId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message);
                    closeScheduleModal();
                } else {
                    alert('Error: ' + (data.message || 'Ocurrió un error al guardar los horarios.'));
                }
            } catch (error) {
                console.error('Error de red:', error);
                alert('Ocurrió un error de red al guardar los horarios.');
            } finally {
                saveButton.disabled = false;
                saveButton.innerText = 'Guardar Horarios';
            }
        }
    </script>