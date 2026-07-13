<x-app-layout>

<style>
/* --- MODO CLARO FINAL --- */

.nba-bg {
    background-color: #f9fafb;
    color: #1f2937;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
}

.nba-card {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    position: relative;
}

.nba-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
}

.nba-header {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
    padding: 0.5rem;
    background-color: #f3f4f6;
    font-weight: 700;
    border-bottom: 1px solid #e5e7eb;
}

/* FILA DE EQUIPOS */
.nba-team-row {
    display: flex;
    align-items: center;
    padding: 0 12px;
    border-bottom: 1px solid #f3f4f6;
    height: 60px;
    position: relative;
    overflow: hidden;
}
.nba-team-row:last-child { border-bottom: none; }

/* LOGO GRANDE Y CORTADO (IZQUIERDA) - REDUCIDO 20% */
.nba-team-logo {
    position: absolute;
    top: 50%;
    left: -10px;
    transform: translateY(-50%);
    height: 104px;
    width: auto;
    opacity: 0.15;
    z-index: 0;
    object-fit: contain;
}

/* MARCADOR BASE (DERECHA) */
.nba-team-score {
    position: relative;
    z-index: 10;
    margin-left: auto;
    font-family: 'Arial', sans-serif;
    line-height: 1;
}

/* =========================================
ESTILO GANADOR
========================================= */
.nba-winner {
    background-color: transparent;
}

.nba-winner .nba-team-score {
    color: #059669;
    font-weight: 900;
    font-size: 1.8rem;
    text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
}

/* =========================================
ESTILO PERDEDOR
========================================= */
.nba-loser::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 1;
    background-color: rgba(75, 85, 99, 0.5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

.nba-loser .nba-team-score {
    color: #d1d5db;
    font-weight: 700;
    font-size: 1.4rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.6);
}

/* ESTADOS (Ocultos) */
.nba-status-badge { display: none; }

/* =========================================
TARJETA CAMPEÓN
========================================= */
.nba-champion-card {
    background-color: #ffffff;
    border: 1px solid #d4af37;
    border-radius: 12px;
    padding: 2rem 1rem;
    text-align: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
    position: relative;
    overflow: hidden;
    min-height: 150px;
}

.champion-bg-logo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    height: 84%;
    width: auto;
    max-width: 90%;
    opacity: 0.15;
    z-index: 0;
    object-fit: contain;
    filter: grayscale(20%);
}

.champion-content {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.champion-label {
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: #d97706;
    font-weight: 900;
    -webkit-text-stroke: 1px #92400e;
    text-shadow: 2px 2px 0px rgba(0,0,0,0.1);
}

.champion-name {
    font-size: 1.8rem;
    font-weight: 900;
    text-transform: uppercase;
    color: #b45309;
    line-height: 1;
    -webkit-text-stroke: 1.5px #78350f;
    text-shadow: 3px 3px 4px rgba(255,255,255,0.8);
}

.trophy-icon {
    font-size: 4rem;
    filter: drop-shadow(0 5px 5px rgba(0,0,0,0.2));
    animation: float 3s ease-in-out infinite;
    margin-bottom: 0.5rem;
}

@keyframes float {
    0% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(2deg); }
    100% { transform: translateY(0px) rotate(0deg); }
}

