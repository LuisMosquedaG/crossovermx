<x-app-layout>
    @php
        $tournamentSettings = $tournament->settings ? $tournament->settings->settings : [];
        $isManualTournament = !empty($tournamentSettings['is_manual']);
    @endphp

    <div class="py-12">
        <div class="w-[96%] md:w-[90%] mx-auto mb-[10vh]">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- 1. CABEZERA CON BOTÓN VUELTA Y FILTROS -->
                    <div class="mb-6">
                        
                        <!-- FORMULARIO DE FILTROS (SIN BORDE/CONTORNO) -->
                        <form action="{{ route('tournaments.schedule', $tournament) }}" method="GET" class="w-full flex flex-col gap-3">
                            
                            <!-- FILA 1: BOTÓN VOLVER (Izquierda) y BUSCADOR GENERAL (Derecha) -->
                            <div class="flex flex-col sm:flex-row w-full gap-2">
                                
                                <!-- BOTÓN VOLVER (Puesto primero, a la izquierda) -->
                                <a href="{{ route('tournaments.index') }}" class="w-full sm:w-auto shrink-0 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center justify-center text-center">
                                    ← Volver a Torneos
                                </a>

                                <!-- BUSCADOR GENERAL (Puesto después, a la derecha, crece hasta llenar el espacio) -->
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                        class="search-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                        placeholder="Buscar equipo o cancha...">
                                </div>

                            </div>

                            <!-- FILA 2: BOTONES Y 5 CAMPOS -->
                            <div class="flex flex-col md:flex-row w-full gap-2">
                                
                                <!-- BOTÓN: IR A POSICIONES (Naranja Intenso) -->
                                <a href="{{ route('tournaments.standings', $tournament) }}" class="w-full md:w-auto shrink-0 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center justify-center gap-2 text-center" title="Tabla de Posiciones">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z" />
                                    </svg>
                                </a>

                                <!-- BOTÓN: GENERAR SIGUIENTE RONDA (Naranja Intenso) -->
                                <button onclick="updateProgression()" class="w-full md:w-auto shrink-0 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center justify-center gap-2 text-center" title="Actualizar Tabla y Generar Siguiente Ronda">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                </button>

                                <!-- BOTÓN: AGREGAR PARTIDO MANUAL (Solo torneos manuales y solo Admin) -->
                                @if(!auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach') && $isManualTournament)
                                    <button type="button" onclick="openAddManualGameModal()" class="w-full md:w-auto shrink-0 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-3.5 rounded shadow transition duration-150 ease-in-out flex items-center justify-center gap-1.5 text-center" title="Agregar Partido Manual">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        <span class="text-sm font-semibold">Agregar Partido</span>
                                    </button>
                                @endif

                                <!-- CAMPO 1: CATEGORÍA -->
                                <div class="w-full md:flex-1">
                                    <select name="category" onchange="this.form.submit()" 
                                        class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm">
                                        <option value="">Categoría</option>
                                        @if(isset($categories))
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                                    {{ $cat }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- CAMPO 2: FUERZA -->
                                <div class="w-full md:flex-1">
                                    <select name="strength" onchange="this.form.submit()" 
                                        class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm">
                                        <option value="">Fuerza</option>
                                        @if(isset($strengths))
                                            @foreach($strengths as $str)
                                                <option value="{{ $str }}" {{ request('strength') == $str ? 'selected' : '' }}>
                                                    {{ $str }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- CAMPO 3: GRUPO -->
                                <div class="w-full md:flex-1">
                                    <select name="group" onchange="this.form.submit()" 
                                        class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm">
                                        <option value="">Grupo</option>
                                        @if(isset($groups))
                                            @foreach($groups as $grp)
                                                <option value="{{ $grp }}" {{ request('group') == $grp ? 'selected' : '' }}>
                                                    {{ $grp }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- CAMPO 4: FECHA INICIO -->
                                <div class="w-full md:flex-1">
                                    <input type="date" name="start_date" 
                                        value="{{ request('start_date') ? request('start_date') : \Carbon\Carbon::now()->setTimezone('America/Mexico_City')->toDateString() }}" 
                                        onchange="this.form.submit()"
                                        class="w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm">
                                </div>
                                    
                                <!-- CAMPO 5: FECHA FIN -->
                                <div class="w-full md:flex-1">
                                    <input type="date" name="end_date" 
                                        value="{{ request('end_date') }}" 
                                        onchange="this.form.submit()"
                                        class="w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm" placeholder="Hasta...">
                                </div>

                            </div>

                        </form>

                    </div>

                    @if ($games->isEmpty())
                        <div class="text-center py-16 bg-gray-50/80 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="mx-auto w-14 h-14 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mb-3 shadow-sm border border-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-orange-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 mb-1">No hay partidos registrados</h3>
                            <p class="text-gray-500 text-sm max-w-md mx-auto mb-5">
                                @if($isManualTournament)
                                    Aún no se han programado partidos para este torneo. Puedes comenzar agregando el primer partido manualmente.
                                @else
                                    No se han generado partidos para este torneo.
                                @endif
                            </p>
                            @if(!auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach') && $isManualTournament)
                                <button type="button" onclick="openAddManualGameModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg shadow font-bold text-white bg-orange-600 hover:bg-orange-700 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span>Agregar Primer Partido</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <!-- WRAPPER RESPONSIVE -->
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <!-- Columna 1: Acciones (MOVIDA AL INICIO) -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[11%]">
                                            Acciones
                                        </th>
                                        
                                        <!-- Columna 2: Combinada Fecha/Cancha -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[14%]">
                                            Fecha / Cancha
                                        </th>
                                        
                                        <!-- Columna 3: Equipo Local -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                            Local
                                        </th>
                                        
                                        <!-- Columna 4: Equipo Visitante -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                            Visitante
                                        </th>
                                        
                                        <!-- Columna 5: Combinada Categoria/Fuerza -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                            Categoría / Fuerza
                                        </th>
                                        
                                        <!-- Columna 6: Estado -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                            Estado
                                        </th>
                                        
                                        <!-- Columna 7: Árbitro -->
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                            Árbitro
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($games as $game)
                                        <tr>
                                            <!-- 1. COLUMNA: ACCIONES (MOVIDA AL INICIO) -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <!-- Botón Asignar Árbitro (COLOR FIJO: AMARILLO) -->
                                                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
                                                        <button onclick="openAssignRefereeModal({{ $game->id }}, '{{ $game->referee->id ?? '' }}')" class="text-yellow-600 transition-colors" title="Asignar Árbitro">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125L18 14v4.75" />
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    <!-- Botón Estadísticas (COLOR FIJO: ÍNDIGO) -->
                                                    @if($game->status === 'finished')
                                                        <a href="{{ route('games.stats', $game->id) }}" class="text-indigo-600 transition-colors" title="Ver Estadísticas">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z" />
                                                            </svg>
                                                        </a>
                                                        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Arbitro'))
                                                            <!-- Botón Editar Marcador Final -->
                                                            @if($game->isRoundClosed())
                                                                <button type="button" disabled class="text-gray-300 cursor-not-allowed transition-colors p-0.5" title="Ronda cerrada: No se puede modificar el marcador">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                                                    </svg>
                                                                </button>
                                                            @else
                                                                <button onclick="openSetFinalScoreModal({{ $game->id }}, '{{ addslashes($game->localTeam->name) }}', '{{ addslashes($game->awayTeam->name) }}', {{ $game->local_team_score ?? 0 }}, {{ $game->away_team_score ?? 0 }})" class="text-emerald-600 hover:text-emerald-700 transition-colors" title="Modificar Marcador Final">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        @endif
                                                         <!-- Botón Comentarios (COLOR FIJO: NARANJA) -->
                                                         <button onclick="openCommentsModal({{ $game->id }})" class="text-orange-600 transition-colors" title="Comentarios">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                                            </svg>
                                                        </button>
                                                    
                                                    <!-- Jugando -->
                                                    @elseif($game->status === 'playing')
                                                        @if(auth()->user()->hasRole('Arbitro') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
                                                            <!-- Botón Live (COLOR FIJO: VERDE) -->
                                                            <a href="{{ route('games.live', $game->id) }}" class="text-green-600 transition-colors" title="Operar Partido">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z" />
                                                                </svg>
                                                            </a>
                                                        @endif
                                                        @if(auth()->user()->hasRole('Coach'))
                                                            <span class="text-gray-400 text-xs italic">En Curso</span>
                                                        @endif

                                                    <!-- Pendiente -->
                                                    @else
                                                        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Arbitro'))
                                                            <!-- Botón Iniciar (COLOR FIJO: NARANJA) -->
                                                            <button onclick="openPlayerSelectionModal({{ $game->id }})" class="text-orange-600 transition-colors" title="Iniciar Partido">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                                                </svg>
                                                            </button>

                                                            <!-- Botón Marcador Final Directo -->
                                                            @if($game->isRoundClosed())
                                                                <button type="button" disabled class="text-gray-300 cursor-not-allowed transition-colors p-0.5" title="Ronda cerrada: No se puede registrar marcador">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                                                    </svg>
                                                                </button>
                                                            @else
                                                                <button onclick="openSetFinalScoreModal({{ $game->id }}, '{{ addslashes($game->localTeam->name) }}', '{{ addslashes($game->awayTeam->name) }}', {{ $game->local_team_score ?? 0 }}, {{ $game->away_team_score ?? 0 }})" class="text-emerald-600 hover:text-emerald-700 transition-colors" title="Registrar Marcador Final">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        @endif
                                                        
                                                        @if(auth()->user()->hasRole('Coach'))
                                                            <span class="text-blue-400 text-xs italic">Pendiente</span>
                                                        @endif

                                                        <!-- Botón Auto Finalizar -->
                                                        @if((auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin')) && ($game->localTeam->status === 'suspended' || $game->awayTeam->status === 'suspended'))
                                                            <button onclick="autoFinishGame({{ $game->id }})" class="text-red-600 hover:text-red-900 bg-red-50 p-1 rounded" title="Finalizar por Suspensión">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                        
                                                        <!-- Botón Cancelación (COLOR FIJO: ROJO) -->
                                                        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Arbitro'))
                                                            <button onclick="openCancelModal({{ $game->id }}, '{{ $game->localTeam->name }}', '{{ $game->awayTeam->name }}')" class="text-red-600 transition-colors" title="Cancelar Partido">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        <!-- Botón Eliminar Partido (Solo Admin) -->
                                                        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
                                                            <button onclick="deleteManualGame({{ $game->id }})" class="text-rose-600 transition-colors" title="Eliminar Partido">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>

                                                            <!-- Botón Notificar a Coaches por Telegram -->
                                                            <button onclick="sendTelegramMatchNotice({{ $game->id }})" class="text-sky-500 hover:text-sky-700 transition-colors" title="Notificar a Coaches por Telegram">
                                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.52 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                        <!-- BOTÓN REEMPLAZO (VERIFICA QUE LOS ARGUMENTOS SEAN group_name) -->
                                                        @if( (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin')) && $game->round_number == 1 )
                                                            <button onclick="openSwapModal(
                                                                {{ $game->id }}, 
                                                                {{ $game->local_team_id }}, 
                                                                '{{ $game->localTeam->name }}',
                                                                '{{ $game->group_name }}', 
                                                                {{ $game->away_team_id }}, 
                                                                '{{ $game->awayTeam->name }}',
                                                                '{{ $game->group_name }}'   
                                                            )"
                                                            class="text-blue-500 hover:text-blue-700 transition-colors p-1 rounded hover:bg-blue-50" 
                                                            title="Reemplazar Equipo (Global)">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- 2. COLUMNA: FECHA y CANCHA -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                <div class="flex flex-col justify-center leading-tight">
                                            <!-- Cambié max-w-[120px] por max-w-[180px] o más, según necesites -->
                                            <span class="font-bold text-gray-900 text-sm truncate max-w-[180px] mx-auto">
                                                {{ $game->date_time->format('d-m-Y H:i') }}
                                            </span>
                                                    <span class="text-sm text-gray-500 truncate max-w-[120px] mx-auto" title="{{ $game->court->name }}">
                                                        {{ $game->court->name }}
                                                    </span>
                                                </div>
                                            </td>
                                                                                        
                                            <!-- 3. COLUMNA: EQUIPO LOCAL -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                <div class="flex flex-col justify-center items-center leading-tight mx-auto">
                                                    @if($game->status === 'finished')
                                                        <span class="font-bold text-gray-900 text-sm truncate w-full text-center">{{ $game->localTeam->name }}</span>
                                                        <!-- Text-sm y leading-none para mantener la fila compacta -->
                                                        <span class="font-black text-orange-600 text-sm leading-none mt-0.5">
                                                            {{ $game->local_team_score ?? 0 }}
                                                        </span>
                                                    @else
                                                        <span class="font-bold text-gray-900 text-sm truncate w-full text-center">{{ $game->localTeam->name }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- 4. COLUMNA: EQUIPO VISITANTE -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                <div class="flex flex-col justify-center items-center leading-tight mx-auto">
                                                    @if($game->status === 'finished')
                                                        <span class="font-medium text-gray-700 text-sm truncate w-full text-center">{{ $game->awayTeam->name }}</span>
                                                        <span class="font-black text-orange-600 text-sm leading-none mt-0.5">
                                                            {{ $game->away_team_score ?? 0 }}
                                                        </span>
                                                    @else
                                                        <span class="font-medium text-gray-700 text-sm truncate w-full text-center">{{ $game->awayTeam->name }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <!-- 5. COLUMNA: CATEGORÍA y FUERZA (Fila Doble) -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                <div class="flex flex-col justify-center items-center leading-tight space-y-1">
                                                    
                                                    @if($game->localTeam->category == 'Femenil')
                                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-pink-100 text-pink-800 uppercase w-max">
                                                            Femenil
                                                        </span>
                                                    @elseif($game->localTeam->category == 'Mixto')
                                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-purple-100 text-purple-800 uppercase w-max">
                                                            Mixto
                                                        </span>
                                                    @elseif($game->localTeam->category == 'Varonil')
                                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800 uppercase w-max">
                                                            Varonil
                                                        </span>
                                                    @elseif($game->localTeam->category == 'Infantil')
                                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-teal-100 text-teal-800 uppercase w-max">
                                                            Infantil
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif

                                                    <span class="text-[10px] text-gray-400 font-semibold tracking-wider">
                                                        {{ $game->localTeam->strength ?? '-' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- 6. COLUMNA: ESTADO -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                @if($game->status === 'playing')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">En Juego</span>
                                                @elseif($game->status === 'finished')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-200 text-gray-800">Finalizado</span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                                @endif
                                            </td>
                                            
                                            <!-- 7. COLUMNA: ÁRBITRO -->
                                            <td class="px-4 py-3 whitespace-nowrap text-center align-middle">
                                                @if($game->referee)
                                                    <div class="text-sm font-medium text-gray-900 truncate mx-auto" title="{{ $game->referee->name }}">
                                                        {{ $game->referee->name }}
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">Sin asignar</span>
                                                @endif
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- CONTROLES DE PAGINACIÓN -->
                            <div class="mt-4 px-4 pb-4">
                                {{ $games->links() }}
                            </div>
                        </div>
                        <!-- FIN WRAPPER RESPONSIVE -->
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

    <!-- Modal para Agregar Partido Manual -->
    <div id="addManualGameModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeAddManualGameModal()" aria-hidden="true"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all border border-gray-100">
                    <form id="addManualGameForm" onsubmit="submitAddManualGame(event)">
                        @csrf
                        <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modalManualGameTitle">Crear Partido Manual</h3>

                            <!-- Fecha y Hora -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Día / Fecha</label>
                                    <input type="date" name="date" id="manualGameDate" required onchange="onManualGameDateChanged()"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2 px-3">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1 flex items-center justify-between">
                                        <span>Horario</span>
                                        <span class="text-[10px] font-normal text-gray-500 lowercase">(duración: {{ $gameDurationMinutes ?? 40 }} min)</span>
                                    </label>
                                    <input type="time" name="time" id="manualGameTime" required onchange="updateCourtAvailability()" oninput="updateCourtAvailability()"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2 px-3">
                                </div>
                            </div>

                            <!-- Cancha -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Cancha</label>
                                <select name="court_id" id="manualGameCourtId" required onchange="onManualCourtSelected()"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2 px-3">
                                    <option value="">-- Seleccionar Cancha --</option>
                                    @if(isset($courts))
                                        @foreach($courts as $court)
                                            <option value="{{ $court->id }}" data-original-name="{{ $court->name }}">{{ $court->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="courtConflictNotice" class="hidden mt-1.5 p-2 rounded-lg text-xs font-medium bg-amber-50 border border-amber-200 text-amber-800"></div>
                            </div>

                            <!-- Equipos: Local y Visitante -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Equipo Local</label>
                                    <select name="local_team_id" id="manualGameLocalTeam" required onchange="filterManualAwayTeams()"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2 px-3">
                                        <option value="">-- Seleccionar Local --</option>
                                        @if(isset($teams))
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}" data-category="{{ $team->category }}" data-strength="{{ $team->strength }}" data-group="{{ $team->group_name }}">
                                                    {{ $team->name }} ({{ $team->group_name }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Equipo Visitante</label>
                                    <select name="away_team_id" id="manualGameAwayTeam" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2 px-3">
                                        <option value="">-- Seleccionar Visitante --</option>
                                        @if(isset($teams))
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}" data-category="{{ $team->category }}" data-strength="{{ $team->strength }}" data-group="{{ $team->group_name }}">
                                                    {{ $team->name }} ({{ $team->group_name }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- Resumen de Configuración Heredada -->
                            @php
                                $tSettings = $tournament->settings ? $tournament->settings->settings : [];
                                $hasKnockout = !empty($tSettings['knock_out']) || !empty($tSettings['knock_out_diff']);
                            @endphp
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-xs text-gray-600 space-y-2.5">
                                <div class="font-bold text-gray-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Configuración heredada del torneo:
                                </div>

                                <!-- Tiempos y Periodos -->
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Tiempos y Periodos</span>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-white p-2 rounded-lg border border-gray-200 text-center">
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Periodos</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['periods_per_game'] ?? 4 }}</b>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Min/Periodo</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['game_duration'] ?? 10 }} min</b>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Tiempos Fuera</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['timeouts_per_game'] ?? 5 }}</b>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Descanso</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['rest_between_periods'] ?? 0 }} min</b>
                                        </div>
                                    </div>
                                </div>

                                <!-- Faltas -->
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Límite de Faltas</span>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-white p-2 rounded-lg border border-gray-200 text-center">
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Personales</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['limit_foul_personal'] ?? 5 }}</b>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Técnicas</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['limit_foul_technical'] ?? 2 }}</b>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Antideportivas</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['limit_foul_unsportsmanlike'] ?? 2 }}</b>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block text-[10px]">Descalificantes</span>
                                            <b class="text-gray-900 text-xs">{{ $tSettings['limit_foul_disqualifying'] ?? 1 }}</b>
                                        </div>
                                    </div>
                                </div>

                                <!-- Knock-out -->
                                <div class="bg-white p-2.5 rounded-lg border border-gray-200 flex flex-wrap items-center justify-between gap-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-gray-700 font-bold text-[11px]">Knock-out:</span>
                                        @if($hasKnockout)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-orange-100 text-orange-800">Incluido</span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">No incluido</span>
                                        @endif
                                    </div>
                                    @if($hasKnockout)
                                        <div class="text-[11px] text-gray-600 flex items-center gap-2">
                                            @if(!empty($tSettings['knock_out']))
                                                <span>Máx: <b class="text-gray-900">{{ $tSettings['knock_out'] }} pts</b></span>
                                            @endif
                                            @if(!empty($tSettings['knock_out']) && !empty($tSettings['knock_out_diff']))
                                                <span class="text-gray-300">|</span>
                                            @endif
                                            @if(!empty($tSettings['knock_out_diff']))
                                                <span>Dif: <b class="text-gray-900">{{ $tSettings['knock_out_diff'] }} pts</b></span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-3.5 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-gray-100">
                            <button type="submit" id="btnSubmitManualGame" class="inline-flex justify-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-bold text-white shadow hover:bg-orange-700 transition duration-150 ease-in-out">
                                Guardar Partido
                            </button>
                            <button type="button" onclick="closeAddManualGameModal()" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Registrar Marcador Final Directo -->
    <div id="setFinalScoreModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeSetFinalScoreModal()" aria-hidden="true"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all border border-gray-100">
                    <form id="setFinalScoreForm" onsubmit="submitSetFinalScore(event)">
                        @csrf
                        <input type="hidden" id="finalScoreGameId" name="game_id">
                        
                        <div class="p-6 space-y-5">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-orange-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                        </svg>
                                        <span id="modalFinalScoreTitle">Registrar Marcador Final</span>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Captura directa del resultado del encuentro</p>
                                </div>
                                <button type="button" onclick="closeSetFinalScoreModal()" class="text-gray-400 hover:text-gray-600 transition">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Marcadores de Equipos -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="grid grid-cols-7 gap-3 items-center">
                                    <!-- Equipo Local -->
                                    <div class="col-span-3 text-center">
                                        <label for="modalLocalScore" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1 truncate" id="modalLocalTeamLabel" title="Equipo Local">
                                            Equipo Local
                                        </label>
                                        <input type="number" name="local_team_score" id="modalLocalScore" min="0" value="0" required
                                            oninput="calculateMatchOutcome()"
                                            class="block w-full text-center text-3xl font-black text-gray-900 rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-3">
                                        <span class="text-[11px] text-gray-500 mt-1 block font-medium">Local</span>
                                    </div>

                                    <!-- VS Separador -->
                                    <div class="col-span-1 flex flex-col items-center justify-center">
                                        <span class="text-xs font-black text-gray-400 bg-white rounded-full px-2 py-1 shadow-sm border border-gray-200">VS</span>
                                    </div>

                                    <!-- Equipo Visitante -->
                                    <div class="col-span-3 text-center">
                                        <label for="modalAwayScore" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1 truncate" id="modalAwayTeamLabel" title="Equipo Visitante">
                                            Equipo Visitante
                                        </label>
                                        <input type="number" name="away_team_score" id="modalAwayScore" min="0" value="0" required
                                            oninput="calculateMatchOutcome()"
                                            class="block w-full text-center text-3xl font-black text-gray-900 rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-3">
                                        <span class="text-[11px] text-gray-500 mt-1 block font-medium">Visitante</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado del Resultado (Ganador / Perdedor / Empate) -->
                            <div id="finalScoreOutcomeCard" class="rounded-xl border p-4 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div id="finalScoreOutcomeIcon" class="w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0">
                                        ⚖️
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-0.5" id="finalScoreOutcomeTitle">
                                            Resultado del Partido
                                        </div>
                                        <div class="text-sm font-bold text-gray-900 truncate" id="finalScoreOutcomeDesc">
                                            Empate (0 - 0)
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5" id="finalScoreOutcomeSub">
                                            Ambos equipos sumarán los puntos asignados por empate.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nota informativa -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-800 flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Al guardar, el partido cambiará automáticamente su estado a <strong>Finalizado</strong> y los puntos ganados/perdidos y canastas se reflejarán de inmediato en la tabla de posiciones del torneo.</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-3.5 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-gray-100">
                            <button type="submit" id="btnSubmitFinalScore" class="inline-flex justify-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-bold text-white shadow hover:bg-orange-700 transition duration-150 ease-in-out">
                                Guardar Marcador
                            </button>
                            <button type="button" onclick="closeSetFinalScoreModal()" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Asignar Árbitro -->
    <div id="assignRefereeModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
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

    <!-- NUEVO MODAL: Crear Árbitro Rápido -->
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
                                        <x-input-label for="q_referee_name" :value="__('Nombre Completo')" />
                                        <x-text-input id="q_referee_name" class="block mt-1 w-full" type="text" name="name" required />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="q_referee_email" :value="__('Usuario (Email)')" />
                                        <x-text-input id="q_referee_email" class="block mt-1 w-full" type="text" name="email" required placeholder="Ej: arbitro.nuevo" />
                                        <p class="mt-1 text-xs text-gray-500">El dominio @cliente se agregará automáticamente.</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="q_referee_password" :value="__('Contraseña')" />
                                            <x-text-input id="q_referee_password" class="block mt-1 w-full" type="password" name="password" required />
                                        </div>
                                        <div>
                                            <x-input-label for="q_referee_password_confirmation" :value="__('Confirmar')" />
                                            <x-text-input id="q_referee_password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
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

    <!-- Modal para Seleccionar Jugadores Iniciales -->
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
                                            <!-- EQUIPO LOCAL -->
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
                                            <!-- EQUIPO VISITANTE -->
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

    <!-- Modal para Cancelación de Partidos -->
    <div id="cancelGameModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="cancelGameForm" onsubmit="submitCancel(event)">
                        @csrf
                        <input type="hidden" name="game_id" id="cancel_game_id">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Cancelar Partido</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">Partido: <strong><span id="cancel_game_teams"></span></strong></p>
                                        
                                        <div class="space-y-3">
                                            <label class="block text-sm font-medium text-gray-700">Motivo de la cancelación:</label>
                                            
                                            <!-- Opción 1: Clima -->
                                            <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                                <input type="radio" name="reason" value="weather" class="mt-1 text-indigo-600 focus:ring-indigo-500" required>
                                                <div class="ml-3">
                                                    <span class="block text-sm font-medium text-gray-900">Reagendar Partido</span>
                                                    <span class="block text-xs text-gray-500">El partido se reagendará automáticamente al próximo hueco disponible según las reglas del torneo.</span>
                                                </div>
                                            </label>

                                            <!-- Opción 2: Default -->
                                            <div class="border-t pt-2 mt-2">
                                                <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Inasistencia (Define ganador - 1-0)</span>
                                                
                                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                                    <input type="radio" name="reason" value="local_no_show" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-2 text-sm text-gray-700">Equipo Local no llegó (Gana Visitante)</span>
                                                </label>
                                                
                                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                                    <input type="radio" name="reason" value="away_no_show" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-2 text-sm text-gray-700">Equipo Visitante no llegó (Gana Local)</span>
                                                </label>
                                                
                                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                                    <input type="radio" name="reason" value="double_no_show" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-2 text-sm text-gray-700">Ninguno llegó (Pierden ambos)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Confirmar Cancelación</button>
                            <button type="button" onclick="closeCancelModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar Jugador Rápido (Dentro de Partido) -->
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
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Agregar Jugador</h3>
                                <p class="text-sm text-gray-500 mt-1">Registro rápido para partido en curso.</p>
                                
                                <div class="mt-4 space-y-4">
                                    <!-- Solo Nombre -->
                                    <div>
                                        <label for="qp_name" class="block text-sm font-medium text-gray-700">Nombre del Jugador</label>
                                        <input type="text" name="name" id="qp_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>
                                    
                                    <!-- Solo Número -->
                                    <div>
                                        <label for="qp_number" class="block text-sm font-medium text-gray-700">Número</label>
                                        <input type="text" name="number" id="qp_number" required inputmode="numeric" pattern="[0-9]*" maxlength="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>

                                    <!-- Selector de Género -->
                                    <div id="qp_gender_container" class="hidden">
                                        <label for="qp_gender" class="block text-sm font-medium text-gray-700">Sexo</label>
                                        <select name="gender" id="qp_gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2 bg-white">
                                            <option value="hombre">Hombre</option>
                                            <option value="mujer">Mujer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 sm:ml-3 sm:w-auto">
                                Agregar
                            </button>
                            <button type="button" onclick="closeQuickPlayerModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="commentsModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    
                    <div class="bg-gray-50 px-4 py-3 border-b max-h-60 overflow-y-auto">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Historial de Comentarios</h3>
                        <div id="commentsList" class="space-y-3">
                            <p class="text-sm text-gray-400 italic text-center">Cargando comentarios...</p>
                        </div>
                    </div>

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Agregar Observación / Nota</h3>
                        <form id="commentForm" onsubmit="submitComment(event)">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Referencia:</label>
                                <select name="player_id" id="commentPlayerId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm hover:border-orange-300 focus:border-orange-500 focus:ring-orange-200 sm:text-sm transition duration-150 ease-in-out">
                                    <option value="">-- Comentario General del Partido --</option>
                                </select>
                                
                                <!-- SOLO ÁRBITROS, ADMIN Y SUPER ADMIN PUEDEN VER ESTO -->
                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Arbitro'))
                                    <div id="suspensionInputContainer" class="mt-2 hidden p-3 bg-red-50 rounded border border-red-100">
                                        <label class="flex items-center space-x-2 text-sm font-medium text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Suspensión por Partidos</span>
                                        </label>
                                        <div class="mt-1 flex items-center space-x-2">
                                            <input type="number" name="suspension_games" id="suspension_games_input" min="1" max="99" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="0">
                                            <span class="text-xs text-gray-600">Ingresa un número mayor a 0 para activar.</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Comentario:</label>
                                <textarea name="content" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Escribe aquí las observaciones del partido..."></textarea>
                            </div>

                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">
                                    Guardar
                                </button>
                                <button type="button" onclick="closeCommentsModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto">
                                    Cerrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Reasignación Global (ACTUALIZADO A TEMA NARANJA) -->
    <div id="globalSwapModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">

                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Reemplazo de Equipo</h3>
                                <div class="mt-2 space-y-4">
                                    
                                    <!-- PASO 1: ¿Quién falta? -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 mb-2">1. ¿Qué equipo de este partido NO llega?</p>
                                        
                                    <!-- CAMBIO 2 y 3: Checkbox Naranja y Focus Ring Naranja (eliminado el azul) -->
                                    <div class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer" onclick="document.getElementById('radio_local').checked = true; filterTeamDropdown();">
                                        <input id="radio_local" name="team_selector" type="radio" value="local" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                        <label for="radio_local" class="ml-2 block text-sm text-gray-900 cursor-pointer flex-1">
                                            Local: <span id="label_local_name" class="font-bold">Nombre Equipo</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer mt-1" onclick="document.getElementById('radio_away').checked = true; filterTeamDropdown();">
                                        <input id="radio_away" name="team_selector" type="radio" value="away" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                        <label for="radio_away" class="ml-2 block text-sm text-gray-900 cursor-pointer flex-1">
                                            Visitante: <span id="label_away_name" class="font-bold">Nombre Equipo</span>
                                        </label>
                                    </div>

                                    <hr class="border-gray-200">

                                    <!-- PASO 2: ¿Quién entra? -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 mb-2">2. ¿Qué equipo lo reemplaza en TODO el torneo?</p>
                                        <select id="swap_team_in_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2">
                                            <option value="">-- Primero selecciona el equipo a reemplazar arriba --</option>
                                        </select>
                                        <!-- Mensaje de ayuda visual -->
                                        <p id="swap_feedback_msg" class="text-xs text-gray-400 mt-1 hidden">Mostrando equipos de: <span id="swap_filter_details" class="font-bold text-gray-600"></span></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <!-- CAMBIO 1: Botón Confirmar Cambio a Naranja -->
                        <button type="button" onclick="confirmGlobalSwap()" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 sm:ml-3 sm:w-auto">
                            Confirmar Cambio
                        </button>
                        <button type="button" onclick="closeSwapModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>

        let currentGameIdForComments = null;
        let currentLocalTeamId = null;
        let currentAwayTeamId = null;
        let currentLocalTeamCategory = null;
        let currentAwayTeamCategory = null;

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
                    
                    alert('Árbitro creado y asignado correctamente');
                    
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
        async function autoFinishGame(gameId) {
            if(!confirm('¿Finalizar este partido automáticamente por suspensión de equipo?')) return;

            try {
                const response = await fetch(`/games/${gameId}/auto-finish-suspended`, {
                    method: 'POST',
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
                    alert(data.message || 'Error.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error.');
            }
        }

        function openPlayerSelectionModal(gameId) {
            resetPlayerSelectionModal();
            document.getElementById('playerSelection_game_id').value = gameId;
            fetch(`/games/${gameId}/details`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('localTeamName').innerText = `Jugadores de ${data.local_team.name}`;
                    document.getElementById('awayTeamName').innerText = `Jugadores de ${data.away_team.name}`;
                    currentLocalTeamId = data.local_team.id;
                    currentAwayTeamId = data.away_team.id;
                    
                    // Determinar categorías de forma robusta e insensible a mayúsculas
                    let categoryGroup = (data.category_group || data.group_name || '').toString().toLowerCase();
                    let localCat = (data.local_team.category || '').toString().toLowerCase();
                    let awayCat = (data.away_team.category || '').toString().toLowerCase();

                    if (categoryGroup.includes('varonil') || localCat.includes('varonil') || awayCat.includes('varonil')) {
                        currentLocalTeamCategory = 'Varonil';
                        currentAwayTeamCategory = 'Varonil';
                    } else if (categoryGroup.includes('femenil') || localCat.includes('femenil') || awayCat.includes('femenil')) {
                        currentLocalTeamCategory = 'Femenil';
                        currentAwayTeamCategory = 'Femenil';
                    } else {
                        // Fallback a los valores crudos
                        currentLocalTeamCategory = data.local_team.category || '';
                        currentAwayTeamCategory = data.away_team.category || '';
                    }

                    populatePlayerList('localTeamPlayers', data.local_team.players, 'local');
                    populatePlayerList('awayTeamPlayers', data.away_team.players, 'away');
                    document.getElementById('playerSelectionModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudieron cargar los jugadores del partido.');
                });
        }

        function getPlayerAvatarHtml(player) {
            if (player.avatar_url) {
                return `<img src="${player.avatar_url}" alt="${player.name}" class="w-8 h-8 rounded-full object-cover ml-2 mr-2 shrink-0 border border-gray-200">`;
            } else if (player.image_path) {
                return `<img src="/storage/${player.image_path}" alt="${player.name}" class="w-8 h-8 rounded-full object-cover ml-2 mr-2 shrink-0 border border-gray-200">`;
            } else if (player.gender === 'hombre') {
                return `<img src="/images/hombre.png" alt="Hombre" class="w-8 h-8 rounded-full object-cover ml-2 mr-2 shrink-0 border border-gray-200">`;
            } else if (player.gender === 'mujer') {
                return `<img src="/images/mujer.png" alt="Mujer" class="w-8 h-8 rounded-full object-cover ml-2 mr-2 shrink-0 border border-gray-200">`;
            } else {
                return `<div class="w-8 h-8 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold ml-2 mr-2 shrink-0">${player.name ? player.name.substring(0,1) : 'J'}</div>`;
            }
        }

        function populatePlayerList(containerId, players, teamPrefix) {
            const container = document.getElementById(containerId);
            container.innerHTML = ''; 
            players.forEach(player => {
                const playerDiv = document.createElement('div');
                playerDiv.className = 'flex items-center py-1.5 border-b border-gray-50 last:border-0';
                
                const isSuspended = (player.status === 'suspended');
                const disabledAttr = isSuspended ? 'disabled' : '';
                const labelClass = isSuspended ? 'text-red-500 line-through' : 'text-sm text-gray-700 font-medium';
                const labelSuffix = isSuspended ? ' (SUSPENDIDO)' : '';
                const avatarHtml = getPlayerAvatarHtml(player);

                playerDiv.innerHTML = `
                    <input type="checkbox" id="player_${teamPrefix}_${player.id}" name="players[${teamPrefix}][]" value="${player.id}" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-0 focus:outline-none player-checkbox" data-team="${teamPrefix}" onchange="updateSelectedCount('${teamPrefix}')" ${disabledAttr}>
                    ${avatarHtml}
                    <label for="player_${teamPrefix}_${player.id}" class="${labelClass}">${player.name} (#${player.number})${labelSuffix}</label>
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
                alert('Debes seleccionar entre 1 y 5 jugadores para cada equipo.');
                return;
            }

            try {
                const response = await fetch('/games/save-starting-players', {
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
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Ocurrió un error al guardar la selección.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            }
        }

        // --- NUEVAS FUNCIONES PARA CANCELACIÓN ---
        
        function openCancelModal(gameId, localTeam, awayTeam) {
            document.getElementById('cancel_game_id').value = gameId;
            document.getElementById('cancel_game_teams').innerText = `${localTeam} vs ${awayTeam}`;
            
            const radios = document.getElementsByName('reason');
            radios.forEach(r => r.checked = false);

            document.getElementById('cancelGameModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelGameModal').classList.add('hidden');
        }

        async function submitCancel(event) {
            event.preventDefault();
            const form = event.target;
            const gameId = document.getElementById('cancel_game_id').value;
            
            if(!confirm('¿Estás seguro de realizar esta acción?')) return;

            try {
                const response = await fetch(`/games/${gameId}/cancel`, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert(data.message);
                    window.location.reload(); 
                } else {
                    alert(data.message || 'Ocurrió un error al cancelar.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            }
        }

        function openCommentsModal(gameId) {
            currentGameIdForComments = gameId;
            document.getElementById('commentsModal').classList.remove('hidden');
            
            document.getElementById('commentForm').reset(); 
            const suspensionContainer = document.getElementById('suspensionInputContainer');
            if(suspensionContainer) suspensionContainer.classList.add('hidden');
            
            loadComments();
            loadGamePlayers(gameId); 
        }

        function closeCommentsModal() {
            document.getElementById('commentsModal').classList.add('hidden');
            document.getElementById('commentForm').reset();
            const suspensionContainer = document.getElementById('suspensionInputContainer');
            if(suspensionContainer) suspensionContainer.classList.add('hidden');
        }

        // --- LÓGICA VISUAL DEL CAMPO DE SUSPENSIÓN ---
        document.getElementById('commentPlayerId').addEventListener('change', function(e) {
            const playerId = e.target.value;
            const container = document.getElementById('suspensionInputContainer');
            const input = document.getElementById('suspension_games_input');
            
            if(playerId) {
                container.classList.remove('hidden');
                input.value = ''; 
            } else {
                container.classList.add('hidden');
                input.value = '';
            }
        });

        async function submitComment(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            const suspensionGames = parseInt(document.getElementById('suspension_games_input').value) || 0;
            const selectedValue = document.getElementById('commentPlayerId').value;
            const content = formData.get('content');

            if (suspensionGames === 0 && !content) {
                alert('Debes escribir un comentario o asignar días de suspensión.');
                return;
            }

            try {
                let url;
                let dataToSend = formData;
                let isTeam = false;

                // 1. Verificar si es EQUIPO (empieza con 'team-')
                if (selectedValue.startsWith('team-')) {
                    isTeam = true;
                    const teamId = selectedValue.replace('team-', '');
                    
                    if (suspensionGames > 0) {
                        const suspensionData = new FormData();
                        suspensionData.append('team_id', teamId);
                        suspensionData.append('game_id', currentGameIdForComments);
                        suspensionData.append('games', suspensionGames);
                        suspensionData.append('content', content);
                        suspensionData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        
                        url = '/tournaments/suspend-team';
                        dataToSend = suspensionData;
                    } else {
                        url = `/games/${currentGameIdForComments}/comments`;
                        dataToSend.append('team_id', teamId); 
                        dataToSend.append('player_id', ''); 
                    }

                } 
                else {
                    if (suspensionGames > 0) {
                        const suspensionData = new FormData();
                        suspensionData.append('player_id', selectedValue);
                        suspensionData.append('game_id', currentGameIdForComments);
                        suspensionData.append('games', suspensionGames);
                        suspensionData.append('content', content);
                        suspensionData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        
                        url = '/tournaments/suspend-player';
                        dataToSend = suspensionData;
                    } else {
                        url = `/games/${currentGameIdForComments}/comments`;
                    }
                }

                const response = await fetch(url, {
                    method: 'POST',
                    body: dataToSend,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message);
                    
                    form.reset();
                    document.getElementById('suspensionInputContainer').classList.add('hidden');
                    document.getElementById('suspension_games_input').value = '';
                    
                    loadComments(); 
                } else {
                    alert('Error al guardar.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            }
        }

        function loadGamePlayers(gameId) {
            const select = document.getElementById('commentPlayerId');
            select.innerHTML = '<option value="">-- Comentario General del Partido --</option>';
            select.disabled = true; 

            fetch(`/games/${gameId}/details`)
                .then(response => response.json())
                .then(data => {
                    
                    const createTeamOption = (team, side) => {
                        const opt = document.createElement('option');
                        opt.value = `team-${team.id}`;
                        opt.text = `EQUIPO: ${team.name}`;
                        opt.style.fontWeight = 'bold';
                        select.appendChild(opt);
                    };

                    createTeamOption(data.local_team, 'local');
                    createTeamOption(data.away_team, 'away');

                    const separator = document.createElement('option');
                    separator.disabled = true;
                    separator.text = "─────────────────";
                    select.appendChild(separator);

                    const createPlayerOptions = (players, teamName) => {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = `Jugadores: ${teamName}`;
                        
                        players.forEach(p => {
                            const option = document.createElement('option');
                            option.value = p.id;
                            
                            if (p.status === 'suspended') {
                                option.disabled = true; 
                                option.text = `🚫 ${p.name} (#${p.number}) - SUSPENDIDO`;
                            } else {
                                option.text = `${p.name} (#${p.number})`;
                            }
                            optgroup.appendChild(option);
                        });
                        select.appendChild(optgroup);
                    };

                    if (data.local_team && data.local_team.players) {
                        createPlayerOptions(data.local_team.players, data.local_team.name);
                    }
                    if (data.away_team && data.away_team.players) {
                        createPlayerOptions(data.away_team.players, data.away_team.name);
                    }
                    
                    select.disabled = false;
                })
                .catch(error => {
                    console.error('Error cargando datos:', error);
                });
        }

        function loadComments() {
            const list = document.getElementById('commentsList');
            list.innerHTML = '<p class="text-sm text-gray-400 italic text-center">Cargando comentarios...</p>';

            if (!currentGameIdForComments) return;

            fetch(`/games/${currentGameIdForComments}/comments`)
                .then(response => response.json())
                .then(comments => {
                    if (comments.length === 0) {
                        list.innerHTML = '<p class="text-sm text-gray-500 text-center">No hay comentarios registrados.</p>';
                    } else {
                        list.innerHTML = '';
                        comments.forEach(c => {
                            let targetLabel = '';
                            let teamBadge = ''; 

                            if (c.player_id) {
                                const playerName = c.player ? c.player.name : 'Jugador Desconocido';
                                const playerNum = (c.player && c.player.number) ? `#${c.player.number}` : '';
                                
                                targetLabel = `Jugador: <span class="font-bold text-gray-700">${playerName}</span> ${playerNum}`;
                                
                                if (c.player && c.player.team) {
                                    teamBadge = `<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                        ${c.player.team.name}
                                    </span>`;
                                }

                            } 
                            else if (c.team_id) {
                                const teamName = c.team ? c.team.name : 'Equipo Desconocido';
                                targetLabel = `<span class="font-bold text-orange-600">Equipo</span>: <span class="font-bold text-gray-700">${teamName}</span>`;
                            } 
                            else {
                                targetLabel = `<span class="font-bold text-orange-600">General</span>`;
                            }

                            const div = document.createElement('div');
                            div.className = 'bg-white p-2 rounded border border-gray-200 text-sm shadow-sm mb-2';
                            div.innerHTML = `
                                <div class="flex flex-col sm:flex-row justify-between sm:items-start mb-1 gap-1">
                                    <div class="flex flex-wrap items-center text-orange-900">
                                        <span class="font-semibold">${targetLabel}</span>
                                        ${teamBadge}
                                    </div>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-0 sm:ml-2">${new Date(c.created_at).toLocaleString()}</span>
                                </div>
                                <div class="bg-gray-50 p-2 rounded text-gray-800 break-words border border-gray-100">
                                    ${c.content}
                                </div>
                                <div class="text-xs text-gray-400 mt-1 text-right">Por: ${c.user ? c.user.name : 'Usuario'}</div>
                            `;
                            list.appendChild(div);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    list.innerHTML = '<p class="text-sm text-red-500 text-center">Error al cargar comentarios.</p>';
                });
        }
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
                // ========================
        // LÓGICA JUGADOR RÁPIDO
        // ========================

        function openQuickPlayerModal(side) {
            const teamId = (side === 'local') ? currentLocalTeamId : currentAwayTeamId;
            const category = (side === 'local') ? currentLocalTeamCategory : currentAwayTeamCategory;

            if (!teamId) {
                alert('Error: No se pudo identificar el equipo.');
                return;
            }

            // Limpiamos el formulario (pero necesitamos reasignar el status manual si el reset lo borra)
            document.getElementById('quickPlayerForm').reset();
            document.getElementById('qp_team_id').value = teamId;
            document.getElementById('qp_side').value = side;
            
            // Forzamos status active de nuevo por si el reset lo borró
            document.querySelector('input[name="status"]').value = 'active';

            // --- LÓGICA DE ASIGNACIÓN DE GÉNERO ---
            const genderContainer = document.getElementById('qp_gender_container');
            const genderSelect = document.getElementById('qp_gender');
            
            let genderValue = 'hombre'; // Default
            const normalizedCategory = (category || '').toString().trim().toLowerCase();
            
            if (normalizedCategory === 'varonil') {
                genderValue = 'hombre';
                genderContainer.classList.add('hidden');
            } else if (normalizedCategory === 'femenil') {
                genderValue = 'mujer';
                genderContainer.classList.add('hidden');
            } else if (normalizedCategory === 'infantil' || normalizedCategory === 'mixto') {
                // Infantil o Mixto -> Mostramos el selector para elegir sexo
                genderValue = 'hombre';
                genderContainer.classList.remove('hidden');
            } else {
                genderContainer.classList.remove('hidden');
            }
            
            if (genderSelect) {
                genderSelect.value = genderValue;
            }
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
            const side = document.getElementById('qp_side').value; // 'local' o 'away'

            submitBtn.innerText = 'Guardando...';
            submitBtn.disabled = true;

            try {
                // Suponemos que existe una ruta para guardar jugadores (la misma que usas en el módulo de jugadores)
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
                    // ÉXITO: Cerrar modal y agregar a la lista visualmente
                    closeQuickPlayerModal();
                    
                    // Construimos el objeto jugador simulado para la UI
                    const newPlayer = {
                        id: data.player.id,
                        name: data.player.name || formData.get('name'),
                        number: data.player.number || formData.get('number'),
                        gender: data.player.gender || formData.get('gender'),
                        image_path: data.player.image_path,
                        avatar_url: data.player.avatar_url,
                        status: 'active'
                    };

                    // Agregar al DOM
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
            const teamPrefix = side; // 'local' o 'away'

            const playerDiv = document.createElement('div');
            playerDiv.className = 'flex items-center py-1.5 border-b border-gray-50 last:border-0';
            const avatarHtml = getPlayerAvatarHtml(player);
            
            // Replicamos la estructura HTML de populatePlayerList
            playerDiv.innerHTML = `
                <input type="checkbox" id="player_${teamPrefix}_${player.id}" name="players[${teamPrefix}][]" value="${player.id}" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-0 focus:outline-none player-checkbox" data-team="${teamPrefix}" onchange="updateSelectedCount('${teamPrefix}')">
                ${avatarHtml}
                <label for="player_${teamPrefix}_${player.id}" class="text-sm text-gray-700 font-medium">${player.name} (#${player.number})</label>
            `;
            
            container.appendChild(playerDiv);
            // Auto-seleccionamos el nuevo jugador
            document.getElementById(`player_${teamPrefix}_${player.id}`).checked = true;
            updateSelectedCount(teamPrefix);
        }
        async function updateProgression() {
            if(!confirm('¿Verificar y generar la siguiente ronda de partidos?')) return;

            const btn = document.querySelector('button[onclick="updateProgression()"]');
            // Si por alguna razón no encuentra el botón, no hacemos nada para evitar error
            if (!btn) return;

            const originalText = btn.innerHTML;
            
            // Estado de carga
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

            try {
                const response = await fetch(`{{ route('tournaments.update-progression', $tournament) }}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    
                    // --- MEJORA: RECARGA INTELIGENTE ---
                    // Recargamos si se crearon juegos O si el mensaje indica actualización
                    const shouldReload = (data.games_created > 0) || (data.message && data.message.toLowerCase().includes('actualizado'));

                    if (shouldReload) {
                        window.location.reload();
                    } else {
                        // Si no recargamos, devolvemos el botón a su estado normal
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                    // ------------------------------
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error en updateProgression:', error);
                alert('Ocurrió un error de conexión.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // 1. CREAR BASE DE DATOS LOCAL DE EQUIPOS (Desde PHP)
        const allTeamsData = @json($teams);

        // Variables globales
        let currentLocalId = null;
        let currentAwayId = null;
        let selectedTeamOutId = null;

        function openSwapModal(gameId, localId, localName, localGroup, awayId, awayName, awayGroup) {
            currentLocalId = localId;
            currentAwayId = awayId;
            selectedTeamOutId = null;

            // Guardamos el group_name en los inputs
            document.getElementById('radio_local').dataset.group = localGroup || '';
            document.getElementById('radio_away').dataset.group = awayGroup || '';

            // Actualizar etiquetas de texto
            document.getElementById('label_local_name').innerText = localName + ` (${localGroup || 'Sin Grupo'})`;
            document.getElementById('label_away_name').innerText = awayName + ` (${awayGroup || 'Sin Grupo'})`;

            // Limpiar select
            const select = document.getElementById('swap_team_in_id');
            select.innerHTML = '<option value="">-- Selecciona un equipo compatible --</option>';
            
            document.getElementById('radio_local').checked = false;
            document.getElementById('radio_away').checked = false;

            document.getElementById('globalSwapModal').classList.remove('hidden');
        }
        
        function closeSwapModal() {
            document.getElementById('globalSwapModal').classList.add('hidden');
        }

        function filterTeamDropdown() {
            const select = document.getElementById('swap_team_in_id');
            const radioLocal = document.getElementById('radio_local');
            const radioAway = document.getElementById('radio_away');
            
            let activeRadio = null;
            if (radioLocal.checked) {
                activeRadio = radioLocal;
                selectedTeamOutId = currentLocalId;
            }
            else if (radioAway.checked) {
                activeRadio = radioAway;
                selectedTeamOutId = currentAwayId;
            }

            // Reseteamos el select
            select.innerHTML = '<option value="">-- Selecciona un equipo compatible --</option>';

            if (!activeRadio) {
                const feedbackMsg = document.getElementById('swap_feedback_msg');
                if(feedbackMsg) feedbackMsg.classList.add('hidden');
                return;
            }

            // --- CLAVE: Convertimos todo a minúsculas para comparar ---
            const targetGroup = (activeRadio.dataset.group || '').trim().toLowerCase();

            console.log("Buscando grupo:", targetGroup); // Depuración

            const feedbackMsg = document.getElementById('swap_feedback_msg');
            const filterDetails = document.getElementById('swap_filter_details');
            if(feedbackMsg && filterDetails) {
                feedbackMsg.classList.remove('hidden');
                // Mostramos en mayúscula al usuario para que se vea bonito
                filterDetails.innerText = activeRadio.dataset.group; 
            }

            const compatibleTeams = allTeamsData.filter(team => {
                // --- CLAVE: Convertimos el dato del equipo a minúsculas ---
                const tGroup = (team.group_name || '').trim().toLowerCase();
                
                const isGroupMatch = tGroup === targetGroup;
                const isNotSameTeam = team.id !== selectedTeamOutId;
                
                // --- NUEVO: Excluir equipos que ya jugaron (has_played = true) ---
                const hasNotPlayed = (team.has_played !== true);

                return isGroupMatch && isNotSameTeam && hasNotPlayed;
            });

            if (compatibleTeams.length === 0) {
                const option = document.createElement('option');
                option.text = "-- No hay coincidencias --";
                option.disabled = true;
                select.appendChild(option);
            } else {
                compatibleTeams.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.id;
                    // Aquí mostramos el nombre original (con mayúsculas correctas) al usuario
                    option.text = `${team.name} (${team.group_name || '-'})`;
                    select.appendChild(option);
                });
            }
        }

        async function confirmGlobalSwap() {
            const teamInId = document.getElementById('swap_team_in_id').value;
            
            const radioLocal = document.getElementById('radio_local');
            const radioAway = document.getElementById('radio_away');

            if (radioLocal.checked) {
                selectedTeamOutId = currentLocalId;
            } else if (radioAway.checked) {
                selectedTeamOutId = currentAwayId;
            } else {
                alert('Por favor selecciona qué equipo (Local o Visitante) es el que falta.');
                return;
            }

            if (!teamInId) {
                alert('Por favor selecciona qué equipo entrará al torneo.');
                return;
            }

            if (selectedTeamOutId == teamInId) {
                alert('No puedes reemplazar un equipo por sí mismo.');
                return;
            }

            const teamOutName = (selectedTeamOutId == currentLocalId) 
                                ? document.getElementById('label_local_name').innerText 
                                : document.getElementById('label_away_name').innerText;

            if(!confirm(`¿Estás seguro?\n\nVas a reemplazar a "${teamOutName}" por el equipo seleccionado en TODOS los partidos del torneo.`)) {
                return;
            }

            const btn = document.querySelector('#globalSwapModal button[onclick="confirmGlobalSwap()"]');
            const originalText = btn.innerText;
            btn.innerText = 'Procesando...';
            btn.disabled = true;

            try {
                const url = "{{ route('tournaments.swap-global', $tournament) }}";

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        team_out_id: selectedTeamOutId,
                        team_in_id: teamInId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('¡Éxito! ' + data.message);
                    window.location.reload(); 
                } else {
                    alert('Error: ' + data.message);
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error de conexión.');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
        // Conectamos los botones de radio con la función de filtrado
        if (document.getElementById('radio_local')) {
            document.getElementById('radio_local').addEventListener('change', filterTeamDropdown);
        }
        if (document.getElementById('radio_away')) {
            document.getElementById('radio_away').addEventListener('change', filterTeamDropdown);
        }

        // ==========================================
        // FUNCIONES DE PARTIDOS MANUALES
        // ==========================================
        const tournamentGameDuration = {{ $gameDurationMinutes ?? 40 }};
        let courtOccupancyCache = {};
        let currentOccupancyList = [];

        async function loadCourtOccupancy(date) {
            if (!date) return;
            if (courtOccupancyCache[date]) {
                currentOccupancyList = courtOccupancyCache[date];
                updateCourtAvailability();
                return;
            }

            try {
                const url = `{{ route('tournaments.courtOccupancy', $tournament->id) }}?date=${date}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (res.ok) {
                    const data = await res.json();
                    courtOccupancyCache[date] = data;
                    currentOccupancyList = data;
                    updateCourtAvailability();
                }
            } catch (err) {
                console.error('Error al cargar disponibilidad de canchas:', err);
            }
        }

        function onManualGameDateChanged() {
            const dateInput = document.getElementById('manualGameDate');
            loadCourtOccupancy(dateInput.value);
        }

        function updateCourtAvailability() {
            const dateVal = document.getElementById('manualGameDate').value;
            const timeVal = document.getElementById('manualGameTime').value;
            const courtSelect = document.getElementById('manualGameCourtId');
            const noticeBox = document.getElementById('courtConflictNotice');

            if (!dateVal || !timeVal) {
                if (noticeBox) noticeBox.classList.add('hidden');
                return;
            }

            // Convertir hora seleccionada a minutos desde medianoche
            const [pHour, pMin] = timeVal.split(':').map(Number);
            const proposedStartMins = pHour * 60 + pMin;
            const proposedEndMins = proposedStartMins + tournamentGameDuration;

            const endHours = Math.floor(proposedEndMins / 60) % 24;
            const endMins = proposedEndMins % 60;
            const formattedProposedEnd = String(endHours).padStart(2, '0') + ':' + String(endMins).padStart(2, '0');

            let currentlySelectedCourtDisabled = false;
            let conflictingMatchInfo = '';
            let availableCourtsCount = 0;
            const courtOptions = Array.from(courtSelect.options).filter(opt => opt.value !== "");

            courtOptions.forEach(opt => {
                const courtId = parseInt(opt.value);
                const originalName = opt.getAttribute('data-original-name') || opt.textContent;

                // Buscar conflictos de horario en esta cancha
                const conflict = currentOccupancyList.find(game => {
                    if (parseInt(game.court_id) !== courtId) return false;

                    const [gStartH, gStartM] = game.start.split(':').map(Number);
                    const [gEndH, gEndM] = game.end.split(':').map(Number);
                    const gameStartMins = gStartH * 60 + gStartM;
                    let gameEndMins = gEndH * 60 + gEndM;
                    if (gameEndMins <= gameStartMins) gameEndMins += 1440;

                    // Solapamiento de intervalos
                    return (proposedStartMins < gameEndMins && proposedEndMins > gameStartMins);
                });

                if (conflict) {
                    opt.disabled = true;
                    opt.textContent = `${originalName} (Ocupada: ${conflict.start} - ${conflict.end})`;
                    opt.classList.add('text-red-500');

                    if (courtSelect.value == courtId) {
                        currentlySelectedCourtDisabled = true;
                        conflictingMatchInfo = `${conflict.local_team} vs ${conflict.away_team} (${conflict.start} - ${conflict.end})`;
                    }
                } else {
                    opt.disabled = false;
                    opt.textContent = originalName;
                    opt.classList.remove('text-red-500');
                    availableCourtsCount++;
                }
            });

            if (currentlySelectedCourtDisabled) {
                courtSelect.value = '';
                noticeBox.innerHTML = `⚠️ <b>Cancha no disponible:</b> El partido dura <b>${tournamentGameDuration} min</b> (${timeVal} - ${formattedProposedEnd}) y la cancha seleccionada está ocupada por: ${conflictingMatchInfo}. Por favor selecciona otra cancha.`;
                noticeBox.classList.remove('hidden');
            } else if (availableCourtsCount === 0 && courtOptions.length > 0) {
                noticeBox.innerHTML = `⚠️ <b>Sin canchas disponibles:</b> Todas las canchas están ocupadas en el horario <b>${timeVal} - ${formattedProposedEnd}</b>. Por favor ajusta el horario o la fecha.`;
                noticeBox.classList.remove('hidden');
            } else {
                noticeBox.classList.add('hidden');
            }
        }

        function onManualCourtSelected() {
            const courtSelect = document.getElementById('manualGameCourtId');
            const selectedOpt = courtSelect.options[courtSelect.selectedIndex];
            if (selectedOpt && selectedOpt.disabled) {
                courtSelect.value = '';
                updateCourtAvailability();
            }
        }

        function openAddManualGameModal() {
            courtOccupancyCache = {};
            document.getElementById('addManualGameForm').reset();
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('manualGameDate').value = today;
            document.getElementById('manualGameTime').value = '10:00';
            const noticeBox = document.getElementById('courtConflictNotice');
            if (noticeBox) noticeBox.classList.add('hidden');
            document.getElementById('addManualGameModal').classList.remove('hidden');
            loadCourtOccupancy(today);
        }

        function closeAddManualGameModal() {
            document.getElementById('addManualGameModal').classList.add('hidden');
        }

        function filterManualAwayTeams() {
            const localSelect = document.getElementById('manualGameLocalTeam');
            const awaySelect = document.getElementById('manualGameAwayTeam');
            const selectedLocalId = localSelect.value;

            Array.from(awaySelect.options).forEach(opt => {
                if (!opt.value) return;
                if (opt.value === selectedLocalId) {
                    opt.disabled = true;
                } else {
                    opt.disabled = false;
                }
            });

            if (awaySelect.value === selectedLocalId) {
                awaySelect.value = '';
            }
        }

        async function submitAddManualGame(event) {
            event.preventDefault();
            const form = event.target;
            const localId = document.getElementById('manualGameLocalTeam').value;
            const awayId = document.getElementById('manualGameAwayTeam').value;
            const courtSelect = document.getElementById('manualGameCourtId');
            const selectedCourtOpt = courtSelect.options[courtSelect.selectedIndex];

            if (!courtSelect.value || (selectedCourtOpt && selectedCourtOpt.disabled)) {
                alert('La cancha seleccionada no está disponible en este horario. Por favor selecciona otra cancha u horario.');
                return;
            }

            if (!localId || !awayId) {
                alert('Debes seleccionar tanto el equipo local como el visitante.');
                return;
            }

            if (localId === awayId) {
                alert('El equipo local y el visitante no pueden ser el mismo.');
                return;
            }

            const btn = document.getElementById('btnSubmitManualGame');
            const originalText = btn.innerText;
            btn.innerText = 'Guardando...';
            btn.disabled = true;

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('tournaments.manualGame.store', $tournament->id) }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    closeAddManualGameModal();
                    alert(data.message || 'Partido agregado exitosamente.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Ocurrió un error al guardar el partido.');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error al guardar partido:', error);
                alert('Error de conexión al registrar el partido.');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        async function deleteManualGame(gameId) {
            if (!confirm('¿Estás seguro de que deseas eliminar este partido del rol de juegos?')) {
                return;
            }

            try {
                const url = `/tournaments/{{ $tournament->id }}/games/${gameId}`;
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert(data.message || 'Partido eliminado exitosamente.');
                    window.location.reload();
                } else {
                    alert(data.message || 'No se pudo eliminar el partido.');
                }
            } catch (error) {
                console.error('Error al eliminar partido:', error);
                alert('Error de conexión al eliminar el partido.');
            }
        }

        async function sendTelegramMatchNotice(gameId) {
            if (!confirm('¿Deseas enviar/reenviar la notificación de este partido por Telegram a los entrenadores?')) {
                return;
            }

            try {
                const response = await fetch(`/games/${gameId}/notify-telegram`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                alert(data.message || (data.success ? 'Aviso enviado por Telegram.' : 'No se pudo enviar la notificación.'));
            } catch (error) {
                console.error('Error al notificar por Telegram:', error);
                alert('Error de conexión al enviar el aviso por Telegram.');
            }
        }

        let currentFinalScoreGameId = null;
        let currentFinalScoreLocalName = '';
        let currentFinalScoreAwayName = '';

        function openSetFinalScoreModal(gameId, localName, awayName, localScore = 0, awayScore = 0) {
            currentFinalScoreGameId = gameId;
            currentFinalScoreLocalName = localName;
            currentFinalScoreAwayName = awayName;

            document.getElementById('finalScoreGameId').value = gameId;
            document.getElementById('modalLocalTeamLabel').innerText = localName;
            document.getElementById('modalLocalTeamLabel').title = localName;
            document.getElementById('modalAwayTeamLabel').innerText = awayName;
            document.getElementById('modalAwayTeamLabel').title = awayName;

            document.getElementById('modalLocalScore').value = localScore ?? 0;
            document.getElementById('modalAwayScore').value = awayScore ?? 0;

            const titleEl = document.getElementById('modalFinalScoreTitle');
            if (titleEl) {
                titleEl.innerText = (localScore > 0 || awayScore > 0) ? 'Modificar Marcador Final' : 'Registrar Marcador Final';
            }

            calculateMatchOutcome();

            const modal = document.getElementById('setFinalScoreModal');
            modal.classList.remove('hidden');
        }

        function closeSetFinalScoreModal() {
            const modal = document.getElementById('setFinalScoreModal');
            modal.classList.add('hidden');
        }

        function calculateMatchOutcome() {
            const localScore = parseInt(document.getElementById('modalLocalScore').value, 10) || 0;
            const awayScore = parseInt(document.getElementById('modalAwayScore').value, 10) || 0;

            const card = document.getElementById('finalScoreOutcomeCard');
            const icon = document.getElementById('finalScoreOutcomeIcon');
            const title = document.getElementById('finalScoreOutcomeTitle');
            const desc = document.getElementById('finalScoreOutcomeDesc');
            const sub = document.getElementById('finalScoreOutcomeSub');

            const diff = Math.abs(localScore - awayScore);

            if (localScore > awayScore) {
                card.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4 transition-all duration-200';
                icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 bg-emerald-100 text-emerald-700';
                icon.innerHTML = '🏆';
                title.innerText = 'Victoria Local';
                title.className = 'text-xs font-bold uppercase tracking-wider text-emerald-800 mb-0.5';
                desc.innerHTML = `<span class="text-emerald-900 font-extrabold">${currentFinalScoreLocalName}</span> gana por <span class="text-emerald-700 font-black">+${diff} pts</span>`;
                sub.innerHTML = `Perdedor: <span class="font-medium text-gray-700">${currentFinalScoreAwayName}</span>`;
            } else if (awayScore > localScore) {
                card.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4 transition-all duration-200';
                icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 bg-emerald-100 text-emerald-700';
                icon.innerHTML = '🏆';
                title.innerText = 'Victoria Visitante';
                title.className = 'text-xs font-bold uppercase tracking-wider text-emerald-800 mb-0.5';
                desc.innerHTML = `<span class="text-emerald-900 font-extrabold">${currentFinalScoreAwayName}</span> gana por <span class="text-emerald-700 font-black">+${diff} pts</span>`;
                sub.innerHTML = `Perdedor: <span class="font-medium text-gray-700">${currentFinalScoreLocalName}</span>`;
            } else {
                card.className = 'rounded-xl border border-amber-200 bg-amber-50 p-4 transition-all duration-200';
                icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 bg-amber-100 text-amber-700';
                icon.innerHTML = '⚖️';
                title.innerText = 'Empate';
                title.className = 'text-xs font-bold uppercase tracking-wider text-amber-800 mb-0.5';
                desc.innerHTML = `Empate a <span class="font-black text-amber-900">${localScore}</span> puntos`;
                sub.innerHTML = `${currentFinalScoreLocalName} y ${currentFinalScoreAwayName} se reparten puntos de empate.`;
            }
        }

        async function submitSetFinalScore(event) {
            event.preventDefault();
            const gameId = document.getElementById('finalScoreGameId').value;
            const localScore = document.getElementById('modalLocalScore').value;
            const awayScore = document.getElementById('modalAwayScore').value;

            if (!gameId) {
                alert('ID de partido no válido.');
                return;
            }

            const btn = document.getElementById('btnSubmitFinalScore');
            const originalText = btn.innerText;
            btn.innerText = 'Guardando marcador...';
            btn.disabled = true;

            try {
                const url = `/games/${gameId}/set-final-score`;
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        local_team_score: localScore,
                        away_team_score: awayScore
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    closeSetFinalScoreModal();
                    alert(data.message || 'Marcador final guardado exitosamente.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al guardar el marcador final.');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error al guardar marcador final:', error);
                alert('Error de conexión al guardar el marcador final.');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
    </script>