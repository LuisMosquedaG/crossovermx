<x-app-layout>

    <div class="py-12">
        <div class="w-[96%] md:w-[90%] mx-auto mb-[10vh]">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Contenedor Flex (Botón Crear + Buscador) -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        
                        <!-- Botón Crear Partido -->
                        <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                            Crear Nuevo Partido
                        </button>

                        <!-- Formulario de Búsqueda -->
                        <form action="{{ route('games.index') }}" method="GET" class="relative w-full md:flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Buscar por equipo, cancha o fecha...">
                        </form>
                    </div>

                    @if (session('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                     <!-- Tabla de Partidos -->
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- 1. Acciones -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    
                                    <!-- 2. Imagen (Logo Local) -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Local</th>
                                    
                                    <!-- 3. Enfrentamiento -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Partido</th>

                                    <!-- 4. Imagen (Logo Visitante) -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Visitante</th>
                                    
                                    <!-- 5. Fecha -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>

                                    <!-- 6. Cancha -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cancha</th>
                                    
                                    <!-- 7. Estado -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($games as $game)
                                    <tr>
                                        <!-- 1. Acciones (Orden: Editar -> Lapiz Arbitro -> Play -> Stats -> Eliminar) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                
                                                <!-- 1. Editar Partido (Lápiz Índigo) -->
                                                <button onclick="openEditModal({{ $game->toJson() }})" class="text-indigo-600 hover:text-indigo-900" title="Editar Partido">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </button>

                                                <!-- 2. Lápiz: Asignar Árbitro -->
                                                <button onclick="openAssignRefereeModal({{ $game->id }}, '{{ $game->referee_id ?? '' }}')" class="text-yellow-600 transition-colors" title="Asignar Árbitro">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L18 14v4.75" />
                                                    </svg>
                                                </button>

                                                <!-- 3. Play / Iniciar (Lógica Condicional) -->
                                                @if($game->status === 'pending')
                                                    <!-- Si es pendiente, abre modal de jugadores -->
                                                    <button onclick="openPlayerSelectionModal({{ $game->id }})" class="text-orange-600 hover:text-orange-800" title="Iniciar Partido">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                                        </svg>
                                                    </button>
                                                @elseif($game->status === 'playing')
                                                    <!-- Si está jugando, va al Live -->
                                                    <a href="{{ route('games.live', $game->id) }}" class="text-orange-600 hover:text-orange-800" title="Operar Partido">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                <!-- 4. Stats: Ver Estadísticas (Solo si finalizado) -->
                                                @if($game->status === 'finished')
                                                    <a href="{{ route('games.stats', $game->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver Estadísticas">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                <!-- 5. Eliminar -->
                                                <form action="{{ route('games.destroy', $game) }}" method="POST" onsubmit="return confirm('¿Eliminar este partido?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <!-- 2. Imagen (Logo Local) -->
                                        <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                            @if($game->localTeam && $game->localTeam->image_path)
                                                <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" alt="{{ $game->localTeam->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                                                    {{ substr($game->localTeam->name ?? 'L', 0, 1) }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 3. Enfrentamiento -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-bold text-gray-900">
                                                <span class="text-blue-600">{{ $game->localTeam->name ?? 'Local' }}</span>
                                                <span class="text-gray-400 mx-1">vs</span>
                                                <span class="text-red-600">{{ $game->awayTeam->name ?? 'Visitante' }}</span>
                                            </div>
                                            @if($game->status !== 'pending')
                                                <div class="text-xs text-gray-500 font-mono mt-1">
                                                    {{ $game->local_team_score }} - {{ $game->away_team_score }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 4. Imagen (Logo Visitante) -->
                                        <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                            @if($game->awayTeam && $game->awayTeam->image_path)
                                                <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" alt="{{ $game->awayTeam->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xs font-bold">
                                                    {{ substr($game->awayTeam->name ?? 'V', 0, 1) }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- 5. Fecha -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ $game->date_time ? $game->date_time->format('d-m-Y H:i') : '-' }}
                                        </td>

                                        <!-- 6. Cancha -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ $game->court->name ?? '-' }}
                                        </td>

                                        <!-- 7. Estado -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($game->status === 'playing')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">En Juego</span>
                                            @elseif($game->status === 'finished')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">Finalizado</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay partidos independientes creados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $games->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

    <!-- MODAL 1: Crear/Editar Partido (ACTUALIZADO CON CONFIGURACIÓN) -->
    <!-- Cambiamos z-50 por z-[60] -->
    <div id="gameModal" class="fixed inset-0 z-[60] hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
    <!-- ... resto del contenido ... -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="gameForm" onsubmit="submitForm(event)">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">Crear Partido Manual</h3>
                                    
                                    <!-- Datos Básicos -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Equipo Local</label>
                                            <!-- CORRECCIÓN: Agregado focus:ring-orange-500 y focus:ring-offset-0 -->
                                            <select name="local_team_id" id="modal_local_team" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                                <option value="">Seleccionar</option>
                                                @foreach($teams as $team)
                                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Equipo Visitante</label>
                                            <!-- CORRECCIÓN: Agregado focus:ring-orange-500 y focus:ring-offset-0 -->
                                            <select name="away_team_id" id="modal_away_team" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                                <option value="">Seleccionar</option>
                                                @foreach($teams as $team)
                                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Cancha</label>
                                        <!-- CORRECCIÓN: Agregado focus:ring-orange-500 y focus:ring-offset-0 -->
                                        <select name="court_id" id="modal_court" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                            <option value="">Seleccionar</option>
                                            @foreach($courts as $court)
                                                <option value="{{ $court->id }}">{{ $court->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                                        <!-- CORRECCIÓN: Agregado focus:ring-orange-500 y focus:ring-offset-0 -->
                                        <input type="datetime-local" name="date_time" id="modal_date_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                    </div>

                                    <!-- NUEVO: Configuración de Reglas -->
                                    <div class="border-t border-gray-200 pt-4 mt-4">
                                        <h4 class="text-md font-bold text-gray-800 mb-3 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-orange-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                            </svg>
                                            Configuración del Partido
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500">Periodos</label>
                                                <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                <input type="number" name="periods" value="4" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500">Minutos/Periodo</label>
                                                <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                <input type="number" name="duration" value="10" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500">Tiempos Muertos</label>
                                                <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                <input type="number" name="timeouts" value="2" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 sm:text-sm border p-2" required>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-md">
                                            <label class="block text-xs font-medium text-gray-700 mb-2">Límite de Faltas (Descalificación)</label>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                <div>
                                                    <label class="text-[10px] text-gray-500">Personal</label>
                                                    <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                    <input type="number" name="fouls[personal]" value="5" class="w-full text-sm border p-1 rounded focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-gray-500">Técnica</label>
                                                    <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                    <input type="number" name="fouls[technical]" value="2" class="w-full text-sm border p-1 rounded focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-gray-500">Antidep.</label>
                                                    <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                    <input type="number" name="fouls[unsportsmanlike]" value="2" class="w-full text-sm border p-1 rounded focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-gray-500">Descalif.</label>
                                                    <!-- CORRECCIÓN: Agregado focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0 -->
                                                    <input type="number" name="fouls[disqualifying]" value="1" class="w-full text-sm border p-1 rounded focus:border-orange-500 focus:ring-orange-500 focus:ring-offset-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" id="saveButton" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700 sm:ml-3 sm:w-auto">Guardar Partido</button>
                            <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Asignar Árbitro (EXISTENTE EN TU ARCHIVO) -->
    <!-- Cambiamos z-50 por z-[60] -->
    <div id="assignRefereeModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
    <!-- ... resto del contenido ... -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="assignRefereeForm" onsubmit="submitAssignReferee(event)">
                        @csrf
                        <input type="hidden" name="game_id" id="referee_game_id">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Asignar Árbitro</h3>
                            <div class="mb-4">
                                <label for="referee_select" class="block text-sm font-medium text-gray-700">Seleccionar Árbitro:</label>
                                
                                <div class="flex mt-1">
                                    <select name="referee_id" id="referee_select" class="flex-1 block w-full rounded-l-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" required>
                                        <option value="">-- Seleccionar --</option>
                                    </select>
                                    
                                    <button type="button" onclick="openQuickRefereeModal()" 
                                        class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-md border border-l-0 border-orange-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                        title="Crear nuevo árbitro">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700 sm:ml-3 sm:w-auto">Asignar</button>
                            <button type="button" onclick="closeAssignRefereeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Crear Árbitro Rápido (EXISTENTE EN TU ARCHIVO) -->
    <div id="quickRefereeModal" class="fixed inset-0 z-[60] hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="quickRefereeForm" onsubmit="submitQuickRefereeForm(event)">
                        @csrf
                        <input type="hidden" name="role" value="Arbitro">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Registrar Nuevo Árbitro</h3>
                                
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="q_referee_name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                        <input type="text" id="q_referee_name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" type="text" name="name" required />
                                    </div>
                                    
                                    <div>
                                        <label for="q_referee_email" class="block text-sm font-medium text-gray-700">Usuario (Email)</label>
                                        <input type="text" id="q_referee_email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" type="text" name="email" required placeholder="Ej: arbitro.nuevo" />
                                        <p class="mt-1 text-xs text-gray-500">El dominio se agregará automáticamente según configuración.</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="q_referee_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                            <input type="password" id="q_referee_password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" name="password" required />
                                        </div>
                                        <div>
                                            <label for="q_referee_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar</label>
                                            <input type="password" id="q_referee_password_confirmation" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2" name="password_confirmation" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 sm:ml-3 sm:w-auto">
                                Guardar y Asignar
                            </button>
                            <button type="button" onclick="closeQuickRefereeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 4: Selección de Jugadores (COPIADO DE SCHEDULE) -->
    <div id="playerSelectionModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="playerSelectionForm" onsubmit="saveStartingPlayers(event)">
                        @csrf
                        <input type="hidden" name="game_id" id="playerSelection_game_id">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Seleccionar Jugadores Iniciales</h3>
                                    <p class="text-sm text-gray-500 mb-4">Selecciona entre 3 y 5 jugadores para cada equipo.</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <!-- EQUIPO LOCAL (CÓDIGO DEL MÓDULO GAMES) -->
                                            <div class="flex justify-between items-center mb-2">
                                                <h4 class="font-medium text-gray-900" id="localTeamName"></h4>
                                                <!-- Botón + Local -->
                                                <button onclick="openQuickPlayerModal('local')" type="button" class="text-orange-600 hover:text-orange-800 p-1 rounded-full hover:bg-orange-50 transition" title="Agregar Jugador">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div id="localTeamPlayers" class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3"></div>
                                            <div class="mt-2 text-sm text-gray-600">Jugadores seleccionados: <span id="localSelectedCount">0</span>/5</div>
                                        </div>
                                        <div>
                                            <!-- EQUIPO VISITANTE (CÓDIGO DEL MÓDULO GAMES) -->
                                            <div class="flex justify-between items-center mb-2">
                                                <h4 class="font-medium text-gray-900 mb-2" id="awayTeamName"></h4>
                                                <!-- Botón + Visitante -->
                                                <button onclick="openQuickPlayerModal('away')" type="button" class="text-orange-600 hover:text-orange-800 p-1 rounded-full hover:bg-orange-50 transition" title="Agregar Jugador">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div id="awayTeamPlayers" class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3"></div>
                                            <div class="mt-2 text-sm text-gray-600">Jugadores seleccionados: <span id="awaySelectedCount">0</span>/5</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">Guardar Selección</button>
                            <button type="button" onclick="closePlayerSelectionModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 5: Agregar Jugador Rápido (COPIADO DE SCHEDULE) -->
    <div id="quickPlayerModal" class="fixed inset-0 z-[60] hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-sm transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="quickPlayerForm" onsubmit="submitQuickPlayer(event)">
                        @csrf
                        <input type="hidden" name="team_id" id="qp_team_id">
                        <input type="hidden" name="side" id="qp_side">
                        <input type="hidden" name="status" value="active">
                        <input type="hidden" name="gender" id="qp_gender" value="">   
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Agregar Jugador</h3>
                                <p class="text-sm text-gray-500 mt-1">Registro rápido para partido en curso.</p>
                                
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="qp_name" class="block text-sm font-medium text-gray-700">Nombre del Jugador</label>
                                        <input type="text" name="name" id="qp_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>
                                    
                                    <div>
                                        <label for="qp_number" class="block text-sm font-medium text-gray-700">Número</label>
                                        <input type="number" name="number" id="qp_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 sm:ml-3 sm:w-auto">Agregar</button>
                            <button type="button" onclick="closeQuickPlayerModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <script>
        // --- LÓGICA MODAL PARTIDO (Create/Edit) ---
        function openCreateModal() {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Crear Partido Manual';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('gameForm').action = '{{ route("games.store") }}';
            document.getElementById('gameModal').classList.remove('hidden');
        }

        function openEditModal(game) {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Editar Partido';
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('gameForm').action = '{{ route("games.update", ":id") }}'.replace(':id', game.id);
            
            // Cargar Datos Básicos
            document.getElementById('modal_local_team').value = game.local_team_id;
            document.getElementById('modal_away_team').value = game.away_team_id;
            document.getElementById('modal_court').value = game.court_id;
            document.getElementById('modal_date_time').value = game.date_time ? game.date_time.substring(0, 16) : '';
            
            // Cargar Configuración (Si existe en settings)
            if (game.settings) {
                document.querySelector('input[name="periods"]').value = game.settings['periods_per_game'] ?? 4;
                document.querySelector('input[name="duration"]').value = game.settings['game_duration'] ?? 10;
                document.querySelector('input[name="timeouts"]').value = game.settings['timeouts_per_game'] ?? 2;
                
                if(game.settings['fouls_per_game']) {
                    document.querySelector('input[name="fouls[personal]"]').value = game.settings['fouls_per_game']['personal'] ?? 5;
                    document.querySelector('input[name="fouls[technical]"]').value = game.settings['fouls_per_game']['technical'] ?? 2;
                    document.querySelector('input[name="fouls[unsportsmanlike]"]').value = game.settings['fouls_per_game']['unsportsmanlike'] ?? 2;
                    document.querySelector('input[name="fouls[disqualifying]"]').value = game.settings['fouls_per_game']['disqualifying'] ?? 1;
                }
            }

            document.getElementById('gameModal').classList.remove('hidden');
        }

        function closeModal() { document.getElementById('gameModal').classList.add('hidden'); }
        function resetForm() { document.getElementById('gameForm').reset(); }

        async function submitForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const saveButton = document.getElementById('saveButton');
            saveButton.disabled = true;
            saveButton.innerText = 'Guardando...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                if (response.ok) window.location.reload(); 
                else alert('Error: ' + (data.message || 'Error inesperado.'));
            } catch (error) { alert('Error de red.'); } 
            finally { saveButton.disabled = false; saveButton.innerText = 'Guardar Partido'; }
        }

        // --- LÓGICA MODAL SELECCIÓN JUGADORES ---
        let currentLocalTeamId = null;
        let currentAwayTeamId = null;

        function openPlayerSelectionModal(gameId) {
            resetPlayerSelectionModal();
            document.getElementById('playerSelection_game_id').value = gameId;
            fetch(`/games/${gameId}/details`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('localTeamName').innerText = `Jugadores de ${data.local_team.name}`;
                    document.getElementById('awayTeamName').innerText = `Jugadores de ${data.away_team.name}`;
                    
                    // 1. Guardamos IDs
                    currentLocalTeamId = data.local_team.id;
                    currentAwayTeamId = data.away_team.id;
                    
                    // 2. CORRECCIÓN PUNTO 2: Guardamos Categorías (Necesario para el botón +)
                    currentLocalTeamCategory = data.local_team.category || '';
                    currentAwayTeamCategory = data.away_team.category || '';
                    
                    populatePlayerList('localTeamPlayers', data.local_team.players, 'local');
                    populatePlayerList('awayTeamPlayers', data.away_team.players, 'away');
                    document.getElementById('playerSelectionModal').classList.remove('hidden');
                })
                .catch(error => alert('Error al cargar jugadores.'));
        }

        function populatePlayerList(containerId, players, teamPrefix) {
            const container = document.getElementById(containerId);
            container.innerHTML = ''; 
            players.forEach(player => {
                const playerDiv = document.createElement('div');
                playerDiv.className = 'flex items-center';
                const isSuspended = (player.status === 'suspended');
                const disabledAttr = isSuspended ? 'disabled' : '';
                const labelClass = isSuspended ? 'text-red-500 line-through' : 'ml-2 text-sm text-gray-700';
                const labelSuffix = isSuspended ? ' (SUSPENDIDO)' : '';

                playerDiv.innerHTML = `
                    <input type="checkbox" id="player_${teamPrefix}_${player.id}" name="players[${teamPrefix}][]" value="${player.id}" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-0 player-checkbox" data-team="${teamPrefix}" onchange="updateSelectedCount('${teamPrefix}')" ${disabledAttr}>
                    <label for="player_${teamPrefix}_${player.id}" class="${labelClass}">${player.name} (${player.number})${labelSuffix}</label>
                `;
                container.appendChild(playerDiv);
            });
        }

        function updateSelectedCount(teamPrefix) {
            const checkboxes = document.querySelectorAll(`input.player-checkbox[data-team="${teamPrefix}"]:checked`);
            document.getElementById(`${teamPrefix}SelectedCount`).innerText = checkboxes.length;
        }

        function closePlayerSelectionModal() {
            document.getElementById('playerSelectionModal').classList.add('hidden');
            resetPlayerSelectionModal();
        }

        function resetPlayerSelectionModal() {
            document.getElementById('playerSelectionForm').reset();
            document.getElementById('localTeamPlayers').innerHTML = '';
            document.getElementById('awayTeamPlayers').innerHTML = '';
            document.getElementById('localSelectedCount').innerText = '0';
            document.getElementById('awaySelectedCount').innerText = '0';
        }

        async function saveStartingPlayers(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const localSelected = document.querySelectorAll('input.player-checkbox[data-team="local"]:checked').length;
            const awaySelected = document.querySelectorAll('input.player-checkbox[data-team="away"]:checked').length;

            if (localSelected < 1 || localSelected > 5 || awaySelected < 1 || awaySelected > 5) {
                alert('Debes seleccionar entre 1 y 5 jugadores para cada equipo.'); return;
            }

            try {
                const response = await fetch('/games/save-starting-players', {
                    method: 'POST', body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });
                const data = await response.json();
                if (response.ok && data.success) window.location.href = data.redirect_url;
                else alert(data.message || 'Error.');
            } catch (error) { alert('Error inesperado.'); }
        }

        // --- LÓGICA JUGADOR RÁPIDO ---
        function openQuickPlayerModal(side) {
            const teamId = (side === 'local') ? currentLocalTeamId : currentAwayTeamId;
            const category = (side === 'local') ? currentLocalTeamCategory : currentAwayTeamCategory;

            if (!teamId) {
                alert('Error: No se pudo identificar el equipo.');
                return;
            }

            document.getElementById('quickPlayerForm').reset();
            document.getElementById('qp_team_id').value = teamId;
            document.getElementById('qp_side').value = side;
            document.querySelector('input[name="status"]').value = 'active';

            // --- LÓGICA DE ASIGNACIÓN DE GÉNERO ---
            let genderValue = '';
            if (category === 'Varonil') {
                genderValue = 'hombre';
            } else if (category === 'Femenil') {
                genderValue = 'mujer';
            }
            document.getElementById('qp_gender').value = genderValue;
            // --------------------------------------

            document.getElementById('quickPlayerModal').classList.remove('hidden');
        }

        function closeQuickPlayerModal() {
            document.getElementById('quickPlayerModal').classList.add('hidden');
        }

        async function submitQuickPlayer(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            const side = document.getElementById('qp_side').value;

            submitBtn.innerText = 'Guardando...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route("players.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    closeQuickPlayerModal();
                    
                    const newPlayer = {
                        id: data.player.id,
                        name: formData.get('name'),
                        number: formData.get('number'),
                        status: 'active'
                    };

                    addPlayerCheckboxToDOM(newPlayer, side);
                    
                    alert('Jugador agregado correctamente.');
                } else {
                    let errorMsg = 'Error al guardar.\n';
                    if (data.message) errorMsg += data.message;
                    if (data.errors) {
                        Object.values(data.errors).forEach(msg => errorMsg += '\n' + msg[0]);
                    }
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error de conexión.');
            } finally {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        }

        function addPlayerCheckboxToDOM(player, side) {
            const containerId = (side === 'local') ? 'localTeamPlayers' : 'awayTeamPlayers';
            const container = document.getElementById(containerId);
            const teamPrefix = side;

            const playerDiv = document.createElement('div');
            playerDiv.className = 'flex items-center';
            
            playerDiv.innerHTML = `
                <input type="checkbox" id="player_${teamPrefix}_${player.id}" name="players[${teamPrefix}][]" value="${player.id}" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-0 player-checkbox" data-team="${teamPrefix}" onchange="updateSelectedCount('${teamPrefix}')">
                <label for="player_${teamPrefix}_${player.id}" class="ml-2 text-sm text-gray-700 font-medium">${player.name} (#${player.number})</label>
            `;
            
            container.appendChild(playerDiv);
            document.getElementById(`player_${teamPrefix}_${player.id}`).checked = true;
            updateSelectedCount(teamPrefix);
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

        // --- NUEVA LÓGICA: ÁRBITRO ---
        function openAssignRefereeModal(gameId, currentRefereeId) {
            document.getElementById('referee_game_id').value = gameId;
            
            fetch('/users/arbitros-json')
                .then(response => response.json())
                .then(users => {
                    const select = document.getElementById('referee_select');
                    select.innerHTML = '<option value="">-- Seleccionar --</option>';
                    users.forEach(user => {
                        const selected = user.id == currentRefereeId ? 'selected' : '';
                        select.innerHTML += `<option value="${user.id}" ${selected}>${user.name}</option>`;
                    });
                });

            document.getElementById('assignRefereeModal').classList.remove('hidden');
        }

        function closeAssignRefereeModal() {
            document.getElementById('assignRefereeModal').classList.add('hidden');
        }

        async function submitAssignReferee(event) {
            event.preventDefault();
            const form = event.target;
            const gameId = document.getElementById('referee_game_id').value;
            const formData = new FormData(form);

            const response = await fetch(`/games/${gameId}/assign-referee`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error al asignar árbitro');
            }
        }

        // --- LÓGICA ÁRBITRO RÁPIDO ---
        function openQuickRefereeModal() {
            document.getElementById('quickRefereeForm').reset();
            document.getElementById('quickRefereeModal').classList.remove('hidden');
        }

        function closeQuickRefereeModal() {
            document.getElementById('quickRefereeModal').classList.add('hidden');
        }

        async function submitQuickRefereeForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;

            submitBtn.innerText = 'Guardando...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');

            try {
                const response = await fetch('{{ route("users.store") }}', {
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
                    closeQuickRefereeModal();

                    const refereeSelect = document.getElementById('referee_select');
                    
                    const newOption = document.createElement('option');
                    newOption.value = data.user.id;
                    newOption.text = data.user.name; 
                    newOption.selected = true; 

                    refereeSelect.appendChild(newOption);
                    
                    alert('Árbitro creado y seleccionado.');
                    
                } else {
                    let errorMsg = 'Error al crear el árbitro.\n';
                    if (data.message) errorMsg += data.message + "\n";
                    if (data.errors) {
                        errorMsg += "Detalles:\n";
                        for (const [field, messages] of Object.entries(data.errors)) {
                            errorMsg += `- ${field}: ${messages.join(', ')}\n`;
                        }
                    }
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error de conexión.');
            } finally {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
            }
        }
    </script>