<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Header y Controles -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        
                        <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                            Crear Nuevo Cliente
                        </button>

                        <!-- Buscador -->
                        <form action="{{ route('clients.index') }}" method="GET" class="relative w-full md:flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Buscar clientes por nombre, contacto o email...">
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
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prefijo</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Organización</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($clients as $client)
                                        <!-- OBTENER USUARIO ADMIN DE ESTE CLIENTE (MÉTODO SEGURO) -->
                                        @php
                                            // 1. Cargamos los usuarios de este cliente en una colección
                                            $users = $client->users;

                                            // 2. Buscamos el primero que tenga el rol 'Admin' usando el método hasRole de Spatie
                                            $adminUser = $users->first(function ($user) {
                                                return $user->hasRole('Admin');
                                            });

                                            // 3. Fallback: Si no encuentra 'Admin' (quizás se llama diferente), 
                                            // buscamos al primero que NO sea Master Admin.
                                            if (!$adminUser && $users->count() > 0) {
                                                $adminUser = $users->first(function ($user) {
                                                    return !$user->hasRole('Master Admin');
                                                });
                                            }
                                        @endphp

                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                                @if($client->logo_path)
                                                    <img src="{{ asset('storage/' . $client->logo_path) }}" alt="{{ $client->name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold">
                                                        {{ substr($client->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                {{ $client->prefix ?? '-' }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">{{ $client->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $client->contact_name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $client->email ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $client->phone ?? '-' }}</td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                <div class="flex items-center justify-center space-x-3">
                                                    
                                                        <!-- BOTÓN DOCUMENTO (VER APROBACIÓN) -->
                                                        @if($adminUser)
                                                            <button onclick="openTermsModal('{{ $client->name }}', '{{ $client->contact_name ?? '-' }}', {{ $adminUser->toJson() }})" 
                                                                class="text-green-600 hover:text-green-900 transition-colors" 
                                                                title="Ver Aceptación de Términos y Condiciones">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                                </svg>
                                                            </button>
                                                        @else
                                                        <!-- Documento deshabilitado (gris) -->
                                                        <span class="text-gray-300 cursor-not-allowed" title="No hay usuario admin asignado">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                            </svg>
                                                        </span>
                                                    @endif

                                                    <!-- BOTÓN EDITAR (Mantenido igual) -->
                                                    <button onclick="openEditModal({{ $client->toJson() }})" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </button>

                                                    <!-- BOTÓN ELIMINAR (Mantenido igual) -->
                                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente?');" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay clientes registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $clients->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Cliente -->
    <div id="clientModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="clientForm" onsubmit="submitForm(event)" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">
                                        Crear Nuevo Cliente
                                    </h3>
                                    
                                    <div class="mb-4">
                                        <x-input-label for="modal_name" :value="__('Nombre de la Organización')" />
                                        <x-text-input id="modal_name" class="block mt-1 w-full" type="text" name="name" required />
                                    </div>

                                    <!-- NUEVO CAMPO PREFIJO EN EL MODAL -->
                                    <div class="mb-4">
                                        <x-input-label for="modal_prefix" :value="__('Prefijo')" />
                                        <x-text-input id="modal_prefix" class="block mt-1 w-full" type="text" name="prefix" placeholder="Ej. CDMX, GTO, 2024" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="modal_contact" :value="__('Nombre de Contacto')" />
                                            <x-text-input id="modal_contact" class="block mt-1 w-full" type="text" name="contact_name" />
                                        </div>
                                        <div>
                                            <x-input-label for="modal_phone" :value="__('Teléfono')" />
                                            <x-text-input id="modal_phone" class="block mt-1 w-full" type="text" name="phone" />
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="modal_email" :value="__('Correo Electrónico')" />
                                        <x-text-input id="modal_email" class="block mt-1 w-full" type="email" name="email" />
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="modal_logo" :value="__('Logo (Imagen)')" />
                                        <x-text-input id="modal_logo" class="block mt-1 w-full" type="file" name="logo" />
                                        <p class="text-xs text-gray-500 mt-1">Archivos PNG, JPG o WEBP (Max 2MB).</p>
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

        <!-- Modal de Información de Términos y Condiciones -->
    <div id="termsInfoModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeTermsModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <!-- Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="termsModalTitle">Aprobación Legal</h3>
                                <div class="mt-1">
                                    <p class="text-sm text-gray-500">Detalles de la aceptación de términos y condiciones.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body Content -->
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <span class="block text-xs font-medium text-gray-500 uppercase">Cliente</span>
                                <span id="infoClientName" class="block text-sm font-bold text-gray-900 mt-1">-</span>
                            </div>

                            <div>
                                <span class="block text-xs font-medium text-gray-500 uppercase">Persona de Contacto</span>
                                <span id="infoContactName" class="block text-sm text-gray-700 mt-1">-</span>
                            </div>
                            
                            <div>
                                <span class="block text-xs font-medium text-gray-500 uppercase">Usuario que aceptó</span>
                                <span id="infoUserName" class="block text-sm text-gray-700 mt-1">-</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-medium text-gray-500 uppercase">Fecha y Hora</span>
                                    <span id="infoDate" class="block text-sm text-gray-700 mt-1">-</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-medium text-gray-500 uppercase">Dirección IP</span>
                                    <span id="infoIp" class="block text-sm font-mono text-gray-700 mt-1">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Estado de Pendiente (Oculto por defecto) -->
                        <div id="pendingStatus" class="hidden text-center py-4">
                            <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                Pendiente de Aceptación
                            </span>
                            <p class="text-xs text-gray-500 mt-2">El usuario administrador aún no ha ingresado al sistema para aceptar los términos.</p>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeTermsModal()">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Crear Nuevo Cliente';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('clientForm').action = '{{ route("clients.store") }}';

            const saveButton = document.getElementById('saveButton');
            saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';
            
            document.getElementById('clientModal').classList.remove('hidden');
        }

        function openEditModal(client) {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Editar Cliente: ' + client.name;
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('clientForm').action = '{{ route("clients.update", ":id") }}'.replace(':id', client.id);

            const saveButton = document.getElementById('saveButton');
            saveButton.className = 'inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';

            // Rellenar datos
            document.getElementById('modal_name').value = client.name;
            document.getElementById('modal_prefix').value = client.prefix || '';
            document.getElementById('modal_contact').value = client.contact_name || '';
            document.getElementById('modal_phone').value = client.phone || '';
            document.getElementById('modal_email').value = client.email || '';

            document.getElementById('clientModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('clientModal').classList.add('hidden');
        }

        function resetForm() {
            document.getElementById('clientForm').reset();
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

                // --- LÓGICA CORREGIDA ---
                if (response.ok) {
                    // Si todo salió bien (código 200 o redirección exitosa), recargamos la página
                    window.location.reload();
                    return; 
                }

                // Si hubo error (ej. validación fallida 422), intentamos leer el JSON
                const data = await response.json();
                
                // Mostrar errores de validación si existen
                let errorMsg = 'Ocurrió un error inesperado.';
                if (data.message) {
                    errorMsg = data.message;
                } else if (data.errors) {
                    // Unimos todos los errores de validación en un solo string
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                alert('Error: ' + errorMsg);

            } catch (error) {
                console.error('Error de red:', error);
                alert('Ocurrió un error de red al intentar guardar.');
            } finally {
                saveButton.disabled = false;
                saveButton.innerText = originalButtonText;
            }
        }

        // Script Búsqueda Automática
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
        // Función para abrir el modal de Términos
        function openTermsModal(clientName, contactName, adminUser) {
            // Asignar nombre del cliente
            document.getElementById('infoClientName').innerText = clientName;

            // ASIGNAR NOMBRE DEL CONTACTO
            document.getElementById('infoContactName').innerText = contactName;
            
            // Verificar si el usuario ha aceptado términos
            const termsAcceptedAt = adminUser.terms_accepted_at;
            const termsIp = adminUser.terms_accepted_ip;
            const userName = adminUser.name;
            
            const pendingStatus = document.getElementById('pendingStatus');
            const contentGrid = document.querySelector('#termsInfoModal .grid'); 

            if (termsAcceptedAt) {
                pendingStatus.classList.add('hidden');
                document.getElementById('infoUserName').innerText = userName;
                
                const dateObj = new Date(termsAcceptedAt);
                const formattedDate = dateObj.toLocaleDateString('es-MX', { 
                    year: 'numeric', month: 'long', day: 'numeric', 
                    hour: '2-digit', minute: '2-digit' 
                });
                
                document.getElementById('infoDate').innerText = formattedDate;
                document.getElementById('infoIp').innerText = termsIp || 'No registrada';
                contentGrid.classList.remove('hidden');

            } else {
                pendingStatus.classList.remove('hidden');
                document.getElementById('infoUserName').innerText = userName + ' (Sin aceptar)';
                document.getElementById('infoDate').innerText = '-';
                document.getElementById('infoIp').innerText = '-';
            }

            document.getElementById('termsInfoModal').classList.remove('hidden');
        }

        // Función para cerrar el modal de Términos
        function closeTermsModal() {
            document.getElementById('termsInfoModal').classList.add('hidden');
        }
    </script>
    
</x-app-layout>