/* Scrollbar Unificado */
.nba-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
.nba-scroll::-webkit-scrollbar-track { background: #f3f4f6; }
.nba-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
.nba-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

/* NOMBRE DEL EQUIPO CENTRADO */
.nba-team-name {
    position: relative;
    z-index: 10;
    flex-grow: 1;
    text-align: center;
    padding-left: 75px;  /* Mueve el texto a la derecha para evitar el logo grande */
    padding-right: 30px; /* Espacio para el marcador */
    font-weight: 700;
    font-size: 0.85rem;
    color: #374151; /* Gris oscuro */
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; /* Puntos suspensivos si el nombre es muy largo */
    letter-spacing: 0.5px;
}

</style>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <!-- 1. CABEZERA (VOLVER + BOTONES) -->
                        <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                            <a href="{{ route('tournaments.index') }}" class="w-full md:w-auto shrink-0 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center justify-center">
                                ← Volver a Torneos
                            </a>
                        </div>

                        <!-- LOOP PRINCIPAL DE GRUPOS / ETAPAS -->
                        @foreach($standingsData as $groupName => $data)
                            @if($groupName === 'mode') @continue @endif
                            <div class="mb-10 bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                                
                            <!-- ================================================================= -->
                            <!--        CASO NUEVO (DISEÑO EN CASCADA): DOBLE ELIMINATORIA         -->
                            <!-- ================================================================= -->
                            @if(isset($data['mode']) && $data['mode'] === 'double_elimination_grouped')
                                
                                <!-- BLOQUE PHP PARA CALCULAR EQUIPOS -->
                                @php
                                    $totalEquipos = 0;
                                    // Verificamos si existe el bracket de ganadores y la primera ronda
                                    if (isset($data['bracket']['winner_bracket']) && isset($data['bracket']['winner_bracket'][0])) {
                                        // El número de equipos es igual a (partidos en ronda 1) * 2
                                        $totalEquipos = count($data['bracket']['winner_bracket'][0]) * 2;
                                    }
                                @endphp

                                <!-- 1. NUEVO ENCABEZADO (Estilo Liga Estándar) -->
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 border-b pb-2 border-gray-300 gap-4">
                                    
                                    <!-- IZQUIERDA: Título del Grupo -->
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            <span class="text-gray-400 text-base font-normal mr-2">Grupo:</span>{{ $groupName }}
                                        </h3>
                                        <!-- Mostramos la variable calculada -->
                                        <span class="text-sm text-gray-500">
                                            {{ $totalEquipos }} Equipos
                                        </span>
                                    </div>

                                    <!-- DERECHA: Icono de Calendario -->
                                    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                                        <a href="{{ route('tournaments.schedule', ['tournament' => $tournament, 'group' => $groupName]) }}" 
                                        class="text-orange-500 hover:text-orange-700 hover:bg-orange-50 rounded-full p-1.5 transition-all duration-200 shrink-0"
                                        title="Ver calendario de este grupo">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- 2. CONTENEDOR ENCAPSULADO CON SCROLL VERTICAL -->
                                <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner">
                                    
                                    <!-- Título Interno -->
                                    <div class="mb-6 border-b border-gray-200 pb-3 flex justify-center items-center">
                                        <h4 class="text-xl font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                            <i class="fa-solid fa-trophy text-gray-600"></i> FASE DOBLE ELIMINATORIA
                                        </h4>
                                    </div>

                                    <!-- Área de Scroll Vertical -->
                                    <div class="overflow-y-auto max-h-[800px] nba-scroll pr-2">
                                        
                                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start justify-center min-w-full">
                                            
                                            <!-- ======================= COLUMNA IZQUIERDA: WINNER BRACKET ======================= -->
                                            <div class="w-full lg:w-1/2 flex flex-col gap-8 border-r-0 lg:border-r-2 border-dashed border-gray-300 pr-0 lg:pr-8">
                                                <h4 class="text-center text-lg font-black text-blue-900 uppercase tracking-widest bg-blue-50 py-2 rounded-full border border-blue-200 shadow-sm">
                                                    Bracket Ganadores
                                                </h4>

                                                @foreach($data['bracket']['winner_bracket'] as $roundIndex => $games)
                                                    <div class="w-full">
                                                        @if($loop->first)
                                                            <div class="flex justify-between items-center mb-2 px-2">
                                                                <span class="text-xs font-bold text-gray-400 uppercase">Ronda {{ $roundIndex + 1 }}</span>
                                                                <div class="h-px bg-gray-200 flex-1 ml-2"></div>
                                                            </div>
                                                        @else
                                                            <div class="flex justify-center mb-4">
                                                                <div class="h-6 border-l-4 border-blue-300"></div>
                                                            </div>
                                                        @endif

                                                        <div class="flex flex-wrap justify-center gap-4">
                                                            @foreach($games as $game)
                                                                <div class="nba-card w-full max-w-xs">
                                                                    <!-- Local -->
                                                                    <div @class([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->local_team_score > $game->away_team_score,
                                                                        'nba-loser' => $game->away_team_score > $game->local_team_score
                                                                    ])>
                                                                        <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name">{{ $game->localTeam->name }}</span>
                                                                        <span class="nba-team-score">{{ $game->local_team_score ?? '-' }}</span>
                                                                    </div>
                                                                    <!-- Visitante -->
                                                                    <div @class([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                        'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                    ])>
                                                                        <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name">{{ $game->awayTeam->name }}</span>
                                                                        <span class="nba-team-score">{{ $game->away_team_score ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- ======================= COLUMNA DERECHA: LOSER BRACKET ======================= -->
                                            <div class="w-full lg:w-1/2 flex flex-col gap-8 pl-0 lg:pl-8 mt-8 lg:mt-0">
                                                <h4 class="text-center text-lg font-black text-red-900 uppercase tracking-widest bg-red-50 py-2 rounded-full border border-red-200 shadow-sm">
                                                    Bracket Perdedores
                                                </h4>

                                                @foreach($data['bracket']['loser_bracket'] as $roundIndex => $games)
                                                    <div class="w-full">
                                                        @if($loop->first)
                                                            <div class="flex justify-between items-center mb-2 px-2">
                                                                <span class="text-xs font-bold text-gray-400 uppercase">Ronda {{ $roundIndex + 1 }}</span>
                                                                <div class="h-px bg-gray-200 flex-1 ml-2"></div>
                                                            </div>
                                                        @else
                                                            <div class="flex justify-center mb-4">
                                                                <div class="h-6 border-l-4 border-red-300"></div>
                                                            </div>
                                                        @endif

                                                        <div class="flex flex-wrap justify-center gap-4">
                                                            @foreach($games as $game)
                                                                <div class="nba-card w-full max-w-xs">
                                                                    <!-- Local -->
                                                                    <div @class([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->local_team_score > $game->away_team_score,
                                                                        'nba-loser' => $game->away_team_score > $game->local_team_score
                                                                    ])>
                                                                        <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name">{{ $game->localTeam->name }}</span>
                                                                        <span class="nba-team-score">{{ $game->local_team_score ?? '-' }}</span>
                                                                    </div>
                                                                    <!-- Visitante -->
                                                                    <div @class([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                        'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                    ])>
                                                                        <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name">{{ $game->awayTeam->name }}</span>
                                                                        <span class="nba-team-score">{{ $game->away_team_score ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- ======================= SECCIÓN FINAL: GRAN FINAL (Dentro del scroll) ======================= -->
                                        <div class="mt-12 pt-8 border-t-2 border-gray-200 w-full max-w-3xl mx-auto">
                                            <div class="flex flex-col items-center justify-center gap-10">
                                                
                                                <!-- 1. GRAN FINAL -->
                                                @if(isset($data['bracket']['grand_final']))
                                                    @php $gf = $data['bracket']['grand_final']; $wfLocal = $gf->local_team_score > $gf->away_team_score; @endphp
                                                    
                                                    <div class="w-full max-w-md relative z-10">
                                                        <div class="text-center mb-4 relative">
                                                            <div class="absolute inset-0 bg-gray-800 opacity-10 rounded-lg"></div>
                                                            <span class="relative inline-block px-6 py-2 rounded-full text-sm font-black uppercase tracking-widest bg-gray-900 text-white shadow-lg border-b-4 border-yellow-500">
                                                                🏆 Gran Final
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="nba-card border-2 border-gray-900 shadow-2xl transform transition hover:scale-[1.02] bg-white relative overflow-hidden">
                                                            @if($gf->status === 'finished')
                                                                <div class="absolute inset-0 bg-gradient-to-t from-yellow-50 to-transparent opacity-50 z-0 pointer-events-none"></div>
                                                            @endif

                                                            <div class="bg-gray-100 py-1 text-center">
                                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Partido Definitorio</span>
                                                            </div>

                                                            <!-- LOCAL -->
                                                            <div @class([
                                                                'nba-team-row relative z-10',
                                                                'nba-winner' => $wfLocal,
                                                                'nba-loser' => !$wfLocal
                                                            ])>
                                                                <img src="{{ asset('storage/' . $gf->localTeam->image_path ?? '') }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                <span class="nba-team-name">{{ $gf->localTeam->name }}</span>
                                                                <span class="nba-team-score">{{ $gf->local_team_score ?? '-' }}</span>
                                                            </div>

                                                            <!-- VISITANTE -->
                                                            <div @class([
                                                                'nba-team-row relative z-10',
                                                                'nba-winner' => !$wfLocal,
                                                                'nba-loser' => $wfLocal
                                                            ])>
                                                                <img src="{{ asset('storage/' . $gf->awayTeam->image_path ?? '') }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                <span class="nba-team-name">{{ $gf->awayTeam->name }}</span>
                                                                <span class="nba-team-score">{{ $gf->away_team_score ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- 2. PARTIDO DE REVANCHA -->
                                                @if(isset($data['bracket']['reset_game']))
                                                    @php $g = $data['bracket']['reset_game']; $wLocal = $g->local_team_score > $g->away_team_score; @endphp
                                                    
                                                    <div class="w-full max-w-md">
                                                        <div class="text-center mb-3">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                Si es necesario (Desempate)
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="nba-card border-2 border-yellow-400 shadow-md transform transition hover:scale-[1.01]">
                                                            <div class="nba-header bg-yellow-50 text-yellow-700 font-bold uppercase text-xs border-b border-yellow-100">
                                                                Partido de Revancha
                                                            </div>
                                                            
                                                            <!-- LOCAL -->
                                                            <div @class([
                                                                'nba-team-row',
                                                                'nba-winner' => $wLocal,
                                                                'nba-loser' => !$wLocal
                                                            ])>
                                                                <img src="{{ asset('storage/' . $g->localTeam->image_path ?? '') }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                <span class="nba-team-name">{{ $g->localTeam->name }}</span>
                                                                <span class="nba-team-score">{{ $g->local_team_score ?? '-' }}</span>
                                                            </div>
                                                            
                                                            <!-- VISITANTE -->
                                                            <div @class([
                                                                'nba-team-row',
                                                                'nba-winner' => !$wLocal,
                                                                'nba-loser' => $wLocal
                                                            ])>
                                                                <img src="{{ asset('storage/' . $g->awayTeam->image_path ?? '') }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                <span class="nba-team-name">{{ $g->awayTeam->name }}</span>
                                                                <span class="nba-team-score">{{ $g->away_team_score ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(!isset($data['bracket']['grand_final']) && !isset($data['bracket']['reset_game']))
                                                    <div class="w-full text-center py-12 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                                                        <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Esperando Finalistas</p>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- ================================================================= -->
                                <!-- FIN BLOQUE DOBLE ELIMINATORIA -->
                                <!-- ================================================================= -->

                                @else
                                <!-- ================================================================= -->
                                <!-- CASO ESTÁNDAR (LIGA O ELIMINACIÓN SIMPLE) -->
                                <!-- ================================================================= -->

                                <!-- LÓGICA ACTUALIZADA: PRIORIDAD A 'team_ids' -->
                                @php
                                    $equiposHeader = 0;
                                    
                                    // 1. Intentar contar usando 'team_ids' (El array que se pasa al modal de playoffs)
                                    if (isset($data['team_ids']) && is_countable($data['team_ids'])) {
                                        $equiposHeader = count($data['team_ids']);
                                    }
                                    // 2. Si no, intentar usar 'teams' (Lista de objetos)
                                    elseif (isset($data['teams']) && is_countable($data['teams'])) {
                                        $equiposHeader = count($data['teams']);
                                    }
                                    // 3. Si no, intentar usar 'standings' (Tabla de posiciones)
                                    elseif (isset($data['standings']) && is_countable($data['standings'])) {
                                        $equiposHeader = count($data['standings']);
                                    }
                                    // 4. Último recurso: Contar IDs únicos en los partidos (Para brackets irregulares como el de 5)
                                    elseif (isset($data['playoff_rounds'])) {
                                        $idsEncontrados = [];
                                        foreach($data['playoff_rounds'] as $ronda) {
                                            if(isset($ronda['games'])) {
                                                foreach($ronda['games'] as $game) {
                                                    // Verificamos si es objeto o array
                                                    $localId = is_object($game) ? ($game->local_team_id ?? null) : ($game['local_team_id'] ?? null);
                                                    $awayId = is_object($game) ? ($game->away_team_id ?? null) : ($game['away_team_id'] ?? null);

                                                    if($localId) $idsEncontrados[] = $localId;
                                                    if($awayId) $idsEncontrados[] = $awayId;
                                                }
                                            }
                                        }
                                        $equiposHeader = count(array_unique($idsEncontrados));
                                    }
                                @endphp

                                <!-- CABECERA DEL GRUPO -->
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 border-b pb-2 border-gray-300 gap-4">
                                    
                                    <!-- IZQUIERDA: Título del Grupo -->
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            <span class="text-gray-400 text-base font-normal mr-2">Grupo:</span>{{ $groupName }}
                                        </h3>
                                        <span class="text-sm text-gray-500">
                                            {{ $equiposHeader }} Equipos
                                        </span>
                                    </div>

                                    <!-- DERECHA: Botones de Acción + Icono de Calendario -->
                                    <div class="flex items-center gap-2 w-full md:w-auto">
                                        
                                        @if( (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin')) && isset($data['is_finished']) && $data['is_finished'] && isset($data['has_playoffs']) && !$data['has_playoffs'] )
                                            
                                            <!-- Botón Vuelta -->
                                            <button onclick="openRoundModal('{{ $groupName }}', {{ json_encode($data['team_ids'] ?? []) }})" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                                {{ $data['round_ordinal'] ?? '' }} Vuelta
                                            </button>

                                            <!-- Botón Playoffs -->
                                            <button onclick="openEliminationModal('{{ $groupName }}', {{ json_encode($data['team_ids'] ?? []) }})" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                                </svg>
                                                Playoffs
                                            </button>
                                        @endif

                                        <!-- ICONO CALENDARIO -->
                                        <a href="{{ route('tournaments.schedule', ['tournament' => $tournament, 'group' => $groupName]) }}" 
                                        class="text-orange-500 hover:text-orange-700 hover:bg-orange-50 rounded-full p-1.5 transition-all duration-200 shrink-0"
                                        title="Ver calendario de este grupo">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </a>

                                    </div>
                                </div>

                                <!-- ================================================================= -->
                                <!-- VISUALIZACIÓN DE PLAYOFFS (FINAL) -->
                                <!-- ================================================================= -->
                                @if(isset($data['has_playoffs']) && $data['has_playoffs'])
                                <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner">

                                    <!-- 1. TÍTULO -->
                                    <div class="mb-6 border-b border-gray-200 pb-3 flex justify-center items-center">
                                        <h4 class="text-xl font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                            <i class="fa-solid fa-trophy text-gray-600"></i> Fase Eliminatoria
                                        </h4>
                                    </div>

                                    <div class="flex flex-row gap-6 overflow-x-auto pb-6 nba-scroll snap-x rounds-flex items-center">
                                        @foreach($data['playoff_rounds'] ?? [] as $round)
                                        <div class="flex-shrink-0 w-full md:w-72 snap-center flex flex-col gap-4">

                                            <!-- Título de la Ronda -->
                                            <div class="nba-header rounded">
                                                {{ $round['name'] ?? 'Ronda' }}
                                            </div>

                                            <!-- Lista de Partidos -->
                                            @foreach($round['games'] ?? [] as $game)
                                            <div class="nba-card">

                                                <!-- FILA LOCAL -->
                                                <div @class([
                                                    'nba-team-row',
                                                    'nba-winner' => isset($game->local_team_score) && isset($game->away_team_score) && $game->local_team_score > $game->away_team_score,
                                                    'nba-loser' => isset($game->local_team_score) && isset($game->away_team_score) && $game->away_team_score > $game->local_team_score
                                                ])>

                                                    <!-- LOGO -->
                                                    @if(isset($game->localTeam) && $game->localTeam->image_path)
                                                    <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                    @endif

                                                    <!-- NOMBRE DEL EQUIPO -->
                                                    @if(isset($game->localTeam))
                                                        <span class="nba-team-name">{{ $game->localTeam->name }}</span>
                                                    @else
                                                        <span class="nba-team-name text-gray-400 italic">Pendiente</span>
                                                    @endif

                                                    <!-- MARCADOR -->
                                                    <span class="nba-team-score">{{ $game->local_team_score ?? '-' }}</span>
                                                </div>

                                                <!-- FILA VISITANTE -->
                                                <div @class([
                                                    'nba-team-row',
                                                    'nba-winner' => isset($game->local_team_score) && isset($game->away_team_score) && $game->away_team_score > $game->local_team_score,
                                                    'nba-loser' => isset($game->local_team_score) && isset($game->away_team_score) && $game->local_team_score > $game->away_team_score
                                                ])>

                                                    <!-- LOGO -->
                                                    @if(isset($game->awayTeam) && $game->awayTeam->image_path)
                                                    <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                    @endif

                                                    <!-- NOMBRE DEL EQUIPO -->
                                                    @if(isset($game->awayTeam))
                                                        <span class="nba-team-name">{{ $game->awayTeam->name }}</span>
                                                    @else
                                                        <span class="nba-team-name text-gray-400 italic">Pendiente</span>
                                                    @endif

                                                    <!-- MARCADOR -->
                                                    <span class="nba-team-score">{{ $game->away_team_score ?? '-' }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach

                                        <!-- CAMPEÓN -->
                                        @if(isset($data['playoff_champion']))
                                        <div class="flex-shrink-0 w-full md:w-64 snap-center">
                                            <div class="nba-champion-card">
                                                @if(isset($data['playoff_champion_logo']))
                                                <img src="{{ asset('storage/' . $data['playoff_champion_logo']) }}" class="champion-bg-logo" alt="logo campeon" onerror="this.style.display='none'">
                                                @else
                                                <img src="{{ asset('storage/' . ($game->awayTeam->image_path ?? '')) }}" class="champion-bg-logo" alt="logo campeon" onerror="this.style.display='none'">
                                                @endif
                                                <div class="champion-content">
                                                    <div class="champion-label">Campeón</div>
                                                    <div class="champion-name">
                                                        {{ $data['playoff_champion'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="flex-shrink-0 w-full md:w-64 snap-center">
                                            <div class="flex items-center justify-center h-full border border-dashed border-gray-300 rounded-lg min-h-[150px]">
                                                <span class="text-gray-400 text-sm uppercase tracking-widest">Pendiente</span>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                                @endif
                                <!-- ================================================================= -->

                                <!-- Tabla de Posiciones (Regular) -->
                                @if(isset($data['standings']) && count($data['standings']) > 0)
                                <div class="overflow-x-auto mt-6">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">#</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipo</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">PJ</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">G</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">E</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">P</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-900 uppercase bg-emerald-50">PTS</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @php $pos = 1; @endphp
                                            @foreach($data['standings'] as $tid => $stats)
                                            <tr>
                                                <td scope="row" class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-gray-900">{{ $pos++ }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <div class="flex items-center">
                                                        @if(isset($data['teams'][$tid]))
                                                        @if($data['teams'][$tid]->image_path)
                                                        <img src="{{ asset('storage/' . $data['teams'][$tid]->image_path) }}"
                                                            alt="{{ $data['teams'][$tid]->name }}"
                                                            class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200" onerror="this.style.display='none'">
                                                        @else
                                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold mr-3 border border-gray-200">
                                                            {{ substr($data['teams'][$tid]->name, 0, 1) }}
                                                        </div>
                                                        @endif
                                                        <span>{{ $data['teams'][$tid]->name }}</span>
                                                        @else
                                                        <span class="text-gray-400 italic">Equipo Eliminado (ID: {{ $tid }})</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-500">{{ $stats['played'] ?? 0 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-green-600 font-bold">{{ $stats['won'] ?? 0 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-yellow-600 font-bold">{{ $stats['drawn'] ?? 0 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-red-600 font-bold">{{ $stats['lost'] ?? 0 }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold bg-emerald-50">{{ $stats['points'] ?? 0 }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                @endif

                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        
</x-app-layout>

    <!-- ================================================================= -->
    <!-- MODALES Y SCRIPTS -->
    <!-- ================================================================= -->

    <!-- Modal para Nueva Vuelta -->
    <div id="roundModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="roundForm" onsubmit="submitRound(event)">
                        @csrf
                        <input type="hidden" name="team_ids" id="round_team_ids" value="">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Iniciar 2da Vuelta</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Se generarán los partidos de revancha para este grupo.</p>
                                        <div class="mt-4 grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                                                <input type="date" name="start_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                                                <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 sm:ml-3 sm:w-auto">Generar</button>
                            <button type="button" onclick="closeRoundModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Eliminatoria -->
    <div id="eliminationModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="eliminationForm" onsubmit="submitElimination(event)">
                        @csrf
                        <input type="hidden" name="team_ids" id="elimination_team_ids" value="">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Fase Eliminatoria</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-2">Se generarán los cruces de playoffs para este grupo.</p>
                                        
                                        <div class="mt-4 grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Cantidad de Equipos (4, 8, 16...)</label>
                                                <input type="number" name="teams_count" min="2" max="32" step="2" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej: 4, 8, 16">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                                                <input type="date" name="start_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                                                <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto">Generar Cruces</button>
                            <button type="button" onclick="closeEliminationModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Lógica Vuelta ---
        function openRoundModal(groupName, teamIds) {
            document.getElementById('roundForm').reset();
            
            const hiddenInput = document.getElementById('round_team_ids');
            if (teamIds) {
                hiddenInput.value = JSON.stringify(teamIds);
            } else {
                hiddenInput.value = '';
            }
            
            document.getElementById('roundModal').classList.remove('hidden');
        }

        function closeRoundModal() {
            document.getElementById('roundModal').classList.add('hidden');
        }

        async function submitRound(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const url = '{{ route("tournaments.secondRound", $tournament) }}';

            try {
                const response = await fetch(url, {
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
                    alert(data.message || 'Error.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            }
        }

        // --- Lógica Eliminatoria ---
        function openEliminationModal(groupName, teamIds) {
            document.getElementById('eliminationForm').reset();
            
            const hiddenInput = document.getElementById('elimination_team_ids');
            if (hiddenInput) {
                 hiddenInput.value = teamIds ? JSON.stringify(teamIds) : '';
            }

            document.getElementById('eliminationModal').classList.remove('hidden');
        }

        function closeEliminationModal() {
            document.getElementById('eliminationModal').classList.add('hidden');
        }

        async function submitElimination(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            const teamsCount = parseInt(formData.get('teams_count'));
            if ((teamsCount & (teamsCount - 1)) !== 0) {
                alert('El número de equipos debe ser una potencia de 2 (Ej: 2, 4, 8, 16).');
                return;
            }

            const url = '{{ route("tournaments.elimination", $tournament) }}';

            try {
                const response = await fetch(url, {
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
                    alert(data.message || 'Error al generar eliminatoria.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            }
        }
    </script>