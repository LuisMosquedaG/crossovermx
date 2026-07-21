<x-app-layout>

<!-- ESTILOS FINALES (PRIORIDAD EN SALTO DE PÁGINA) -->
<style>
    /* --- MODO VISTA PREVIA (En Pantalla - Modal) --- */
    body.printing-mode {
        overflow: hidden;
    }

    /* Difuminar el fondo */
    body.printing-mode > *:not(#printable-area) {
        filter: blur(5px);
        pointer-events: none;
    }

    /* El área de impresión oculta por defecto */
    #printable-area {
        display: none;
    }

    /* Activar vista modal (Solo para pantalla) */
    #printable-area.preview-active {
        display: block;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 95%;
        height: 90vh;
        background-color: #f3f4f6;
        border-radius: 16px;
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        padding: 20px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
    }

    /* --- MODO IMPRESIÓN (En PDF) --- */
    @media print {
        /* 1. Resetear página */
        body, html {
            margin: 0 !important;
            padding: 0 !important;
            height: 100%;
            width: 100%;
        }

        /* 2. Ocultar todo lo que no sea printable-area */
        body.printing-mode > *:not(#printable-area) {
            display: none !important;
        }

        /* 3. CAMBIO CLAVE: Usamos position absolute para flujo normal de documento */
        /* Esto permite al navegador calcular alturas reales de papel, no de pantalla */
        #printable-area {
            display: block !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: auto !important; /* Crucial para saltar de hoja */
            background-color: white !important;
            z-index: 999999 !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            overflow: visible !important;
            transform: none !important;
        }

        /* 4. Centrado del contenido (El div interno de las tarjetas) */
        #printable-area > div {
            margin: 0 auto !important;
            max-width: 210mm !important;
            width: 100% !important;
            padding: 5px !important; /* Un pequeño padding lateral por si acaso */
        }

        /* 5. REGLAS DE SALTO DE PÁGINA (Más estrictas) */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 3px; /* Espacio entre filas */
        }

        /* EVITAR CORTAR UNA FILA ENTERA */
        tr {
            page-break-inside: avoid;
        }
        
        /* EVITAR CORTAR LA TARJETA (TD) */
        td {
            page-break-inside: avoid;
        }
        
        /* Evitar cortar el header de la hoja */
        #printable-area > div > div:first-child {
            page-break-after: avoid;
        }

        @page {
            size: A4 portrait;
            margin: 0; /* Sin márgenes del navegador */
        }

        /* Asegurar colores */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

    <!-- Carga de librería externa (Movida fuera del tbody para validación HTML correcta) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <div class="py-12">
        <div class="w-[96%] md:w-[90%] mx-auto mb-[10vh]">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Contenedor Flex: Botón y Buscador -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        
                        <!-- 1. El Botón Crear -->
                        @if(!auth()->user()->hasRole('Coach'))
                            <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                                Crear Nuevo Equipo
                            </button>
                        @endif

                        <!-- 2. FORMULARIO UNIFICADO DE BÚSQUEDA Y FILTROS -->
                        <form action="{{ route('teams.index') }}" method="GET" class="w-full md:flex-1 flex flex-col md:flex-row gap-3">
                            
                            <!-- Buscador de Texto -->
                            <div class="relative w-full md:flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                    placeholder="Buscar por nombre, entrenador...">
                            </div>

                            <!-- Filtro: Categoría -->
                            <div class="w-full md:w-48">
                                <select name="category" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todas las Categorías</option>
                                    <option value="Varonil" {{ request('category') == 'Varonil' ? 'selected' : '' }}>Varonil</option>
                                    <option value="Femenil" {{ request('category') == 'Femenil' ? 'selected' : '' }}>Femenil</option>
                                    <option value="Mixto" {{ request('category') == 'Mixto' ? 'selected' : '' }}>Mixto</option>
                                </select>
                            </div>

                            <!-- Filtro: Torneo -->
                            <div class="w-full md:w-48">
                                <select name="tournament_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todos los Torneos</option>
                                    @foreach ($tournaments as $t)
                                        <option value="{{ $t->id }}" {{ request('tournament_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro: Fuerza (Dentro del formulario de búsqueda superior) -->
                            <div class="w-full md:w-48">
                                <select name="strength" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todas las Fuerzas</option>
                                    
                                    <!-- Opciones Dinámicas -->
                                    @foreach ($strengths as $str)
                                        <option value="{{ $str->name }}" {{ request('strength') == $str->name ? 'selected' : '' }}>
                                            {{ $str->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </form>

                    </div>

                    @if (session('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    <!-- INICIO CAMBIO: Envolver tabla principal para Responsive -->
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- CAMBIO: Columna Acciones movida al inicio -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fuerza</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Entrenador</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Torneo</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($teams as $team)
                                    <tr>
                                        <!-- CAMBIO: Columna Acciones movida al inicio -->
                                        <!-- 8. COLUMNA: ACCIONES -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-center space-x-3">
                                                
                                                @if(auth()->user()->hasRole('Coach') && $team->coach_id == auth()->id() && !$team->contract_accepted_at)
                                                    <button onclick="openContractModal({{ $team->toJson() }})" class="text-orange-600 hover:text-orange-900 animate-pulse" title="Firma tu Contrato (Pendiente)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                @if(!auth()->user()->hasRole('Coach'))
                                                    <button onclick="openEditModal({{ $team->toJson() }})" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                <!-- INICIO CAMBIO: Ocultar botón si es Coach -->
                                                @if(!auth()->user()->hasRole('Coach'))
                                                    <!-- NUEVO BOTÓN: Horarios Preferidos del Equipo -->
                                                    <button onclick="openTeamScheduleModal({{ $team->toJson() }})" class="text-indigo-500 hover:text-indigo-800" title="Horarios Preferidos">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                        </svg>
                                                    </button>
                                                @endif
                                                <!-- FIN CAMBIO -->

                                                <button onclick="openStatsModal({{ $team->toJson() }})" class="text-purple-600 hover:text-purple-900" title="Ver Estadísticas Totales">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z" />
                                                    </svg>
                                                </button>

                                                <button onclick="showPlayersListModal({{ $team->toJson() }})" class="text-green-600 hover:text-green-900" title="Ver Jugadores">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                    </svg>
                                                </button>

                                                <!-- Botón Imprimir Credenciales -->
                                                <button onclick="downloadTeamCredentials({{ $team->toJson() }})" class="text-blue-600 hover:text-blue-900" title="Imprimir Credenciales del Equipo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.218-.54-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                                    </svg>
                                                </button>
                                                
                                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
                                                    <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este equipo?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- 1. COLUMNA: IMAGEN -->
                                        <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                            @if($team->image_path)
                                                <img src="{{ asset('storage/' . $team->image_path) }}" alt="{{ $team->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold">
                                                    {{ substr($team->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 2. COLUMNA: NOMBRE -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">{{ $team->name }}</td>

                                        <!-- 3. COLUMNA: CATEGORÍA (NUEVO) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            @if($team->category == 'Femenil')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">
                                                    {{ $team->category }}
                                                </span>
                                            @elseif($team->category == 'Mixto')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    {{ $team->category }}
                                                </span>
                                            @elseif($team->category == 'Varonil')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $team->category }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <!-- 4. COLUMNA: FUERZA (NUEVO) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $team->strength ?? '-' }}
                                            </span>
                                        </td>

                                        <!-- 5. COLUMNA: ENTRENADOR -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ $team->coach ? $team->coach->name : ($team->coach_name ?? 'Sin asignar') }}
                                        </td>

                                        <!-- 6. COLUMNA: TORNEO -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $team->tournament->name ?? 'Sin torneo' }}</td>
                                        
                                        <!-- 7. COLUMNA: ESTATUS -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            @if($team->status == 'active')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            @elseif($team->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">Pendiente</span>
                                            @elseif($team->status == 'suspended')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspendido ({{ $team->suspension_games ?? 0 }} part.)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <!-- IMPORTANTE: Actualiza el colspan a 8 para que cubra todas las columnas nuevas -->
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No hay equipos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- FIN CAMBIO -->

                    <!-- CONTROLES DE PAGINACIÓN -->
                    <div class="mt-4">
                        {{ $teams->links() }}
                    </div>
                    <!-- FIN CONTROLES DE PAGINACIÓN -->

                </div>
            </div>
        </div>

    </x-app-layout>

<!-- Modal para Crear/Editar Equipo -->
<div id="teamModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <form id="teamForm" onsubmit="submitTeamForm(event)">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">
                                    Crear Nuevo Equipo
                                </h3>
                                <div class="mb-4">
                                    <x-input-label for="modal_name" :value="__('Nombre del Equipo')" />
                                    <!-- CAMBIO: Agregado focus naranja -->
                                    <x-text-input id="modal_name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="name" required />
                                </div>
                                
                                <div class="mb-4">
                                    <!-- Etiqueta -->
                                    <x-input-label for="modal_coach_id" :value="__('Entrenador Asignado')" />
                                    
                                    <!-- Grupo: Select + Botón Unido -->
                                    <div class="flex mt-1">
                                        
                                        <!-- SELECT (Parte Izquierda: Redondeado a la izquierda) -->
                                        <select id="modal_coach_id" name="coach_id" 
                                            class="flex-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-l-md shadow-sm block w-full border py-2 px-3 bg-white">
                                            <option value="">-- Sin Entrenador --</option>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- BOTÓN (Parte Derecha: Color Naranja, Redondeado a la derecha) -->
                                        <button type="button" onclick="openQuickCoachModal()" 
                                            class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-md border border-l-0 border-orange-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                            title="Crear nuevo entrenador">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>

                                    </div>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="modal_tournament_id" :value="__('Torneo')" />
                                    <!-- CAMBIO: Indigo -> Orange -->
                                    <select id="modal_tournament_id" name="tournament_id" class="border-gray-300 focus-border-orange-500 focus-ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="">-- Sin torneo (Opcional) --</option>
                                        @foreach ($tournaments as $tournament)
                                            <option value="{{ $tournament->id }}" data-status="{{ $tournament->status }}">{{ $tournament->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="modal_status" :value="__('Estatus del Equipo')" />
                                    <!-- CAMBIO: Indigo -> Orange -->
                                    <select id="modal_status" name="status" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="pending">Pendiente (Necesita firma)</option>
                                        <option value="active">Activo</option>
                                        <option value="suspended">Suspendido</option>
                                    </select>
                                </div>
                                <!-- CAMBIOS AQUÍ: Nuevos Campos -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="modal_category" :value="__('Categoría')" />
                                            <!-- CAMBIO: Indigo -> Orange -->
                                            <select id="modal_category" name="category" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                                <option value="">-- Seleccionar --</option>
                                                <option value="Varonil">Varonil</option>
                                                <option value="Femenil">Femenil</option>
                                                <option value="Mixto">Mixto</option>
                                                <option value="Infantil">Infantil</option>
                                            </select>
                                        </div>
                                    <div>
                                        <x-input-label for="modal_strength" :value="__('Fuerza / Nivel')" />
                                        
                                        <div class="flex mt-1">
                                            <!-- SELECT Dinámico -->
                                            <select id="modal_strength" name="strength" class="flex-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-l-md shadow-sm block w-full border py-2 px-3 bg-white">
                                                <option value="">-- Seleccionar --</option>
                                                @foreach ($strengths as $str)
                                                    <option value="{{ $str->name }}">{{ $str->name }}</option>
                                                @endforeach
                                            </select>

                                            <!-- BOTÓN + AGREGAR FUERZA -->
                                            <button type="button" onclick="openAddStrengthModal()" 
                                                class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-md border border-l-0 border-orange-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                                title="Agregar nueva fuerza">
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="modal_image" :value="__('Logo del Equipo')" />
                                    <!-- CAMBIO: Agregado focus naranja -->
                                    <x-text-input id="modal_image" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="file" name="image" />
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-primary-button type="submit" id="saveButton" class="w-full sm:ml-3 sm:w-auto">
                            Guardar
                        </x-primary-button>
                        <button type="button" onclick="closeTeamModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Coach Rápido -->
<div id="quickCoachModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                
                <form id="quickCoachForm" onsubmit="submitQuickCoachForm(event)">
                    @csrf
                    
                    <!-- Campo oculto para forzar el rol de Coach -->
                    <input type="hidden" name="role" value="Coach">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 1-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 5.472m0 0a9.09 9.09 0 0 1-3.74-.479 3 3 0 0 0 4.682-2.72m.94 3.198c.083-.89.142-1.79.18-2.692M14.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                </svg>
                            </div>

                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Nuevo Entrenador</h3>
                                <div class="mt-4 space-y-4">
                                    
                                    <!-- Nombre -->
                                    <div>
                                        <label for="q_name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                        <input type="text" name="name" id="q_name" required placeholder="Ej. Juan Pérez" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="q_email" class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <input type="text" name="email" id="q_email" required placeholder="Ej. juan.perez" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                        <p class="text-xs text-gray-500 mt-1">Solo el usuario (sin @). El dominio del cliente se agregará automáticamente.</p>
                                    </div>

                                    <!-- Contraseña -->
                                    <div>
                                        <label for="q_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                        <input type="password" name="password" id="q_password" required 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>

                                    <!-- NUEVO: Confirmar Contraseña -->
                                    <div>
                                        <label for="q_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                        <input type="password" name="password_confirmation" id="q_password_confirmation" required 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">
                            Crear y Asignar
                        </button>
                        <button type="button" onclick="closeQuickCoachModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>        

<!-- Modal para Crear/Editar Equipo -->
<div id="teamModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <form id="teamForm" onsubmit="submitTeamForm(event)">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">
                                    Crear Nuevo Equipo
                                </h3>
                                <div class="mb-4">
                                    <x-input-label for="modal_name" :value="__('Nombre del Equipo')" />
                                    <!-- CAMBIO: Agregado focus naranja -->
                                    <x-text-input id="modal_name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="name" required />
                                </div>
                                
                                <div class="mb-4">
                                    <!-- Etiqueta -->
                                    <x-input-label for="modal_coach_id" :value="__('Entrenador Asignado')" />
                                    
                                    <!-- Grupo: Select + Botón Unido -->
                                    <div class="flex mt-1">
                                        
                                        <!-- SELECT (Parte Izquierda: Redondeado a la izquierda) -->
                                        <select id="modal_coach_id" name="coach_id" 
                                            class="flex-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-l-md shadow-sm block w-full border py-2 px-3 bg-white">
                                            <option value="">-- Sin Entrenador --</option>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- BOTÓN (Parte Derecha: Color Naranja, Redondeado a la derecha) -->
                                        <button type="button" onclick="openQuickCoachModal()" 
                                            class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-md border border-l-0 border-orange-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                            title="Crear nuevo entrenador">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>

                                    </div>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="modal_tournament_id" :value="__('Torneo')" />
                                    <!-- CAMBIO: Indigo -> Orange -->
                                    <select id="modal_tournament_id" name="tournament_id" class="border-gray-300 focus-border-orange-500 focus-ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="">-- Sin torneo (Opcional) --</option>
                                        @foreach ($tournaments as $tournament)
                                            <option value="{{ $tournament->id }}" data-status="{{ $tournament->status }}">{{ $tournament->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="modal_status" :value="__('Estatus del Equipo')" />
                                    <!-- CAMBIO: Indigo -> Orange -->
                                    <select id="modal_status" name="status" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="pending">Pendiente (Necesita firma)</option>
                                        <option value="active">Activo</option>
                                        <option value="suspended">Suspendido</option>
                                    </select>
                                </div>
                                <!-- CAMBIOS AQUÍ: Nuevos Campos -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="modal_category" :value="__('Categoría')" />
                                            <!-- CAMBIO: Indigo -> Orange -->
                                            <select id="modal_category" name="category" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                                <option value="">-- Seleccionar --</option>
                                                <option value="Varonil">Varonil</option>
                                                <option value="Femenil">Femenil</option>
                                                <option value="Mixto">Mixto</option>
                                                <option value="Infantil">Infantil</option>
                                            </select>
                                        </div>
                                    <div>
                                        <x-input-label for="modal_strength" :value="__('Fuerza / Nivel')" />
                                        <!-- CAMBIO: Indigo -> Orange -->
                                        <select id="modal_strength" name="strength" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="">-- Seleccionar --</option>
                                            <option value="1ra (Alta)">1ra Fuerza (Alta)</option>
                                            <option value="2da (Media)">2da Fuerza (Media)</option>
                                            <option value="3ra (Baja)">3ra Fuerza (Baja)</option>
                                            <option value="Recreacional">Recreacional</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="modal_image" :value="__('Logo del Equipo')" />
                                    <!-- CAMBIO: Agregado focus naranja -->
                                    <x-text-input id="modal_image" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="file" name="image" />
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-primary-button type="submit" id="saveButton" class="w-full sm:ml-3 sm:w-auto">
                            Guardar
                        </x-primary-button>
                        <button type="button" onclick="closeTeamModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 1: Lista de Jugadores de un Equipo -->
<div id="playersListModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-5xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="playersListModalTitle">
                                Jugadores del Equipo
                            </h3>
                            <!-- INICIO CAMBIO: Tabla interna del modal con scroll -->
                            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">RFC</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Posición</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="playersTableBody" class="bg-white divide-y divide-gray-200">
                                    </tbody>
                                </table>
                                <div id="noPlayersMessage" class="hidden text-center py-4 text-gray-500">
                                    Este equipo aún no tiene jugadores.
                                </div>
                            </div>
                            <!-- FIN CAMBIO -->
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Coach'))
                        <!-- CAMBIO: Se agregó el id="btnAddPlayer" -->
                        <button type="button" id="btnAddPlayer" onclick="openCreatePlayerModalFromList()" class="bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 sm:ml-3">
                            Agregar Nuevo Jugador
                        </button>
                    @endif
                    <button type="button" onclick="closePlayersListModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: Crear/Editar Jugador (DISEÑO CON PESTAÑAS Y NARANJA) -->
<div id="playerModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <!-- Contenedor max-w-3xl -->
            <div class="relative w-full max-w-3xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <form id="playerForm" onsubmit="submitPlayerForm(event)" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    
                    <!-- TÍTULO -->
                    <div class="bg-white px-4 py-3 sm:p-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-2 border-b pb-1" id="modalTitle">
                            Crear Nuevo Jugador
                        </h3>
                    </div>

                    <!-- NAVEGACIÓN DE PESTAÑAS -->
                    <div class="px-4 pt-2 sm:px-6 sm:pt-4">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button type="button" onclick="switchTab('personal')" id="tab-btn-personal" class="text-orange-600 border-orange-500 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                                    Datos Personales
                                </button>
                                <button type="button" onclick="switchTab('emergency')" id="tab-btn-emergency" class="text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                                    Contacto de Emergencia
                                </button>
                            </nav>
                        </div>
                    </div>

                    <div class="px-4 py-4 sm:p-6">
                        
                        <!-- PESTAÑA 1: DATOS PERSONALES -->
                        <div id="tab-personal">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <div class="md:col-span-2">
                                    <x-input-label for="modal_player_name" :value="__('Nombre Completo *')" />
                                    <x-text-input id="modal_player_name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="name" required />
                                </div>
                                
                                <div>
                                    <x-input-label for="modal_player_rfc" :value="__('RFC')" />
                                    <x-text-input id="modal_player_rfc" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="rfc" maxlength="13" placeholder="Ej: ABCD123456XYZ" />
                                </div>

                                <div>
                                    <x-input-label for="modal_player_curp" :value="__('CURP *')" />
                                    <x-text-input id="modal_player_curp" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="curp" maxlength="18" placeholder="18 caracteres" required />
                                </div>

                                <div>
                                    <x-input-label for="modal_player_number" :value="__('Número *')" />
                                    <x-text-input id="modal_player_number" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="number" name="number" required />
                                </div>

                                <div>
                                    <x-input-label for="modal_player_position" :value="__('Posición')" />
                                    <x-text-input id="modal_player_position" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="position" placeholder="Ej: Base, Alero" />
                                </div>

                                <div>
                                    <x-input-label for="modal_player_gender" :value="__('Sexo')" />
                                    <select id="modal_player_gender" name="gender" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                        <option value="">Seleccionar...</option>
                                        <option value="hombre">Hombre</option>
                                        <option value="mujer">Mujer</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="modal_player_blood_type" :value="__('Tipo de Sangre')" />
                                    <select id="modal_player_blood_type" name="blood_type" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                        <option value="">Seleccionar...</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="modal_player_team_id" :value="__('Equipo')" />
                                    @if(auth()->user()->hasRole('Coach'))
                                        <input type="hidden" name="team_id" id="modal_player_team_id" value="{{ $teams->first()->id ?? '' }}">
                                        <select class="bg-gray-100 border-gray-300 text-gray-500 cursor-not-allowed rounded-md shadow-sm mt-1 block w-full text-sm" disabled>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}" selected>{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select id="modal_player_team_id" name="team_id" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>

                                <div>
                                    <x-input-label for="modal_player_status" :value="__('Estatus')" />
                                    <select id="modal_player_status" name="status" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                        <option value="active">Activo</option>
                                        <option value="suspended">Suspendido</option>
                                        <option value="expelled">Expulsado</option>
                                    </select>
                                </div>

                                <!-- FOTO AL LADO DE ESTATUS -->
                                <div>
                                    <x-input-label for="modal_player_image" :value="__('Foto *')" />
                                    <input type="file" name="image" id="modal_player_image" class="mt-1 block w-full text-sm text-gray-500 focus:outline-none focus:border-orange-500 file:mr-4 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700" />
                                </div>
                            </div>
                        </div>

                        <!-- PESTAÑA 2: CONTACTO DE EMERGENCIA -->
                        <div id="tab-emergency" class="hidden">
                            <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                                <h4 class="text-sm font-bold text-orange-800 mb-3 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Información de Emergencia
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label for="modal_player_emergency_name" :value="__('Nombre del Contacto')" />
                                        <x-text-input id="modal_player_emergency_name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="emergency_contact_name" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="modal_player_emergency_relationship" :value="__('Parentesco')" />
                                        <x-text-input id="modal_player_emergency_relationship" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="emergency_contact_relationship" placeholder="Ej: Padre, Esposa" />
                                    </div>

                                    <div>
                                        <x-input-label for="modal_player_emergency_phone" :value="__('Teléfono')" />
                                        <x-text-input id="modal_player_emergency_phone" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="emergency_contact_phone" placeholder="Ej: 55 1234 5678" />
                                    </div>

                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label for="modal_player_emergency_address" :value="__('Dirección')" />
                                        <textarea id="modal_player_emergency_address" name="emergency_contact_address" rows="2" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-primary-button type="submit" id="savePlayerButton" class="w-full sm:ml-3 sm:w-auto">
                            Guardar
                        </x-primary-button>
                        <button type="button" onclick="closePlayerModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO: Ver Credencial (Jugador) -->
<div id="viewModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-sm bg-white rounded-xl shadow-xl overflow-visible">
                
                <!-- PUNTO DE ESTATUS (Esquina superior derecha) -->
                <div id="view_status_dot" class="absolute top-4 right-4 w-4 h-4 rounded-full border-2 border-white shadow-sm z-20"></div>

                <!-- Foto Grande Flotante -->
                <div id="view_image_container" class="absolute -top-20 left-1/2 -translate-x-1/2 h-40 w-40 rounded-full border-4 border-white shadow-xl bg-gray-200 overflow-hidden flex items-center justify-center z-50">
                    <img id="view_image" src="" alt="" class="h-full w-full object-cover hidden">
                    <span id="view_initial" class="text-6xl font-bold text-gray-500 hidden"></span>
                </div>

                <!-- Contenido de la Credencial -->
                <div class="pt-28 pb-6 px-6 text-center relative z-10">
                    
                    <h3 id="view_name" class="text-xl font-bold leading-6 text-gray-900"></h3>
                    
                    <!-- 1. Línea Principal: Torneo | Equipo | Número | Posición -->
                    <div class="flex justify-center items-center gap-2 text-sm text-gray-600 mt-1 font-medium flex-wrap">
                        <span id="view_tournament">-</span>
                        <span class="text-gray-300">|</span>
                        <span id="view_team" class="text-orange-600">-</span>
                        <span class="text-gray-300">|</span>
                        <span># <span id="view_number">-</span></span>
                        <span class="text-gray-300">|</span>
                        <span id="view_position">-</span>
                    </div>

                    <!-- 2. Segunda Línea: Fuerza | Categoría -->
                    <div class="flex justify-center items-center gap-2 text-xs text-gray-500 mt-0 font-medium uppercase">
                        <span id="view_strength">-</span>
                        <span class="text-gray-300">|</span>
                        <span id="view_category">-</span>
                    </div>

                    <div class="mt-6 text-left">
                        
                        <!-- SECCIÓN GRIS (Informativa) -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 relative">
                            
                            <!-- Botón para cambiar vista -->
                            <button onclick="toggleCredentialView()" class="absolute top-2 right-2 text-gray-400 hover:text-orange-600 transition-colors p-1" title="Cambiar vista">
                                <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                            </button>

                            <!-- Título Dinámico (Estilo Naranja) -->
                            <p id="infoSectionTitle" class="text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2">DATOS PERSONALES</p>

                            <!-- VISTA 1: DATOS PERSONALES -->
                            <div id="view_personal_info" class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">RFC:</span>
                                    <span id="view_rfc" class="font-semibold text-gray-900 text-sm">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">CURP:</span>
                                    <span id="view_curp" class="font-semibold text-gray-900 text-sm">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Sexo:</span>
                                    <span id="view_gender" class="font-semibold text-gray-900 text-sm">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Tipo Sangre:</span>
                                    <span id="view_blood_type" class="font-semibold text-gray-900 text-sm">-</span>
                                </div>
                            </div>

                            <!-- VISTA 2: DATOS DE EMERGENCIA -->
                            <div id="view_emergency_info" class="space-y-2 hidden">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Contacto:</span>
                                    <span id="view_emergency_name" class="font-semibold text-gray-900 text-sm text-right">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Parentesco:</span>
                                    <span id="view_emergency_relationship" class="font-semibold text-gray-900 text-sm">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Teléfono:</span>
                                    <span id="view_emergency_phone" class="font-semibold text-gray-900 text-sm">-</span>
                                </div>
                                <div class="text-sm pt-1 border-t border-gray-200 mt-2">
                                    <span class="text-gray-500 block text-xs mb-1">Dirección:</span>
                                    <span id="view_emergency_address" class="text-gray-900 font-medium">-</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="button" onclick="closeViewModal()" class="w-full inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Estadísticas Acumuladas del Equipo -->
<div id="teamStatsModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4 text-center" id="statsModalTitle">
                                Estadísticas Acumuladas
                            </h3>
                            <!-- INICIO CAMBIO: Tabla de estadísticas con scroll -->
                            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                <table class="min-w-full text-sm text-left">
                                    <thead class="bg-gray-100 text-gray-600">
                                        <tr>
                                            <th class="px-3 py-2">Jugador</th>
                                            <th class="px-3 py-2 text-center">1 Pt</th>
                                            <th class="px-3 py-2 text-center">2 Pt</th>
                                            <th class="px-3 py-2 text-center">3 Pt</th>
                                            <th class="px-3 py-2 text-center font-bold bg-gray-200">Total</th>
                                            <th class="px-3 py-2 text-center">Faltas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="teamStatsTableBody" class="divide-y divide-gray-200">
                                        <!-- Se llenará con JS -->
                                    </tbody>
                                </table>
                                <div id="noStatsMessage" class="hidden text-center py-4 text-gray-500 bg-white">
                                    Este equipo aún no tiene estadísticas registradas.
                                </div>
                            </div>
                            <!-- FIN CAMBIO -->
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeStatsModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Aceptación de Contrito (Mejorado) -->
<div id="contractModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Firma de Contrato</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Antes de activar tu equipo, debes revisar el reglamento del torneo:
                                    <strong id="contractTournamentName"></strong>.
                                </p>
                                
                                <!-- ÁREA DE REGLAMENTO -->
                                <div class="bg-gray-50 border border-gray-200 rounded-md p-3 mb-4 max-h-40 overflow-y-auto">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Reglamento</h4>
                                    <p id="contractRulesText" class="text-sm text-gray-700 whitespace-pre-wrap italic">
                                        Cargando reglamento...
                                    </p>
                                </div>

                                <!-- BOTÓN DESCARGAR PDF REGLAMENTO -->
                                <div class="text-left mb-4" id="contractPdfButtonContainer">
                                    <button type="button" onclick="downloadContractRulesPDF()" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Descargar Reglamento en PDF
                                    </button>
                                </div>

                                <p class="text-sm text-gray-500">
                                    Al firmar, aceptas las reglas anteriores y tu equipo pasará a <strong>ESTATUS ACTIVO</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="confirmContractBtn" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">
                        Aceptar y Activar Equipo
                    </button>
                    <button type="button" onclick="closeContractModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Gestionar Horarios del Equipo (NUEVO) -->
<div id="teamScheduleModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-3xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <form id="teamScheduleForm" onsubmit="submitTeamSchedules(event)">
                    @csrf
                    <input type="hidden" name="team_id" id="team_schedule_id">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">
                                    Disponibilidad del Equipo
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">Define los días y horas en los que el equipo prefiere jugar.</p>
                                
                                <!-- Contenedor de filas de horarios -->
                                <div id="teamScheduleRows" class="space-y-3">
                                    <!-- Las filas se agregan aquí con JS -->
                                </div>

                                <button type="button" onclick="addTeamScheduleRow()" class="mt-3 flex items-center text-orange-600 hover:text-orange-700 font-medium text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-1">
                                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                    </svg>
                                    Agregar otro horario
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <x-primary-button type="submit" id="saveTeamScheduleButton" class="w-full sm:ml-3 sm:w-auto bg-orange-600 hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:ring-orange-500">
                            Guardar Horarios
                        </x-primary-button>
                        <button type="button" onclick="closeTeamScheduleModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        <!-- MODAL DE ERROR PERSONALIZADO (NUEVO) -->
<div id="errorModal" class="fixed inset-0 hidden" style="z-index: 9999;">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Atención</h3>
                            <div class="mt-2">
                                <p id="errorText" class="text-sm text-gray-500 whitespace-pre-wrap font-medium">
                                    <!-- Aquí se inyectará el mensaje de error -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeErrorModal()" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contenedor para impresión nativa -->
<div id="printable-area" style="display: none;"></div>

<!-- Modal para Agregar Fuerza -->
<div id="addStrengthModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm">
                <form id="addStrengthForm" onsubmit="submitStrengthForm(event)">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Nueva Fuerza</h3>
                                <div class="mt-2">
                                    <label for="new_strength_name" class="block text-sm font-medium text-gray-700">Nombre de la Fuerza</label>
                                    <input type="text" name="name" id="new_strength_name" required placeholder="Ej. 2013-2014" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">
                            Agregar
                        </button>
                        <button type="button" onclick="closeAddStrengthModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- JAVASCRIPT UNIFICADO -->
<script>

    let currentTeamPlayers = [];
    let currentTeamForPlayer = null; 
    const storageUrl = '{{ asset('storage') }}/';

    // ========================
    // LÓGICA DE EQUIPOS
    // ========================

    function openCreateModal() {
        resetTeamForm();
        filterTournamentSelect(null, '');
        document.getElementById('modalTitle').innerText = 'Crear Nuevo Equipo';
        document.getElementById('form_method').value = 'POST';
        document.getElementById('teamForm').action = '{{ route("teams.store") }}';

        const saveButton = document.getElementById('saveButton');
        saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';
        document.getElementById('teamModal').classList.remove('hidden');
    }

    function openEditModal(team) {
        resetTeamForm();
        
        let tournamentId = team.tournament_id;
        const tournamentStatus = team.tournament ? team.tournament.status : '';
        
        // Si el torneo ya finalizó, liberamos al equipo asignando torneo nulo/vacío para que no se muestre
        if (tournamentStatus === 'finished') {
            tournamentId = null;
        }
        
        filterTournamentSelect(tournamentId, tournamentStatus);

        document.getElementById('modalTitle').innerText = 'Editar Equipo: ' + team.name;
        document.getElementById('form_method').value = 'PUT';
        document.getElementById('teamForm').action = '{{ route("teams.update", ":id") }}'.replace(':id', team.id);

        const saveButton = document.getElementById('saveButton');
        saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';
        document.getElementById('modal_name').value = team.name;
        document.getElementById('modal_coach_id').value = team.coach_id || '';
        document.getElementById('modal_tournament_id').value = tournamentId || '';
        document.getElementById('modal_status').value = team.status || 'active';

        // Categoría: Se asigna al select
        document.getElementById('modal_category').value = team.category || '';

        // Fuerza: Se asigna al input de texto (Libre)
        document.getElementById('modal_strength').value = team.strength || '';

        document.getElementById('teamModal').classList.remove('hidden');
    }

    function closeTeamModal() {
        document.getElementById('teamModal').classList.add('hidden');
    }

    function resetTeamForm() {
        document.getElementById('teamForm').reset();
    }

    function filterTournamentSelect(currentTournamentId, currentTournamentStatus) {
        const selects = document.querySelectorAll('select[name="tournament_id"]');
        selects.forEach(select => {
            // Habilita el selector por defecto
            select.disabled = false;
            
            // Filtra las opciones de torneos
            Array.from(select.options).forEach(option => {
                const val = option.value;
                const status = option.getAttribute('data-status');

                if (val === '') {
                    // Opción vacía siempre disponible
                    option.hidden = false;
                    option.style.display = '';
                } else if (currentTournamentId && String(val) === String(currentTournamentId)) {
                    // Torneo actual del equipo siempre visible para que no aparezca en blanco
                    option.hidden = false;
                    option.style.display = '';
                } else if (status === 'pending') {
                    // Mostrar otros torneos pendientes
                    option.hidden = false;
                    option.style.display = '';
                } else {
                    // Ocultar torneos activos y terminados de la selección
                    option.hidden = true;
                    option.style.display = 'none';
                }
            });

            // Si el torneo actual está activo, bloqueamos la selección
            if (currentTournamentId && currentTournamentStatus === 'active') {
                select.disabled = true;
            }
        });
    }

    async function submitPlayerForm(event) {
        event.preventDefault();
        
        // 1. Creamos el FormData vacío (NO confiamos en leer el formulario completo)
        const formData = new FormData();

        // 2. Función auxiliar para buscar el valor y agregarlo manualmente
        const appendVal = (id, name) => {
            let el = document.getElementById(id);
            if (!el) {
                console.error(`Error: No se encontró el ID ${id} para el campo ${name}`);
                return;
            }

            let val = '';
            
            // A. Si el elemento es un input/select directo
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
                // Caso especial: Archivo (Imagen)
                if (el.type === 'file' && el.files.length > 0) {
                    formData.append(name, el.files[0]);
                    console.log(`Archivo agregado: ${name}`, el.files[0].name);
                    return;
                }
                val = el.value;
            } 
            // B. Si es un contenedor (div del componente), buscamos el input adentro
            else {
                const input = el.querySelector('input, select, textarea');
                if (input) {
                    if (input.type === 'file' && input.files.length > 0) {
                        formData.append(name, input.files[0]);
                        console.log(`Archivo agregado (wrapper): ${name}`, input.files[0].name);
                        return;
                    }
                    val = input.value;
                } else {
                    console.error(`Error: El ID ${id} existe pero no tiene input dentro.`);
                }
            }
            
            // Agregamos al FormData con el NOMBRE correcto que espera Laravel
            formData.append(name, val);
            console.log(`Campo agregado manualmente: ${name} = ${val}`);
        };

        // 3. Agregamos campos de control
        formData.append('_method', document.getElementById('form_method').value);
        formData.append('_token', '{{ csrf_token() }}');

        // 4. Agregamos TODOS los campos uno por uno
        // DATOS PERSONALES
        appendVal('modal_player_name', 'name');
        appendVal('modal_player_rfc', 'rfc');
        appendVal('modal_player_curp', 'curp');
        appendVal('modal_player_number', 'number');
        appendVal('modal_player_position', 'position');
        appendVal('modal_player_gender', 'gender');
        appendVal('modal_player_blood_type', 'blood_type');
        appendVal('modal_player_team_id', 'team_id');
        appendVal('modal_player_status', 'status');
        appendVal('modal_player_image', 'image'); // La imagen si se cambia

        // DATOS DE EMERGENCIA
        appendVal('modal_player_emergency_name', 'emergency_contact_name');
        appendVal('modal_player_emergency_relationship', 'emergency_contact_relationship');
        appendVal('modal_player_emergency_phone', 'emergency_contact_phone');
        appendVal('modal_player_emergency_address', 'emergency_contact_address');

        // 5. Enviamos
        const form = document.getElementById('playerForm');
        const httpMethod = document.getElementById('form_method').value;

        try {
            const response = await fetch(form.action, {
                method: httpMethod,
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                    // NOTA: No ponemos X-CSRF-TOKEN aquí porque ya lo agregamos manualmente arriba como '_token'
                }
            });

            if (response.ok) {
                closePlayerModal();
                if (currentTeamForPlayer) {
                    await showPlayersListModal(currentTeamForPlayer);
                }
            } else {
                let errorDetails = "Error desconocido";
                try {
                    const data = await response.json();
                    if (data.message) errorDetails = data.message;
                    if (data.errors) errorDetails += "\n" + JSON.stringify(data.errors);
                } catch (e) {
                    errorDetails = await response.text();
                }
                console.error("Error:", response.status, errorDetails);
                alert(`Error ${response.status}: ${errorDetails}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado de conexión.');
        }
    }

    // ========================
    // LÓGICA DE JUGADORES
    // ========================

    // --- Lista de Jugadores (Actualizada) ---
    async function showPlayersListModal(team) {
        currentTeamForPlayer = team;
        document.getElementById('playersListModalTitle').innerText = `Jugadores del equipo: ${team.name}`;
        document.getElementById('playersTableBody').innerHTML = '';
        document.getElementById('noPlayersMessage').classList.add('hidden');

        try {
            const response = await fetch(`/teams/${team.id}/players/json`);
            if (!response.ok) throw new Error('Network response was not ok');
            const players = await response.json();
            
            // 1. Guardamos la lista completa en la variable global
            currentTeamPlayers = players;
            
            const tableBody = document.getElementById('playersTableBody');
            if (players.length > 0) {
            players.forEach(player => {
                // --- 1. LÓGICA DE IMAGEN ---
                let playerImage = '';
                if (player.image_path) {
                    let path = player.image_path;
                    let finalUrl = '';
                    if (path.startsWith('http')) {
                        finalUrl = path;
                    } else {
                        if (path.startsWith('/')) path = path.substring(1);
                        if (path.startsWith('storage/')) path = path.replace('storage/', '');
                        finalUrl = storageUrl + path;
                    }
                    playerImage = `<img src="${finalUrl}" alt="${player.name}" class="h-10 w-10 rounded-full object-cover">`;
                } 
                else if (player.gender === 'hombre') {
                    playerImage = `<img src="/images/hombre.png" alt="${player.name}" class="h-10 w-10 rounded-full object-cover">`;
                } 
                else if (player.gender === 'mujer') {
                    playerImage = `<img src="/images/mujer.png" alt="${player.name}" class="h-10 w-10 rounded-full object-cover">`;
                } 
                else {
                    playerImage = `<div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold">${player.name.substring(0, 1).toUpperCase()}</div>`;
                }

                // --- 2. LÓGICA DE ESTATUS ---
                let statusBadge = '';
                if (player.status === 'suspended') {
                    statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspendido (${player.suspension_games || 0} part.)</span>`;
                } else if (player.status === 'expelled') {
                    statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expulsado</span>`;
                } else {
                    statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>`;
                }

                // --- 3. DEFINICIÓN DE BOTONES ---
                const viewCredentialButton = `
                    <button onclick='openViewModal(${JSON.stringify(player)})' class="text-green-600 hover:text-green-900" title="Ver Credencial">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                `;

                // --- CAMBIO AQUÍ: Pasamos solo el ID (player.id) en lugar del objeto JSON ---
                const editPlayerButton = `
                    <button onclick='openEditPlayerModalFromList(${player.id})' class="text-indigo-600 hover:text-indigo-900" title="Editar Jugador">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </button>
                `;

                const printSingleButton = `
                    <button onclick='printSingleCredential(${JSON.stringify(player)})' class="text-blue-600 hover:text-blue-900" title="Imprimir Credencial">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.218-.54-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </button>
                `;

                // --- 4. CREACIÓN DE LA FILA ---
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">${playerImage}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">${player.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${player.rfc}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${player.number}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${player.position}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">${statusBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center justify-center space-x-3">
                                ${viewCredentialButton}
                                ${editPlayerButton} 
                                ${printSingleButton}
                            </div>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
            } else {
                document.getElementById('noPlayersMessage').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error al cargar jugadores:', error);
            alert('No se pudieron cargar los jugadores del equipo.');
        }

        // ==========================================
        // VALIDACIÓN DE BOTÓN AGREGAR
        // ==========================================
        const btnAddPlayer = document.getElementById('btnAddPlayer');
        
        const isCoach = {{ auth()->user()->hasRole('Coach') ? 'true' : 'false' }};
        const isTournamentActive = team.tournament && team.tournament.status === 'active';

        if (isCoach && isTournamentActive) {
            btnAddPlayer.disabled = true;
            btnAddPlayer.removeAttribute('onclick'); 
            btnAddPlayer.className = 'bg-gray-200 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed sm:ml-3 transition-colors border border-gray-300';
            btnAddPlayer.innerText = "Torneo Activo (No editable)";
        } else {
            btnAddPlayer.disabled = false;
            btnAddPlayer.setAttribute('onclick', 'openCreatePlayerModalFromList()');
            btnAddPlayer.className = 'bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 sm:ml-3 transition-colors';
            btnAddPlayer.innerText = "Agregar Nuevo Jugador";
        }

        document.getElementById('playersListModal').classList.remove('hidden');
    }

    function closePlayersListModal() {
        document.getElementById('playersListModal').classList.add('hidden');
    }

    function openCreatePlayerModalFromList() {
        closePlayersListModal();
        openCreatePlayerModal(currentTeamForPlayer.id);
    }

    // --- Crear/Editar Jugador ---
    function openCreatePlayerModal(teamId = null) {
        resetPlayerForm();
        document.getElementById('modalTitle').innerText = 'Crear Nuevo Jugador';
        document.getElementById('form_method').value = 'POST';
        document.getElementById('playerForm').action = '{{ route("players.store") }}';

        if (teamId) {
            document.getElementById('modal_player_team_id').value = teamId;
        }
        document.getElementById('modal_player_status').value = 'active';

        const saveButton = document.getElementById('savePlayerButton');
        saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto'   
        document.getElementById('playerModal').classList.remove('hidden');
    }

    function closePlayerModal() {
        document.getElementById('playerModal').classList.add('hidden');
    }

    function resetPlayerForm() {
        document.getElementById('playerForm').reset();
    }

    // 3. Función de envío (MANEJO CSRF ROBUSTO)
    async function submitPlayerForm(event) {
        event.preventDefault();
        const form = document.getElementById('playerForm');
        
        // --- OBTENCIÓN DEL TOKEN CSRF (MÉTODO ESTÁNDAR LARAVEL) ---
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            alert('Error: No se encontró el token CSRF en la página. Asegúrate de tener <meta name="csrf-token"> en tu layout.');
            return;
        }
        const token = csrfTokenMeta.getAttribute('content');
        // ----------------------------------------------------------------

        const formData = new FormData();

        // Función para obtener valor por NAME y agregarlo al FormData
        const addField = (fieldName) => {
            const el = document.querySelector(`[name="${fieldName}"]`);
            
            if (!el) {
                console.warn(`Campo no encontrado: [name="${fieldName}"]`);
                return;
            }

            if (el.type === 'file' && el.files.length > 0) {
                formData.append(fieldName, el.files[0]);
                console.log(`Agregado Archivo [${fieldName}]:`, el.files[0].name);
            } else {
                const val = el.value || '';
                formData.append(fieldName, val);
                console.log(`Agregado Texto [${fieldName}]:`, val);
            }
        };

        // Agregamos Token y Método al Body (Por seguridad extra)
        formData.append('_token', token);
        formData.append('_method', document.getElementById('form_method').value);

        // Datos Personales
        addField('name');
        addField('rfc');
        addField('curp');
        addField('number');
        addField('position');
        addField('gender');
        addField('blood_type');
        addField('team_id');
        addField('status');
        addField('image'); 

        // Datos de Emergencia
        addField('emergency_contact_name');
        addField('emergency_contact_relationship');
        addField('emergency_contact_phone');
        addField('emergency_contact_address');

        // Envío
        const httpMethod = document.getElementById('form_method').value;

        try {
            const response = await fetch(form.action, {
                method: httpMethod,
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    // --- AGREGAMOS EL TOKEN EN EL HEADER (CLAVE PARA SOLUCIONAR 419) ---
                    'X-CSRF-TOKEN': token
                    // ----------------------------------------------------------------------
                }
            });

            if (response.ok) {
                closePlayerModal();
                if (currentTeamForPlayer) {
                    await showPlayersListModal(currentTeamForPlayer);
                }
            } else {
                let errorDetails = "Error desconocido";
                try {
                    const data = await response.json();
                    if (data.message) errorDetails = data.message;
                    if (data.errors) {
                        let errorList = [];
                        for (const [field, messages] of Object.entries(data.errors)) {
                            errorList.push(`${field}: ${messages.join(', ')}`);
                        }
                        errorDetails += "\n\n" + errorList.join("\n");
                    }
                } catch (e) {
                    errorDetails = await response.text();
                }
                console.error("Error:", response.status, errorDetails);
                alert(`Error ${response.status}: ${errorDetails}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado de conexión.');
        }
    }

    // ========================
    // LÓGICA DE ESTADÍSTICAS (ACTUALIZADA)
    // ========================
    
    async function openStatsModal(team) {
        document.getElementById('statsModalTitle').innerText = `Estadísticas: ${team.name}`;
        const tableBody = document.getElementById('teamStatsTableBody');
        const noStatsMsg = document.getElementById('noStatsMessage');
        
        tableBody.innerHTML = '';
        noStatsMsg.classList.add('hidden');

        try {
            const response = await fetch(`/teams/${team.id}/stats`);
            const statsData = await response.json();

            if (Array.isArray(statsData)) {
                if (statsData.length === 0) {
                    noStatsMsg.classList.remove('hidden');
                } else {
                    statsData.forEach(item => {
                        const points1 = item.stats.points1 ?? 0;
                        const points2 = item.stats.points2 ?? 0;
                        const points3 = item.stats.points3 ?? 0;
                        const totalPoints = points1 + (points2 * 2) + (points3 * 3);
                        const fouls = item.stats.fouls ?? 0;
                        const displayNumber = item.number ? item.number : 'N/A';

                        const row = `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-3 py-2 font-medium">${item.name} (${displayNumber})</td>
                                <td class="px-3 py-2 text-center text-gray-600">${points1}</td>
                                <td class="px-3 py-2 text-center text-gray-600">${points2}</td>
                                <td class="px-3 py-2 text-center text-gray-600">${points3}</td>
                                <td class="px-3 py-2 text-center font-bold text-purple-700">${totalPoints}</td>
                                <td class="px-3 py-2 text-center text-orange-600">${fouls}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                }
            } else {
                if (Object.keys(statsData).length === 0) {
                    noStatsMsg.classList.remove('hidden');
                } else {
                    for (const [playerId, stats] of Object.entries(statsData)) {
                        const totalPoints = (stats.points1 * 1) + (stats.points2 * 2) + (stats.points3 * 3);
                        const row = `
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-medium">Jugador ID: ${playerId}</td>
                                <td class="px-3 py-2 text-center text-gray-600">${stats.points1}</td>
                                <td class="px-3 py-2 text-center text-gray-600">${stats.points2}</td>
                                <td class="px-3 py-2 text-center text-gray-600">${stats.points3}</td>
                                <td class="px-3 py-2 text-center font-bold text-purple-700">${totalPoints}</td>
                                <td class="px-3 py-2 text-center text-orange-600">${stats.fouls}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    }
                }
            }
            
            document.getElementById('teamStatsModal').classList.remove('hidden');

        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
            alert('No se pudieron cargar las estadísticas.');
        }
    }

    function closeStatsModal() {
        document.getElementById('teamStatsModal').classList.add('hidden');
    }

    // ========================
    // LÓGICA VER CREDENCIAL (NUEVA)
    // ========================
    function openViewModal(player) {
        // --- CORRECCIÓN: AGREGAR ESTA LÍNEA ---
        const storageUrl = '{{ asset('storage') }}/'; 
        // ---------------------------------------

        const imgEl = document.getElementById('view_image');
        const initialEl = document.getElementById('view_initial');
        const container = document.getElementById('view_image_container');

        // 1. Manejo de Imagen
        if (player.image_path) {
            imgEl.src = storageUrl + player.image_path;
            imgEl.classList.remove('hidden');
            initialEl.classList.add('hidden');
        } else if (player.gender === 'hombre') {
            // Si no hay foto y es HOMBRE -> Imagen Default
            imgEl.src = '/images/hombre.png';
            imgEl.classList.remove('hidden');
            initialEl.classList.add('hidden');
        } else if (player.gender === 'mujer') {
            // Si no hay foto y es MUJER -> Imagen Default
            imgEl.src = '/images/mujer.png';
            imgEl.classList.remove('hidden');
            initialEl.classList.add('hidden');
        } else {
            // Si no hay foto y no hay género -> Letra
            imgEl.classList.add('hidden');
            initialEl.classList.remove('hidden');
            initialEl.innerText = player.name ? player.name.charAt(0).toUpperCase() : '?';
        }

        // 2. Datos Básicos
        document.getElementById('view_name').innerText = player.name;

        // ---- DATOS DEL EQUIPO Y TORNEO ----
        const teamName = player.team?.name || 'Sin equipo';
        const tournamentName = player.team?.tournament?.name || 'Sin Torneo';
        const categoryName = player.team?.category || '-';
        const strengthName = player.team?.strength || '-';

        document.getElementById('view_tournament').innerText = tournamentName;
        document.getElementById('view_team').innerText = teamName;
        document.getElementById('view_number').innerText = player.number || '-';
        document.getElementById('view_position').innerText = player.position || '-';

        document.getElementById('view_strength').innerText = strengthName;
        document.getElementById('view_category').innerText = categoryName;
        // ------------------------------------

        // 3. Color del Punto de Estatus
        const statusDot = document.getElementById('view_status_dot');
        statusDot.className = 'absolute top-4 right-4 w-4 h-4 rounded-full border-2 border-white shadow-sm z-20 ';
        
        if (player.status === 'active') {
            statusDot.classList.add('bg-green-500');
        } else if (player.status === 'suspended') {
            statusDot.classList.add('bg-yellow-500');
        } else {
            statusDot.classList.add('bg-red-500');
        }

        // 4. Datos Personales
        document.getElementById('view_rfc').innerText = player.rfc || '-';
        document.getElementById('view_curp').innerText = player.curp || '-';
        
        let genderText = player.gender || '-';
        if (player.gender) {
            genderText = player.gender.charAt(0).toUpperCase() + player.gender.slice(1);
        }
        document.getElementById('view_gender').innerText = genderText;
        
        document.getElementById('view_blood_type').innerText = player.blood_type || '-';

        // 5. Datos de Emergencia
        document.getElementById('view_emergency_name').innerText = player.emergency_contact_name || '-';
        document.getElementById('view_emergency_relationship').innerText = player.emergency_contact_relationship || '-';
        document.getElementById('view_emergency_phone').innerText = player.emergency_contact_phone || '-';
        document.getElementById('view_emergency_address').innerText = player.emergency_contact_address || '-';

        // 6. Resetear vista a "Datos Personales" con Estilo Naranja
        document.getElementById('view_personal_info').classList.remove('hidden');
        document.getElementById('view_emergency_info').classList.add('hidden');
        document.getElementById('infoSectionTitle').innerText = "DATOS PERSONALES";
        
        // Aplicar clase Naranja
        document.getElementById('infoSectionTitle').className = "text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2";

        document.getElementById('viewModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }

    // Función para alternar entre Personal y Emergencia
    function toggleCredentialView() {
        const personalSection = document.getElementById('view_personal_info');
        const emergencySection = document.getElementById('view_emergency_info');
        const title = document.getElementById('infoSectionTitle');

        if (personalSection.classList.contains('hidden')) {
            // Si estaba oculto, mostrar Personal
            personalSection.classList.remove('hidden');
            emergencySection.classList.add('hidden');
            title.innerText = "DATOS PERSONALES";
            title.className = "text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2";
        } else {
            // Si estaba visible, mostrar Emergencia
            personalSection.classList.add('hidden');
            emergencySection.classList.remove('hidden');
            title.innerText = "CONTACTO DE EMERGENCIA";
            // Mantener el título en naranja o cambiarlo si prefieres, aquí lo dejamos consistente
            title.className = "text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2";
        }
    }
    
    // === NUEVO SCRIPT PARA BÚSQUEDA AUTOMÁTICA (SERVER-SIDE) ===
    const searchInput = document.querySelector('input[name="search"]');

    if (searchInput) {
        let timeout = null;

        searchInput.addEventListener('keyup', function (e) {
            clearTimeout(timeout);
            // Esperar 500ms para enviar el formulario
            timeout = setTimeout(function () {
                e.target.form.submit();
            }, 500);
        });
    }

    // --- LÓGICA DEL CONTRATO ---

    async function openContractModal(team) {
        document.getElementById('contractTournamentName').innerText = team.tournament ? team.tournament.name : 'Torneo';
        const rulesText = document.getElementById('contractRulesText');
        const pdfBtnContainer = document.getElementById('contractPdfButtonContainer');
        const btn = document.getElementById('confirmContractBtn');
        
        // Mostrar estado de carga
        rulesText.innerText = "Cargando reglamento...";
        pdfBtnContainer.style.display = 'none'; // Ocultar botón descarga mientras carga
        
        // Asignar función de envío
        btn.onclick = function() {
            submitContract(team.id);
        };

        document.getElementById('contractModal').classList.remove('hidden');

        // 1. Cargar Reglamento
        try {
            // Usamos la misma ruta que creamos para los torneos
            const response = await fetch(`/tournaments/${team.tournament_id}/rules`);
            const data = await response.json();

            if (data && data.reglamento) {
                currentContractRules = data.reglamento;
                rulesText.innerText = data.reglamento;
                pdfBtnContainer.style.display = 'block'; // Mostrar botón de descarga
            } else {
                currentContractRules = "Sin reglamento definido para este torneo.";
                rulesText.innerText = "Sin reglamento definido.";
                pdfBtnContainer.style.display = 'none';
            }
        } catch (error) {
            console.error("Error cargando reglamento:", error);
            currentContractRules = "";
            rulesText.innerText = "Error al cargar el reglamento.";
            pdfBtnContainer.style.display = 'none';
        }
    }

    function closeContractModal() {
        document.getElementById('contractModal').classList.add('hidden');
    }


    async function submitContract(teamId) {
        const btn = document.getElementById('confirmContractBtn');
        const originalText = btn.innerText;
        
        btn.innerText = 'Procesando...';
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        try {
            // URL absoluta de la ruta
            const url = `/teams/${teamId}/accept-contract`;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            // Intentar parsear JSON
            const data = await response.json();

            if (response.ok && data.success) {
                // Éxito
                closeContractModal();
                // Opción 1: Alerta simple
                alert(data.message); 
                // Opción 2: Recargar página para ver los cambios
                window.location.reload(); 
            } else {
                // Error del servidor (ej: no autorizado)
                alert(data.message || 'Hubo un problema al procesar tu firma.');
                btn.innerText = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        } catch (error) {
            console.error('Error de red:', error);
            alert('Error de conexión. Por favor intenta de nuevo o actualiza la página.');
            btn.innerText = originalText;
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }
    // --- FUNCIÓN DESCARGAR PDF DEL CONTRATO ---
    function downloadContractRulesPDF() {
        const textContent = currentContractRules;
        const tournamentName = document.getElementById('contractTournamentName').innerText;

        if (!textContent.trim()) {
            alert('No hay reglamento para descargar.');
            return;
        }

        const element = document.createElement('div');
        element.style.padding = '20px';
        element.style.fontFamily = 'Arial, sans-serif';
        
        // Usamos la misma lógica de párrafos que usamos antes para evitar cortes
        const paragraphs = textContent.split('\n');
        let contentHTML = '';
        paragraphs.forEach(line => {
            if (line.trim() !== '') {
                contentHTML += `<p style="margin-bottom: 8px; font-size: 14px; line-height: 1.5; color: #333; page-break-inside: avoid;">${line}</p>`;
            } else {
                contentHTML += `<br>`;
            }
        });

        element.innerHTML = `
            <h1 style="text-align: center; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; page-break-after: avoid;">Reglamento: ${tournamentName}</h1>
            <div>${contentHTML}</div>
        `;

        const opt = {
            margin:       1.0, 
            filename:     `Reglamento_${tournamentName.replace(/\s+/g, '_')}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }

    async function downloadTeamCredentials(team) {
        const btn = event.currentTarget;
        const printArea = document.getElementById('printable-area');
        const body = document.body;

        const originalContent = btn.innerHTML;
        btn.innerText = "Generando...";
        btn.disabled = true;

        try {
            let cleanStorageUrl = storageUrl.endsWith('/') ? storageUrl : storageUrl + '/';
        
            // --- LOGICA DE LOGOS: EQUIPO Y CLIENTE ---
            
            // 1. Logo del Equipo (Para marca de agua)
            let teamLogoBase64 = null;
            if (team.image_path) {
                let fullLogoUrl = team.image_path.startsWith('http') || team.image_path.startsWith('data') 
                    ? team.image_path 
                    : (team.image_path.startsWith('/') ? cleanStorageUrl + team.image_path.substring(1) : cleanStorageUrl + team.image_path);
                try {
                    const logoResponse = await fetch(fullLogoUrl);
                    if (logoResponse.ok) {
                        const blob = await logoResponse.blob();
                        teamLogoBase64 = await new Promise(resolve => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.readAsDataURL(blob);
                        });
                    }
                } catch (e) { console.warn("Error cargando logo equipo:", e); }
            }

            // 2. Logo del Cliente (Para la esquina superior derecha)
            let clientLogoBase64 = null;
            const clientLogoPath = team.tournament?.client?.logo_path;
            
            if (clientLogoPath) {
                let fullClientUrl = clientLogoPath.startsWith('http') || clientLogoPath.startsWith('data') 
                    ? clientLogoPath 
                    : (clientLogoPath.startsWith('/') ? cleanStorageUrl + clientLogoPath.substring(1) : cleanStorageUrl + clientLogoPath);
                try {
                    const clientLogoResponse = await fetch(fullClientUrl);
                    if (clientLogoResponse.ok) {
                        const blob = await clientLogoResponse.blob();
                        clientLogoBase64 = await new Promise(resolve => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.readAsDataURL(blob);
                        });
                    }
                } catch (e) { console.warn("Error cargando logo cliente:", e); }
            }

            const finalClientLogo = clientLogoBase64 ? clientLogoBase64 : "{{ asset('img/logo_generico.png') }}";


            // --- FIN LOGICA LOGOS ---

            // Obtener Jugadores
            const response = await fetch(`/teams/${team.id}/players/json`);
            if (!response.ok) throw new Error('Error al obtener jugadores');
            let players = await response.json();

            if (players.length === 0) {
                alert('Este equipo no tiene jugadores registrados para imprimir.');
                btn.innerHTML = originalContent;
                btn.disabled = false;
                return;
            }

            // Conversión de imágenes de jugadores
            const playersWithImages = await Promise.all(players.map(async (player) => {
                let base64Img = null;
                let imagePath = player.image_path;

                if (imagePath) {
                    let fullUrl = imagePath.startsWith('http') || imagePath.startsWith('data') ? imagePath : (imagePath.startsWith('/') ? cleanStorageUrl + imagePath.substring(1) : cleanStorageUrl + imagePath);
                    try {
                        const imgResponse = await fetch(fullUrl);
                        if (imgResponse.ok) {
                            const blob = await imgResponse.blob();
                            base64Img = await new Promise((resolve, reject) => {
                                const reader = new FileReader();
                                reader.onloadend = () => resolve(reader.result);
                                reader.onerror = reject;
                                reader.readAsDataURL(blob);
                            });
                        }
                    } catch (e) { console.warn("Error imagen jugador:", player.name); }
                }
                return { ...player, base64Img };
            }));

            // --- 2. Construcción del HTML (DISEÑO FRENTE + DORSO) ---
            const primaryColor = '#ea580c'; 
            const textColor = '#ffffff';      
            const grayTextColor = '#6b7280';  

            const tournamentName = team.tournament ? team.tournament.name : (team.tournament_name || 'TORNEO');
            const category = team.category || '-';
            const strength = team.strength || '-';
            
            let htmlContent = `
                <div style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; width: 100%; max-width: 210mm; margin: 0 auto; padding: 10px; text-align: center; background-color: #fff;">
                    
                    <!-- Header Hoja -->
                    <div style="background-color: #fff; padding: 8px 15px; margin-bottom: 15px; border-bottom: 2px solid ${primaryColor}; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h1 style="margin: 0; font-size: 18px; color: ${primaryColor}; text-transform: uppercase; font-weight: 800;">${team.name}</h1>
                        <span style="font-size: 12px; color: #6b7280; font-weight: 600;">LISTA OFICIAL DE JUGADORES</span>
                    </div>
                    
                    <!-- Tabla de Tarjetas -->
                    <table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-collapse: separate;">
                        <tbody>
            `;

            for (let i = 0; i < playersWithImages.length; i++) {
                const player = playersWithImages[i];

                const statusColor = (player.status === 'active') ? '#059669' : ((player.status === 'suspended') ? '#d97706' : '#dc2626');
                const accentColor = '#ea580c'; 
                
                // Datos
                const tournamentName = team.tournament ? team.tournament.name : (team.tournament_name || 'TORNEO');
                const category = team.category || '-';
                const strength = team.strength || '-';

                // Foto
                let imgHtml = '';
                if (player.base64Img) {
                    imgHtml = `<img src="${player.base64Img}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">`;
                } else {
                    imgHtml = `<div style="width: 80px; height: 80px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-weight: 800; color: #9ca3af; font-size: 30px;">${player.name.charAt(0)}</div>`;
                }

                // Marca de agua del equipo
                let watermarkHtml = '';
                if (teamLogoBase64) {
                    watermarkHtml = `<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 85%; height: 85%; background-image: url('${teamLogoBase64}'); background-size: contain; background-repeat: no-repeat; background-position: center; opacity: 0.06; z-index: 0; pointer-events: none;"></div>`;
                }

                // HTML DEL FRENTE
                const frontHtml = `
                    <div style="width: 300px; height: 180px; background: #fff; border-radius: 8px; position: relative; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; page-break-inside: avoid;">
                        
                        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background: ${accentColor};"></div>
                        ${watermarkHtml}
                        
                        <!-- LOGO DEL CLIENTE -->
                        <img src="${finalClientLogo}" alt="Cliente" style="position: absolute; top: 8px; right: 8px; width: 40px; height: auto; opacity: 0.9; z-index: 10; object-fit: contain;">

                        <div style="padding: 20px 15px 15px 15px; text-align: center; position: relative; z-index: 2;">
                            
                            <!-- Foto -->
                            <div style="margin-bottom: 6px;">${imgHtml}</div>
                            
                            <!-- NOMBRE DEL EQUIPO -->
                            <div style="font-size: 10px; font-weight: 800; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0;">
                                ${team.name}
                            </div>
                            
                            <!-- Nombre del Jugador -->
                            <h3 style="margin: 0 0 2px 0; font-size: 16px; font-weight: 700; color: #1f2937; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.1;">${player.name}</h3>
                            
                            <!-- Posición -->
                            <div style="font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; margin-bottom: 6px; line-height: 1;">
                                ${player.position || '-'}
                            </div>
                            
                            <!-- Línea de Datos -->
                            <div style="display: flex; justify-content: center; align-items: center; gap: 4px; font-size: 10px; color: #6b7280; margin-bottom: 8px;">
                                <span style="font-weight: 700; color: #374151; font-size: 11px;">#${player.number || '--'}</span>
                                <span style="color: #d1d5db;">|</span>
                                <span style="font-weight: 600; color: #1f2937;">${tournamentName}</span>
                                <span style="color: #d1d5db;">|</span>
                                <span style="font-weight: 600; color: #1f2937;">${category}</span>
                                <span style="color: #d1d5db;">|</span>
                                <span style="font-weight: 600; color: #1f2937;">${strength}</span>
                            </div>

                            <!-- Footer -->
                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #e5e7eb; font-size: 10px; color: #9ca3af; text-transform: uppercase;">
                                ${team.name}
                            </div>
                        </div>

                        <div style="position: absolute; bottom: 12px; left: 12px; width: 10px; height: 10px; border-radius: 50%; background: ${statusColor}; box-shadow: 0 0 0 2px #fff; z-index: 10;"></div>
                    </div>
                `;

                // HTML DEL DORSO (AJUSTADO: ETIQUETAS EN LÍNEA)
                const backHtml = `
                    <div style="width: 300px; height: 180px; background: #fff; border-radius: 8px; position: relative; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; padding: 12px 15px 15px 15px; font-size: 10px; display: flex; flex-direction: column; justify-content: flex-start;">
                        
                        <!-- Decoración lateral -->
                        <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 4px; background: #f3f4f6;"></div>

                        <!-- SECCIÓN SUPERIOR: DATOS PERSONALES -->
                        <div style="flex: 1; border-bottom: 1px dashed #e5e7eb; padding-bottom: 4px; margin-bottom: 6px;">
                            <h4 style="margin: 0 0 3px 0; font-size: 9px; color: #ea580c; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Datos Personales</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3px 8px;">
                                <div>
                                    <span style="color: #9ca3af; font-size: 8px;">RFC:</span>
                                    <span style="color: #1f2937; font-weight: 600; font-size: 9px; margin-left: 2px;">${player.rfc || '-'}</span>
                                </div>
                                <div>
                                    <span style="color: #9ca3af; font-size: 8px;">CURP:</span>
                                    <span style="color: #1f2937; font-weight: 600; font-size: 9px; margin-left: 2px;">${player.curp || '-'}</span>
                                </div>
                                <div style="grid-column: span 2;">
                                    <span style="color: #9ca3af; font-size: 8px;">Tipo de Sangre:</span>
                                    <span style="color: #dc2626; font-weight: 700; font-size: 10px; margin-left: 2px;">${player.blood_type || '-'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN INFERIOR: EMERGENCIA -->
                        <div style="flex: 1; display: flex; flex-direction: column;">
                            <h4 style="margin: 0 0 4px 0; font-size: 9px; color: #ea580c; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Contacto de Emergencia</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px 8px; margin-bottom: 5px;">
                                <div style="grid-column: span 2;">
                                    <span style="color: #9ca3af; font-size: 8px;">Nombre:</span>
                                    <span style="color: #1f2937; font-weight: 600; margin-left: 2px;">${player.emergency_contact_name || '-'}</span>
                                </div>
                                <div>
                                    <span style="color: #9ca3af; font-size: 8px;">Parentesco:</span>
                                    <span style="color: #1f2937; font-weight: 500; margin-left: 2px;">${player.emergency_contact_relationship || '-'}</span>
                                </div>
                                <div>
                                    <span style="color: #9ca3af; font-size: 8px;">Teléfono:</span>
                                    <span style="color: #1f2937; font-weight: 600; margin-left: 2px;">${player.emergency_contact_phone || '-'}</span>
                                </div>
                                
                                <!-- Dirección -->
                                <div style="grid-column: span 2; margin-bottom: 12px; padding-bottom: 2px;">
                                    <span style="color: #9ca3af; font-size: 8px; display: block;">Dirección:</span>
                                    <span style="color: #374151; font-weight: 400; font-size: 8px; line-height: 1.2;">${player.emergency_contact_address || '-'}</span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                `;

                // LÍNEA PUNTEADA
                const foldLineHtml = `
                    <div style="width: 1px; height: 180px; border-left: 1px dashed #cbd5e1;"></div>
                `;

                htmlContent += `
                    <tr>
                        <td style="padding: 0;">
                            <div style="display: inline-flex; align-items: center; gap: 0; page-break-inside: avoid;">
                                ${frontHtml}
                                ${foldLineHtml}
                                ${backHtml}
                            </div>
                        </td>
                    </tr>
                `;
            }

            htmlContent += `</tbody></table></div>`;
            
            // --- 3. PREPARAR IMPRESIÓN ---
            printArea.innerHTML = htmlContent;
            document.body.appendChild(printArea);
            
            // Activar blur y preview
            body.classList.add('printing-mode');
            printArea.classList.add('preview-active');

            await new Promise(r => setTimeout(r, 500));

            // --- 4. MANEJO DE EVENTO DE IMPRESIÓN ---
            
            const cleanup = () => {
                body.classList.remove('printing-mode');
                printArea.classList.remove('preview-active');
                printArea.innerHTML = '';
                window.onafterprint = null;
                btn.innerHTML = originalContent;
                btn.disabled = false;
            };

            window.onafterprint = cleanup;
            window.print();

            // Fallback
            setTimeout(() => {
                if (window.onafterprint !== null) {
                    cleanup();
                }
            }, 1000);

        } catch (error) {
            console.error('Error:', error);
            alert('Hubo un error al imprimir. Asegúrate de cargar el cliente en el controlador.');
            body.classList.remove('printing-mode');
            printArea.classList.remove('preview-active');
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    }
    // --- FUNCIONES PARA MODAL DE ERROR ---
    function showErrorModal(message) {
        const textElement = document.getElementById('errorText');
        textElement.innerText = message;
        document.getElementById('errorModal').classList.remove('hidden');
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
    }
                // ========================
    // LÓGICA COACH RÁPIDO
    // ========================

    function openQuickCoachModal() {
        // Limpiamos el formulario
        document.getElementById('quickCoachForm').reset();
        // Mostramos el modal
        document.getElementById('quickCoachModal').classList.remove('hidden');
    }

    function closeQuickCoachModal() {
        document.getElementById('quickCoachModal').classList.add('hidden');
    }

    async function submitQuickCoachForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerText;

        // UI Feedback
        submitBtn.innerText = 'Guardando...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75');

        try {
            const response = await fetch('{{ route("users.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json', // Le decimos al servidor que esperamos JSON
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            console.log("Respuesta del servidor (Status):", response.status);

            // Intentamos obtener el JSON
            let data;
            try {
                data = await response.json();
            } catch (e) {
                // Si falla el JSON, probablemente sea un error 500 (HTML) o redirección
                console.error("Error parseando JSON. Respuesta cruda:", await response.text());
                throw new Error("El servidor devolvió un error inesperado (Posible Error 500). Revisa la consola.");
            }

            if (response.ok && data.success) {
                // ÉXITO
                closeQuickCoachModal();
                const coachSelect = document.getElementById('modal_coach_id');
                const newOption = document.createElement('option');
                newOption.value = data.user.id;
                newOption.text = data.user.name;
                newOption.selected = true;
                coachSelect.appendChild(newOption);
                
                alert('Entrenador creado y asignado: ' + data.user.name);
                
            } else {
                // ERROR DE VALIDACIÓN (422) u otro error controlado
                console.error("Datos de error:", data);
                let errorMsg = 'Error al crear el coach.\n';
                
                if (data.message) {
                    errorMsg += data.message + "\n";
                }

                if (data.errors) {
                    errorMsg += "Detalles:\n";
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMsg += `- ${field}: ${messages.join(', ')}\n`;
                    }
                }
                alert(errorMsg);
            }
        } catch (error) {
            console.error("Error en catch:", error);
            alert('Error de conexión o del servidor: ' + error.message);
        } finally {
            submitBtn.innerText = originalBtnText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75');
        }
    }
    async function printSingleCredential(player) {
        // Usamos la variable global del equipo que ya se cargó cuando se abrió el modal
        const team = currentTeamForPlayer; 
        
        const printArea = document.getElementById('printable-area');
        const body = document.body;

        // ... el resto del código sigue igual ...

        // Estado inicial del botón si tuvieras uno, aquí no es necesario pasar event.currentTarget
        body.style.cursor = "wait";

        try {
            let cleanStorageUrl = storageUrl.endsWith('/') ? storageUrl : storageUrl + '/';
        
            // --- LOGICA DE LOGOS: EQUIPO Y CLIENTE ---
            // 1. Logo del Equipo
            let teamLogoBase64 = null;
            if (team.image_path) {
                let fullLogoUrl = team.image_path.startsWith('http') || team.image_path.startsWith('data') 
                    ? team.image_path 
                    : (team.image_path.startsWith('/') ? cleanStorageUrl + team.image_path.substring(1) : cleanStorageUrl + team.image_path);
                try {
                    const logoResponse = await fetch(fullLogoUrl);
                    if (logoResponse.ok) {
                        const blob = await logoResponse.blob();
                        teamLogoBase64 = await new Promise(resolve => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.readAsDataURL(blob);
                        });
                    }
                } catch (e) { console.warn("Error cargando logo equipo:", e); }
            }

            // 2. Logo del Cliente
            let clientLogoBase64 = null;
            const clientLogoPath = team.tournament?.client?.logo_path;
            if (clientLogoPath) {
                let fullClientUrl = clientLogoPath.startsWith('http') || clientLogoPath.startsWith('data') 
                    ? clientLogoPath 
                    : (clientLogoPath.startsWith('/') ? cleanStorageUrl + clientLogoPath.substring(1) : cleanStorageUrl + clientLogoPath);
                try {
                    const clientLogoResponse = await fetch(fullClientUrl);
                    if (clientLogoResponse.ok) {
                        const blob = await clientLogoResponse.blob();
                        clientLogoBase64 = await new Promise(resolve => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.readAsDataURL(blob);
                        });
                    }
                } catch (e) { console.warn("Error cargando logo cliente:", e); }
            }

            const finalClientLogo = clientLogoBase64 ? clientLogoBase64 : "{{ asset('img/logo_generico.png') }}";

            // --- LOGICA FOTO JUGADOR ---
            let playerImgBase64 = null;
            if (player.image_path) {
                let fullUrl = player.image_path.startsWith('http') || player.image_path.startsWith('data') ? player.image_path : (player.image_path.startsWith('/') ? cleanStorageUrl + player.image_path.substring(1) : cleanStorageUrl + player.image_path);
                try {
                    const imgResponse = await fetch(fullUrl);
                    if (imgResponse.ok) {
                        const blob = await imgResponse.blob();
                        playerImgBase64 = await new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.onerror = reject;
                            reader.readAsDataURL(blob);
                        });
                    }
                } catch (e) { console.warn("Error imagen jugador:", player.name); }
            }

            // --- GENERACION HTML ---
            const primaryColor = '#ea580c'; 
            const statusColor = (player.status === 'active') ? '#059669' : ((player.status === 'suspended') ? '#d97706' : '#dc2626');
            const accentColor = '#ea580c'; 
            
            const tournamentName = team.tournament ? team.tournament.name : (team.tournament_name || 'TORNEO');
            const category = team.category || '-';
            const strength = team.strength || '-';

            let imgHtml = '';
            if (playerImgBase64) {
                imgHtml = `<img src="${playerImgBase64}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">`;
            } else {
                imgHtml = `<div style="width: 80px; height: 80px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-weight: 800; color: #9ca3af; font-size: 30px;">${player.name.charAt(0)}</div>`;
            }

            let watermarkHtml = '';
            if (teamLogoBase64) {
                watermarkHtml = `<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 85%; height: 85%; background-image: url('${teamLogoBase64}'); background-size: contain; background-repeat: no-repeat; background-position: center; opacity: 0.06; z-index: 0; pointer-events: none;"></div>`;
            }

            // FRENTE
            const frontHtml = `
                <div style="width: 300px; height: 180px; background: #fff; border-radius: 8px; position: relative; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; page-break-inside: avoid;">
                    
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background: ${accentColor};"></div>
                    ${watermarkHtml}
                    <img src="${finalClientLogo}" alt="Cliente" style="position: absolute; top: 8px; right: 8px; width: 40px; height: auto; opacity: 0.9; z-index: 10; object-fit: contain;">

                    <div style="padding: 20px 15px 15px 15px; text-align: center; position: relative; z-index: 2;">
                        <div style="margin-bottom: 6px;">${imgHtml}</div>
                        
                        <div style="font-size: 10px; font-weight: 800; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0;">
                            ${team.name}
                        </div>
                        
                        <h3 style="margin: 0 0 2px 0; font-size: 16px; font-weight: 700; color: #1f2937; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.1;">${player.name}</h3>
                        
                        <div style="font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; margin-bottom: 6px; line-height: 1;">
                            ${player.position || '-'}
                        </div>
                        
                        <div style="display: flex; justify-content: center; align-items: center; gap: 4px; font-size: 10px; color: #6b7280; margin-bottom: 8px;">
                            <span style="font-weight: 700; color: #374151; font-size: 11px;">#${player.number || '--'}</span>
                            <span style="color: #d1d5db;">|</span>
                            <span style="font-weight: 600; color: #1f2937;">${tournamentName}</span>
                            <span style="color: #d1d5db;">|</span>
                            <span style="font-weight: 600; color: #1f2937;">${category}</span>
                            <span style="color: #d1d5db;">|</span>
                            <span style="font-weight: 600; color: #1f2937;">${strength}</span>
                        </div>

                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #e5e7eb; font-size: 10px; color: #9ca3af; text-transform: uppercase;">
                            ${team.name}
                        </div>
                    </div>

                    <div style="position: absolute; bottom: 12px; left: 12px; width: 10px; height: 10px; border-radius: 50%; background: ${statusColor}; box-shadow: 0 0 0 2px #fff; z-index: 10;"></div>
                </div>
            `;

            // DORSO
            const backHtml = `
                <div style="width: 300px; height: 180px; background: #fff; border-radius: 8px; position: relative; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; padding: 12px 15px 15px 15px; font-size: 10px; display: flex; flex-direction: column; justify-content: flex-start;">
                    
                    <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 4px; background: #f3f4f6;"></div>

                    <!-- DATOS PERSONALES -->
                    <div style="flex: 1; border-bottom: 1px dashed #e5e7eb; padding-bottom: 4px; margin-bottom: 6px;">
                        <h4 style="margin: 0 0 3px 0; font-size: 9px; color: #ea580c; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Datos Personales</h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3px 8px;">
                            <div>
                                <span style="color: #9ca3af; font-size: 8px;">RFC:</span>
                                <span style="color: #1f2937; font-weight: 600; font-size: 9px; margin-left: 2px;">${player.rfc || '-'}</span>
                            </div>
                            <div>
                                <span style="color: #9ca3af; font-size: 8px;">CURP:</span>
                                <span style="color: #1f2937; font-weight: 600; font-size: 9px; margin-left: 2px;">${player.curp || '-'}</span>
                            </div>
                            <div style="grid-column: span 2;">
                                <span style="color: #9ca3af; font-size: 8px;">Tipo de Sangre:</span>
                                <span style="color: #dc2626; font-weight: 700; font-size: 10px; margin-left: 2px;">${player.blood_type || '-'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- EMERGENCIA -->
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <h4 style="margin: 0 0 4px 0; font-size: 9px; color: #ea580c; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Contacto de Emergencia</h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px 8px; margin-bottom: 5px;">
                            <div style="grid-column: span 2;">
                                <span style="color: #9ca3af; font-size: 8px;">Nombre:</span>
                                <span style="color: #1f2937; font-weight: 600; margin-left: 2px;">${player.emergency_contact_name || '-'}</span>
                            </div>
                            <div>
                                <span style="color: #9ca3af; font-size: 8px;">Parentesco:</span>
                                <span style="color: #1f2937; font-weight: 500; margin-left: 2px;">${player.emergency_contact_relationship || '-'}</span>
                            </div>
                            <div>
                                <span style="color: #9ca3af; font-size: 8px;">Teléfono:</span>
                                <span style="color: #1f2937; font-weight: 600; margin-left: 2px;">${player.emergency_contact_phone || '-'}</span>
                            </div>
                            <div style="grid-column: span 2; margin-bottom: 12px; padding-bottom: 2px;">
                                <span style="color: #9ca3af; font-size: 8px; display: block;">Dirección:</span>
                                <span style="color: #374151; font-weight: 400; font-size: 8px; line-height: 1.2;">${player.emergency_contact_address || '-'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const foldLineHtml = `
                <div style="width: 1px; height: 180px; border-left: 1px dashed #cbd5e1;"></div>
            `;

            // HTML Final (Centrado en hoja)
            let htmlContent = `
                <div style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; width: 100%; max-width: 210mm; margin: 0 auto; padding: 10px; text-align: center; background-color: #fff;">
                    <div style="display: inline-flex; align-items: center; gap: 0; page-break-inside: avoid;">
                        ${frontHtml}
                        ${foldLineHtml}
                        ${backHtml}
                    </div>
                </div>
            `;
            
            printArea.innerHTML = htmlContent;
            document.body.appendChild(printArea);
            
            body.classList.add('printing-mode');
            printArea.classList.add('preview-active');

            await new Promise(r => setTimeout(r, 500));

            const cleanup = () => {
                body.classList.remove('printing-mode');
                printArea.classList.remove('preview-active');
                printArea.innerHTML = '';
                window.onafterprint = null;
                body.style.cursor = "default";
            };

            window.onafterprint = cleanup;
            window.print();

            setTimeout(() => {
                if (window.onafterprint !== null) {
                    cleanup();
                }
            }, 1000);

        } catch (error) {
            console.error('Error:', error);
            alert('Hubo un error al imprimir.');
            body.classList.remove('printing-mode');
            printArea.classList.remove('preview-active');
            body.style.cursor = "default";
        } finally {
            body.style.cursor = "default";
        }
    }
    // ========================
    // LÓGICA DE HORARIOS EQUIPO (NUEVO)
    // ========================

    let currentTeamScheduleRowIndex = 0;

    async function openTeamScheduleModal(team) {
        document.getElementById('team_schedule_id').value = team.id;
        const container = document.getElementById('teamScheduleRows');
        container.innerHTML = ''; 
        currentTeamScheduleRowIndex = 0;

        try {
            const response = await fetch(`{{ route('teams.getSchedules', ':id') }}`.replace(':id', team.id), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const schedules = await response.json();

            if (schedules.length > 0) {
                schedules.forEach(s => {
                    addTeamScheduleRow({
                        day: s.day_of_week,
                        start: s.start_time.substring(0, 5),
                        end: s.end_time.substring(0, 5)
                    });
                });
            } else {
                addTeamScheduleRow();
            }

        } catch (error) {
            console.error('Error cargando horarios:', error);
            addTeamScheduleRow();
        }

        document.getElementById('teamScheduleModal').classList.remove('hidden');
    }

    function closeTeamScheduleModal() {
        document.getElementById('teamScheduleModal').classList.add('hidden');
    }

    function addTeamScheduleRow(data = null) {
        const container = document.getElementById('teamScheduleRows');
        const index = currentTeamScheduleRowIndex++;
        
        const row = document.createElement('div');
        row.className = 'schedule-row flex gap-3 items-center';
        
        const daysOfWeek = [
            { value: 1, label: 'Lunes' },
            { value: 2, label: 'Martes' },
            { value: 3, label: 'Miércoles' },
            { value: 4, label: 'Jueves' },
            { value: 5, label: 'Viernes' },
            { value: 6, label: 'Sábado' },
            { value: 0, label: 'Domingo' },
        ];

        let dayOptions = daysOfWeek.map(day => 
            `<option value="${day.value}" ${data && data.day == day.value ? 'selected' : ''}>${day.label}</option>`
        ).join('');

        row.innerHTML = `
            <div class="w-1/3">
                <!-- CAMBIO AQUÍ: focus:border-orange-500 focus:ring-orange-500 -->
                <select name="schedules[${index}][day]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                    ${dayOptions}
                </select>
            </div>
            <div class="w-1/4">
                <!-- CAMBIO AQUÍ: focus:border-orange-500 focus:ring-orange-500 -->
                <input type="time" name="schedules[${index}][start_time]" value="${data ? data.start : ''}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" required>
            </div>
            <div class="w-1/4">
                <!-- CAMBIO AQUÍ: focus:border-orange-500 focus:ring-orange-500 -->
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

    async function submitTeamSchedules(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const saveButton = document.getElementById('saveTeamScheduleButton');
        const teamId = document.getElementById('team_schedule_id').value;
        
        const schedules = [];
        const keys = Array.from(formData.keys()).filter(k => k.startsWith('schedules['));
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
            const response = await fetch(`{{ route('teams.schedules', ':id') }}`.replace(':id', teamId), {
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
                closeTeamScheduleModal();
            } else {
                alert('Error: ' + (data.message || 'Ocurrió un error.'));
            }
        } catch (error) {
            console.error('Error de red:', error);
            alert('Ocurrió un error de red.');
        } finally {
            saveButton.disabled = false;
            saveButton.innerText = 'Guardar Horarios';
        }
    }
    // Función para cambiar pestañas en el modal de jugador
    function switchTab(tabName) {
        // Ocultar todos los contenidos
        document.getElementById('tab-personal').classList.add('hidden');
        document.getElementById('tab-emergency').classList.add('hidden');

        // Resetear estilos de botones
        document.getElementById('tab-btn-personal').classList.remove('text-orange-600', 'border-orange-500');
        document.getElementById('tab-btn-personal').classList.add('text-gray-500', 'border-transparent');
        
        document.getElementById('tab-btn-emergency').classList.remove('text-orange-600', 'border-orange-500');
        document.getElementById('tab-btn-emergency').classList.add('text-gray-500', 'border-transparent');

        // Mostrar el seleccionado
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Activar estilo del botón
        document.getElementById('tab-btn-' + tabName).classList.remove('text-gray-500', 'border-transparent');
        document.getElementById('tab-btn-' + tabName).classList.add('text-orange-600', 'border-orange-500');
    }
    // ========================
    // LÓGICA DE FUERZAS DINÁMICAS
    // ========================

    function openAddStrengthModal() {
        document.getElementById('addStrengthForm').reset();
        document.getElementById('addStrengthModal').classList.remove('hidden');
    }

    function closeAddStrengthModal() {
        document.getElementById('addStrengthModal').classList.add('hidden');
    }

    async function submitStrengthForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;

        submitBtn.innerText = 'Guardando...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("teams.strengths.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // 1. Cerrar modal
                closeAddStrengthModal();

                // 2. Crear opción HTML
                const newOptionText = data.strength.name;
                
                // 3. Función auxiliar para agregar opción a un select
                const addOptionToSelect = (selectId) => {
                    const select = document.getElementById(selectId);
                    const option = document.createElement('option');
                    option.value = newOptionText;
                    option.text = newOptionText;
                    option.selected = true; // Seleccionar la nueva opción
                    select.appendChild(option);
                };

                // 4. Agregar al Select del Modal de Equipo
                addOptionToSelect('modal_strength');

                // 5. Agregar al Select del Filtro (si existe en la vista)
                // Nota: Buscamos el select dentro del formulario de búsqueda
                const filterSelect = document.querySelector('select[name="strength"]');
                if (filterSelect) {
                    const filterOption = document.createElement('option');
                    filterOption.value = newOptionText;
                    filterOption.text = newOptionText;
                    filterSelect.appendChild(filterOption);
                }

                alert('Fuerza agregada exitosamente: ' + newOptionText);

            } else {
                alert('Error: ' + (data.message || 'No se pudo guardar la fuerza.'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error de conexión.');
        } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    }

    // ==========================================
    // LÓGICA DE JUGADORES (VERSIÓN ROBUSTA POR NAME)
    // ==========================================

    // 1. Función puente
    function openEditPlayerModalFromList(playerId) {
        const player = currentTeamPlayers.find(p => p.id == playerId);
        if (!player) {
            alert('Error: Jugador no encontrado en la lista.');
            return;
        }
        closePlayersListModal();
        openEditPlayerModal(player);
    }

    // 2. Función para llenar el modal (CORRECCIÓN PARA EL CAMPO NOMBRE)
    function openEditPlayerModal(player) {
        const form = document.getElementById('playerForm');
        if(!form) return;
        
        form.reset();

        document.getElementById('modalTitle').innerText = 'Editar Jugador: ' + player.name;
        document.getElementById('form_method').value = 'PUT'; 
        form.action = '{{ route("players.update", ":id") }}'.replace(':id', player.id);

        const saveButton = document.getElementById('savePlayerButton');
        saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';

        // --- LLENADO INTELIGENTE ---
        const setField = (fieldName, value) => {
            
            // --- CORRECCIÓN ESPECÍFICA PARA EL NOMBRE ---
            // Como 'name' es un atributo genérico y se repite (Buscador, Equipo, Jugador),
            // forzamos la búsqueda por ID para el jugador.
            if (fieldName === 'name') {
                const playerInput = document.getElementById('modal_player_name');
                if (playerInput) {
                    playerInput.value = value || '';
                    playerInput.dispatchEvent(new Event('input', { bubbles: true }));
                    playerInput.dispatchEvent(new Event('change', { bubbles: true }));
                    return; // Terminamos aquí, no seguimos buscando
                }
            }
            // ----------------------------------------------

            // Para el resto de campos (que tienen nombres únicos), buscamos normalmente
            const els = document.querySelectorAll(`[name="${fieldName}"]`);
            
            if (els.length === 0) {
                console.error(`No encontrado: [name="${fieldName}"]`);
                return;
            }

            els.forEach(el => {
                // Si es el input real
                if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
                    el.value = value || '';
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                } 
                // Si es un wrapper, buscar el input adentro
                else {
                    const input = el.querySelector('input, select, textarea');
                    if (input) {
                        input.value = value || '';
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        };

        // Asignar valores
        setField('name', player.name);
        setField('rfc', player.rfc);
        setField('curp', player.curp);
        setField('number', player.number);
        setField('position', player.position);
        setField('gender', player.gender);
        setField('blood_type', player.blood_type);
        setField('team_id', player.team_id);
        setField('status', player.status || 'active');

        setField('emergency_contact_name', player.emergency_contact_name);
        setField('emergency_contact_relationship', player.emergency_contact_relationship);
        setField('emergency_contact_phone', player.emergency_contact_phone);
        setField('emergency_contact_address', player.emergency_contact_address);

        switchTab('personal'); 
        document.getElementById('playerModal').classList.remove('hidden');
    }

    // 3. Función de envío (CORRECCIÓN FINAL DEL SCOPE)
    async function submitPlayerForm(event) {
        event.preventDefault();
        const form = document.getElementById('playerForm'); // Obtenemos el elemento FORM
        
        // --- 1. OBTENER TOKEN ---
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            alert('Error crítico: No se encontró el token CSRF en la página.');
            return;
        }
        const token = csrfTokenMeta.getAttribute('content');
        
        // --- 2. CREAR FORMDATA Y AGREGAR TOKEN ---
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('_method', document.getElementById('form_method').value);

        // --- 3. FUNCIÓN PARA AGREGAR CAMPOS ---
        const addField = (fieldName) => {
            // CAMBIO CLAVE AQUÍ: Usamos 'form.querySelector' en lugar de 'document.querySelector'
            // Esto restringe la búsqueda únicamente a los inputs dentro de #playerForm
            // Evitando así que tome el input del modal de equipos.
            const el = form.querySelector(`[name="${fieldName}"]`);
            
            if (!el) {
                console.warn(`Campo no encontrado dentro de #playerForm: [name="${fieldName}"]`);
                return;
            }

            if (el.type === 'file' && el.files.length > 0) {
                formData.append(fieldName, el.files[0]);
            } else {
                const val = el.value || '';
                formData.append(fieldName, val);
                console.log(`Enviando [${fieldName}]: ${val}`); // Debug para verificar
            }
        };

        // --- 4. AGREGAR TODOS LOS CAMPOS ---
        // Datos Personales
        addField('name');
        addField('rfc');
        addField('curp');
        addField('number');
        addField('position');
        addField('gender');
        addField('blood_type');
        addField('team_id');
        addField('status');
        addField('image'); 

        // Datos de Emergencia
        addField('emergency_contact_name');
        addField('emergency_contact_relationship');
        addField('emergency_contact_phone');
        addField('emergency_contact_address');

        // --- 5. ENVÍO ---
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin', 
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                }
            });

            if (response.ok) {
                // Éxito
                closePlayerModal();
                if (currentTeamForPlayer) {
                    await showPlayersListModal(currentTeamForPlayer);
                }
            } else {
                // Error
                let errorDetails = "Error desconocido";
                try {
                    const data = await response.json();
                    if (data.message) errorDetails = data.message;
                    if (data.errors) {
                        let errorList = [];
                        for (const [field, messages] of Object.entries(data.errors)) {
                            errorList.push(`${field}: ${messages.join(', ')}`);
                        }
                        errorDetails += "\n\n" + errorList.join("\n");
                    }
                } catch (e) {
                    errorDetails = await response.text();
                }
                console.error("Error:", response.status, errorDetails);
                alert(`Error ${response.status}: ${errorDetails}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado de conexión.');
        }
    }

    async function submitTeamForm(event) {
        event.preventDefault();
        const form = document.getElementById('teamForm');
        const formData = new FormData();

        // Función auxiliar
        const addField = (name) => {
            const el = form.querySelector(`[name="${name}"]`);
            if (el) {
                if (el.type === 'file' && el.files.length > 0) {
                    formData.append(name, el.files[0]);
                } else {
                    formData.append(name, el.value);
                }
            }
        };

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', document.getElementById('form_method').value);

        // Agregar campos
        addField('name');
        addField('coach_id');
        addField('tournament_id');
        addField('status');
        addField('category');
        addField('strength');
        addField('image');

        const btn = document.getElementById('saveButton');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Guardando...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // --- AQUÍ ESTÁ EL CAMBIO IMPORTANTE ---
            if (!response.ok) {
                // Si la respuesta no es OK (ej. Error 500), leemos el texto HTML del error
                const errorText = await response.text();
                console.error("ERROR DEL SERVIDOR (HTML):", errorText);
                
                // Mostramos las primeras 500 letras del error en una alerta
                alert("Error " + response.status + " del servidor:\n\n" + errorText.substring(0, 500) + "...");
                return; // Detenemos la ejecución aquí
            }
            // ---------------------------------------

            // Si todo está bien, parseamos JSON
            const data = await response.json();

            if (response.ok) {
                closeTeamModal();
                window.location.reload();
            }

        } catch (error) {
            console.error('Error de red o JS:', error);
            alert('Error de conexión: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }
</script>