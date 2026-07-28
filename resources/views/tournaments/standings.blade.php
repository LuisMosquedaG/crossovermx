<x-app-layout>

<style>
@import url('https://fonts.googleapis.com/css2?family=Graduate&display=swap');

.nba-bracket-title {
    font-family: 'Graduate', 'Courier New', monospace, serif;
    font-size: 2.2rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    text-align: center;
    padding: 0.6rem 1.5rem;
    line-height: 1.2;
    border-radius: 9999px;
    width: 100%;
}

.nba-bracket-title-winners {
    color: #2563eb;
    background-color: rgba(37, 99, 235, 0.06);
    border: 1px solid rgba(37, 99, 235, 0.15);
}

.nba-bracket-title-losers {
    color: #dc2626;
    background-color: rgba(220, 38, 38, 0.06);
    border: 1px solid rgba(220, 38, 38, 0.15);
}

.nba-bracket-title-final {
    color: #eab308;
    background-color: rgba(234, 179, 8, 0.06);
    border: 1px solid rgba(234, 179, 8, 0.15);
}

/* --- MODO CLARO FINAL --- */

.nba-bg {
    background-color: #f9fafb;
    color: #1f2937;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
}

.nba-card {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    position: relative;
}

.nba-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.12);
    border-color: #cbd5e1;
}

.nba-header {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.75rem;
    color: #64748b;
    text-align: center;
    padding: 0.5rem;
    background-color: #f8fafc;
    font-weight: 700;
    border-bottom: 1px solid #e2e8f0;
}

/* FILA DE EQUIPOS */
.nba-team-row {
    display: flex;
    align-items: center;
    padding: 0 16px;
    border-bottom: 1px solid #f1f5f9;
    height: 56px;
    position: relative;
    overflow: hidden;
    background-color: #ffffff;
}
.nba-team-row:last-child { border-bottom: none; }

/* LOGO GRANDE Y CORTADO (IZQUIERDA) */
.nba-team-logo {
    position: absolute;
    top: 50%;
    left: -8px;
    transform: translateY(-50%);
    height: 90px;
    width: auto;
    opacity: 0.08;
    z-index: 0;
    object-fit: contain;
    pointer-events: none;
    transition: all 0.3s ease;
}

/* MARCADOR BASE (DERECHA) - GRANDE Y DESVANECIDO */
.nba-team-score {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-family: 'Outfit', 'Inter', 'Arial Black', sans-serif;
    line-height: 1;
    user-select: none;
    pointer-events: none;
    transition: all 0.3s ease;
}

/* Marcador con puntos */
.nba-score-numeric {
    font-size: 3.4rem;
    font-weight: 700;
    z-index: 1;
    font-family: 'Graduate', 'Courier New', monospace, serif;
    transform: translateY(-50%) scaleY(1.4) scaleX(0.95); /* Stretches vertically and keeps them thicker */
    transform-origin: right center;
    color: rgba(148, 163, 184, 0.12); /* Default light grey for pending/playing games */
}

/* Marcador vacío (guión) */
.nba-score-empty {
    font-size: 1.1rem;
    font-weight: 700;
    color: #94a3b8;
    opacity: 0.6;
    right: 18px;
    z-index: 10;
}

/* =========================================
ESTILO GANADOR
========================================= */
.nba-winner {
    background-color: #f8fafc;
}

.nba-winner .nba-team-name {
    color: #0f172a;
    font-weight: 800;
}

.nba-winner .nba-team-score.nba-score-numeric {
    color: rgba(16, 185, 129, 0.18); /* Green faded watermark */
}

/* =========================================
ESTILO PERDEDOR
========================================= */
.nba-loser {
    background-color: #ffffff;
}

.nba-loser .nba-team-name {
    color: #94a3b8;
    font-weight: 500;
    opacity: 0.7;
}

.nba-loser .nba-team-score.nba-score-numeric {
    color: rgba(148, 163, 184, 0.12); /* Slate/grey faded watermark */
}

.nba-loser .nba-team-logo {
    opacity: 0.03;
    filter: grayscale(100%);
}

/* ESTADOS DE PARTIDOS (Píldoras flotantes en el centro del encuentro) */
.nba-status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 2px 8px;
    border-radius: 9999px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    pointer-events: none;
    user-select: none;
    line-height: 1.2;
    transition: all 0.3s ease;
}

.nba-status-pending {
    background-color: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
}

.nba-status-playing {
    background-color: #fff7ed;
    color: #ea580c;
    border: 1px solid #fed7aa;
    animation: status-pulse 2s infinite ease-in-out;
}

.nba-status-finished {
    background-color: #f8fafc;
    color: #94a3b8;
    border: 1px solid #e2e8f0;
}

@keyframes status-pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.3); }
    70% { transform: scale(1.04); box-shadow: 0 0 0 5px rgba(234, 88, 12, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(234, 88, 12, 0); }
}

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

/* NOMBRE DEL EQUIPO CENTRADO CON DISEÑO TIPO PASTILLA BLANCO TRANSPARENTE */
.nba-team-name {
    position: relative;
    z-index: 10;
    flex-grow: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    background-color: rgba(255, 255, 255, 0.1); /* Highly transparent to perfectly reveal the logo and giant score numbers */
    backdrop-filter: none; /* Removed blur entirely so background numbers are crystal clear */
    -webkit-backdrop-filter: none;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 9999px;
    padding: 0 16px;
    font-weight: 700;
    font-size: 0.85rem;
    color: #0f172a; /* Slate dark text for readability over transparency */
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: 0.75px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.01);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    width: fit-content;
    max-width: 90%;
    margin: 0 auto; /* Centers the pill within the team row container */
}

.nba-card:hover .nba-team-name {
    background-color: rgba(255, 255, 255, 0.3); /* Slightly more visible on hover but still highly transparent */
    border-color: rgba(255, 255, 255, 0.35);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

</style>

        <div class="py-12">
            <div class="w-[96%] md:w-[90%] mx-auto mb-[10vh]">
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
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
                                    
                                    <!-- IZQUIERDA: Título del Grupo y Tipo de Torneo -->
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                            <span><span class="text-gray-400 text-base font-normal mr-2">Grupo:</span>{{ $groupName }}</span>
                                            @php
                                                $tSettings = $tournament->settings ? $tournament->settings->settings : [];
                                                $tType = $tSettings['tournament_type'] ?? 'round_robin';
                                                $typeLabels = [
                                                    'round_robin' => 'Liga (Todos contra todos + Playoffs)',
                                                    'elimination' => 'Eliminatoria Directa',
                                                    'single_elimination' => 'Eliminatoria Directa',
                                                    'double_elimination' => 'Doble Eliminatoria',
                                                    'groups' => 'Fase de Grupos',
                                                    'groups_and_playoffs' => 'Grupos y Liguilla',
                                                ];
                                                $labelText = $typeLabels[$tType] ?? ucfirst(str_replace('_', ' ', $tType));
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                                {{ $labelText }}
                                            </span>
                                        </h3>
                                        <!-- Mostramos la variable calculada -->
                                        <span class="text-sm text-gray-500">
                                            {{ $totalEquipos }} Equipos
                                        </span>
                                    </div>

                                </div>

                                <!-- 2. CONTENEDOR ENCAPSULADO CON SCROLL HORIZONTAL (TREE BRACKET) -->
                                <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner">
                                    
                                    <!-- Título Interno y Botón de Equipo Tardío -->
                                    <div class="mb-6 border-b border-gray-200 pb-3 flex flex-wrap justify-between items-center gap-4">
                                        <h4 class="text-xl font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                            <i class="fa-solid fa-trophy text-yellow-500"></i> FASE DOBLE ELIMINATORIA - ÁRBOL DE TORNEO
                                        </h4>

                                        @php
                                            $groupData = $tournament->settings->settings['brackets_data'][$groupName] ?? [];
                                            $wbRound = $groupData['wb_current_round'] ?? 1;
                                            $wbByes = $groupData['wb_byes'] ?? [];
                                            $currentByes = $tournament->settings->settings['current_byes'][$groupName] ?? [];
                                            $hasByes = !empty($wbByes) || !empty($currentByes);
                                        @endphp

                                        <div class="flex flex-wrap gap-2 items-center">
                                            @if($tournament->status === 'active' || $tournament->status === 'in_progress')
                                                @if($wbRound == 1)
                                                    <button type="button" onclick="document.getElementById('modalAddNormalLateTeam-{{ Str::slug($groupName) }}').showModal()" class="px-3.5 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg font-bold text-xs uppercase tracking-wider shadow-sm transition flex items-center gap-2">
                                                        <i class="fa-solid fa-user-plus text-gray-500"></i> Inscribir Equipo Normal
                                                    </button>
                                                @endif

                                                <button type="button" onclick="document.getElementById('modalAddLateTeam-{{ Str::slug($groupName) }}').showModal()" class="px-3.5 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg font-bold text-xs uppercase tracking-wider shadow-sm transition flex items-center gap-2">
                                                    <i class="fa-solid fa-user-plus text-gray-500"></i> Inscribir Equipo Tardío
                                                </button>
                                            @endif

                                            <a href="{{ route('tournaments.schedule', ['tournament' => $tournament, 'group' => $groupName]) }}" 
                                               class="px-3.5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow transition flex items-center gap-2"
                                               title="Ver calendario de este grupo">
                                                <i class="fa-solid fa-calendar-days"></i> Ver Calendario
                                            </a>
                                        </div>

                                        @if($tournament->status === 'active' || $tournament->status === 'in_progress')
                                            @if($wbRound == 1)
                                                @php
                                                    $parts = explode(' - ', $groupName, 2);
                                                    $groupCategory = trim($parts[0] ?? 'Varonil');
                                                    $groupStrength = trim($parts[1] ?? 'Libre');
                                                @endphp

                                                <!-- Modal de Inscripción Normal (Winner Bracket R1) -->
                                                <dialog id="modalAddNormalLateTeam-{{ Str::slug($groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                    <form method="POST" action="{{ route('tournaments.add-normal-late-team', $tournament) }}" class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="category_group" value="{{ $groupName }}">

                                                        <div class="flex items-center justify-between border-b pb-2">
                                                            <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                                <i class="fa-solid fa-user-plus text-green-500"></i> Inscribir Equipo Normal (Winner Bracket)
                                                            </h3>
                                                            <button type="button" onclick="document.getElementById('modalAddNormalLateTeam-{{ Str::slug($groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                        </div>

                                                        @if($hasByes)
                                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                                                        <p class="text-xs text-gray-600 leading-relaxed">
                            Hay <strong>{{ count($wbByes) }}</strong> pases directos (BYEs) disponibles en la Ronda 1.
                        </p>
                        <div class="flex items-center justify-between border-b pb-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo</label>
                            <select name="team_id" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug($groupName) }}">
                                <option value="">-- Selecciona equipo --</option>
                                @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                    @if(!$tournament->teams->contains($t->id))
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                                                                Al inscribir este equipo, se eliminará uno de los BYEs y el equipo jugará un partido normal de la Ronda 1 del Winner Bracket contra el equipo que iba a descansar.
                                                            </p>

                                                            <div>
                                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo</label>
                                                                <div class="flex">
                                                                    <select name="team_id" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug($groupName) }} border-r-0">
                                                                        <option value="">-- Selecciona un equipo --</option>
                                                                        @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                            @if(!$tournament->teams->contains($t->id))
                                                                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                    <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug($groupName) }}', 'team_id')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-gray-600 leading-relaxed text-red-600 font-medium">
                                                                No hay pases directos (BYEs) disponibles en la Ronda 1.
                                                                Para no alterar los partidos ya agendados, debes inscribir exactamente <strong>2 equipos</strong> que jugarán directamente entre sí en la Ronda 1.
                                                            </p>

                                                            <div class="space-y-3">
                                                                <div>
                                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo 1</label>
                                                                    <div class="flex">
                                                                        <select name="team_id_1" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug($groupName) }} border-r-0">
                                                                            <option value="">-- Selecciona equipo 1 --</option>
                                                                            @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                                @if(!$tournament->teams->contains($t->id))
                                                                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug($groupName) }}', 'team_id_1')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo 2</label>
                                                                    <div class="flex">
                                                                        <select name="team_id_2" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug($groupName) }} border-r-0">
                                                                            <option value="">-- Selecciona equipo 2 --</option>
                                                                            @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                                @if(!$tournament->teams->contains($t->id))
                                                                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug($groupName) }}', 'team_id_2')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="flex justify-end gap-2 pt-3 border-t">
                                                            <button type="button" onclick="document.getElementById('modalAddNormalLateTeam-{{ Str::slug($groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Inscribir</button>
                                                        </div>
                                                    </form>
                                                </dialog>

                                                <!-- Modal para Crear Nuevo Equipo vía AJAX -->
                                                <dialog id="modalCreateTeamFromStandings-{{ Str::slug($groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                    <form onsubmit="submitCreateTeamFromStandings(event, '{{ Str::slug($groupName) }}')" class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                                                        <input type="hidden" name="category" value="{{ $groupCategory }}">
                                                        <input type="hidden" name="strength" value="{{ $groupStrength }}">
                                                        <input type="hidden" name="status" value="active">
                                                        <input type="hidden" name="skip_double_elim_late" value="1">

                                                        <div class="flex items-center justify-between border-b pb-2">
                                                            <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                                <i class="fa-solid fa-user-plus text-orange-500"></i> Crear Nuevo Equipo
                                                            </h3>
                                                            <button type="button" onclick="document.getElementById('modalCreateTeamFromStandings-{{ Str::slug($groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                        </div>

                                                        <p class="text-xs text-gray-600 leading-relaxed">
                                                            Se creará un nuevo equipo directamente para el torneo <strong>{{ $tournament->name }}</strong>, categoría <strong>{{ $groupCategory }}</strong> y nivel <strong>{{ $groupStrength }}</strong>.
                                                        </p>

                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nombre del Equipo</label>
                                                            <input type="text" name="name" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm animate-pulse-once" placeholder="Ej. Lakers">
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Entrenador (Coach)</label>
                                                            <select name="coach_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm bg-white">
                                                                <option value="">-- Sin Entrenador --</option>
                                                                @foreach ($coaches as $coach)
                                                                    <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="flex justify-end gap-2 pt-3 border-t">
                                                            <button type="button" onclick="document.getElementById('modalCreateTeamFromStandings-{{ Str::slug($groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Crear Equipo</button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                            @endif

                                            <!-- Modal de Equipo Tardío -->
                                            <dialog id="modalAddLateTeam-{{ Str::slug($groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                <form method="POST" action="{{ route('tournaments.add-late-team', $tournament) }}" class="space-y-4">
                                                    @csrf
                                                    <input type="hidden" name="category_group" value="{{ $groupName }}">

                                                    <div class="flex items-center justify-between border-b pb-2">
                                                        <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                            <i class="fa-solid fa-user-plus text-orange-500"></i> Inscribir Equipo Tardío
                                                        </h3>
                                                        <button type="button" onclick="document.getElementById('modalAddLateTeam-{{ Str::slug($groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                    </div>

                                                    <p class="text-xs text-gray-600 leading-relaxed">
                                                        El equipo se inscribirá con <strong>1 derrota técnica acumulada</strong> e ingresará directamente al <strong>Bracket de Perdedores (Loser Bracket)</strong>. Si existe un pase directo (BYE) disponible, se emparejará de inmediato contra ese equipo.
                                                    </p>

                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo Tardío</label>
                                                        <select name="team_id" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                                            <option value="">-- Selecciona un equipo --</option>
                                                            @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                @if(!$tournament->teams->contains($t->id))
                                                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="flex justify-end gap-2 pt-3 border-t">
                                                        <button type="button" onclick="document.getElementById('modalAddLateTeam-{{ Str::slug($groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Integrar al Loser Bracket</button>
                                                    </div>
                                                </form>
                                            </dialog>
                                        @endif
                                    </div>

                                    <!-- Área de Scroll Horizontal para el Árbol -->
                                    <div class="overflow-x-auto pb-6 pt-2 nba-scroll">
                                        <div class="inline-flex items-stretch justify-between min-w-full gap-8 md:gap-12 p-2">
                                            
                                            <!-- ================================================================= -->
                                            <!-- 1. BRACKET DE GANADORES (IZQUIERDA A DERECHA)                     -->
                                            <!-- ================================================================= -->
                                            @php
                                                $allGroupTeams = $tournament->teams->filter(function($t) use ($groupName) {
                                                    return (($t->category ?? 'Sin Categoria') . ' - ' . ($t->strength ?? 'General')) === $groupName;
                                                });

                                                $settings = $tournament->settings ? $tournament->settings->settings : [];
                                                $lateTeams = array_map('intval', $settings['brackets_data'][$groupName]['late_teams'] ?? []);

                                                // 1. Calcular Byes de Winner Bracket (excluyendo equipos tardíos que van al Loser Bracket)
                                                $activeWbTeams = array_values(array_diff($allGroupTeams->pluck('id')->toArray(), $lateTeams));
                                                $wbByesByRound = [];
                                                foreach ($data['bracket']['winner_bracket'] as $rIdx => $rGames) {
                                                    $rNum = $rIdx + 1;
                                                    $played = [];
                                                    $winners = [];
                                                    foreach ($rGames as $g) {
                                                        if ($g->local_team_id) $played[] = $g->local_team_id;
                                                        if ($g->away_team_id) $played[] = $g->away_team_id;
                                                        if ($g->status === 'finished') {
                                                            $winners[] = ($g->local_team_score > $g->away_team_score) ? $g->local_team_id : $g->away_team_id;
                                                        }
                                                    }
                                                    $byes = array_values(array_diff($activeWbTeams, $played));
                                                    $wbByesByRound[$rNum] = $byes;
                                                    $activeWbTeams = array_merge($winners, $byes);
                                                }

                                                // 2. Calcular Byes de Loser Bracket
                                                $activeLbTeams = $lateTeams; // Los equipos tardíos inician en el Loser Bracket
                                                $lbByesByRound = [];
                                                foreach ($data['bracket']['loser_bracket'] as $rIdx => $rGames) {
                                                    $rNum = $rIdx + 1;
                                                    $newEntrants = [];
                                                    if ($rIdx === 0) {
                                                        $wbGames = $data['bracket']['winner_bracket'][0] ?? [];
                                                        foreach ($wbGames as $g) {
                                                            if ($g->status === 'finished') {
                                                                $newEntrants[] = ($g->local_team_score > $g->away_team_score) ? $g->away_team_id : $g->local_team_id;
                                                            }
                                                        }
                                                    } elseif ($rIdx % 2 !== 0) {
                                                        $wbRoundIndex = ($rIdx + 1) / 2;
                                                        $wbGames = $data['bracket']['winner_bracket'][$wbRoundIndex] ?? [];
                                                        foreach ($wbGames as $g) {
                                                            if ($g->status === 'finished') {
                                                                $newEntrants[] = ($g->local_team_score > $g->away_team_score) ? $g->away_team_id : $g->local_team_id;
                                                            }
                                                        }
                                                    }
                                                    $activeLbTeams = array_values(array_unique(array_merge($activeLbTeams, $newEntrants)));
                                                    $played = [];
                                                    $winners = [];
                                                    foreach ($rGames as $g) {
                                                        if ($g->local_team_id) $played[] = $g->local_team_id;
                                                        if ($g->away_team_id) $played[] = $g->away_team_id;
                                                        if ($g->status === 'finished') {
                                                            $winners[] = ($g->local_team_score > $g->away_team_score) ? $g->local_team_id : $g->away_team_id;
                                                        }
                                                    }
                                                    $byes = array_values(array_diff($activeLbTeams, $played));
                                                    $lbByesByRound[$rNum] = $byes;
                                                    $activeLbTeams = array_values(array_unique(array_merge($winners, $byes)));
                                                }
                                            @endphp

                                            <div class="flex flex-col gap-4 border-r border-dashed border-blue-200 pr-6 shrink-0">
                                                <div class="nba-header">BRACKET DE GANADORES</div>

                                                <div class="flex flex-row items-center gap-2 md:gap-4 h-full">
                                                    @foreach($data['bracket']['winner_bracket'] as $roundIndex => $games)
                                                        <div class="flex flex-col justify-around gap-6 h-full min-w-[240px]">
                                                            <!-- Encabezado de Ronda -->
                                                            <div class="text-center">
                                                                @php
                                                                    $totalWinnerRounds = count($data['bracket']['winner_bracket']);
                                                                    $roundNum = $roundIndex + 1;
                                                                    if ($roundNum == $totalWinnerRounds) {
                                                                        $label = 'Final';
                                                                    } elseif ($roundNum == $totalWinnerRounds - 1) {
                                                                        $label = 'Semifinal';
                                                                    } else {
                                                                        $label = 'Ronda ' . $roundNum;
                                                                    }
                                                            @endphp
                                                            <div class="nba-header">{{ $label }}</div>                             </div>

                                                            <!-- Partidos de la Ronda -->
                                                            <div class="flex flex-col justify-around gap-6 flex-1">
                                                                @foreach($games as $game)
                                                                    <div class="nba-card w-60 shadow-md hover:shadow-xl transition-all duration-200 border-l-4 border-l-blue-500 relative">
                                                                        <!-- Local -->
                                                                        <div @class([
                                                                            'nba-team-row',
                                                                            'nba-winner' => $game->local_team_score > $game->away_team_score,
                                                                            'nba-loser' => $game->away_team_score > $game->local_team_score
                                                                        ])>
                                                                            <img src="{{ asset('storage/' . ($game->localTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                            <span class="nba-team-name">{{ $game->localTeam->name ?? 'Por definir' }}</span>
                                                                            <span class="nba-team-score {{ is_numeric($game->local_team_score ?? ($game->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $game->local_team_score ?? ($game->localTeam ? '0' : '-') }}</span>
                                                                        </div>
                                                                        <!-- Visitante -->
                                                                        <div @class([
                                                                            'nba-team-row',
                                                                            'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                            'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                        ])>
                                                                            <img src="{{ asset('storage/' . ($game->awayTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                            <span class="nba-team-name">{{ $game->awayTeam->name ?? 'Por definir' }}</span>
                                                                            <span class="nba-team-score {{ is_numeric($game->away_team_score ?? ($game->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $game->away_team_score ?? ($game->awayTeam ? '0' : '-') }}</span>
                                                                        </div>
                                                                        <!-- Estado del Juego Centrado -->
                                                                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                            @if(($game->status ?? 'pending') === 'pending')
                                                                                <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                            @elseif(($game->status ?? 'pending') === 'playing')
                                                                                <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                            @elseif(($game->status ?? 'pending') === 'finished')
                                                                                <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                                @php
                                                                    $roundByes = $wbByesByRound[$roundIndex + 1] ?? [];
                                                                    $normalByes = [];
                                                                    $lateByes = [];
                                                                    foreach ($roundByes as $byeId) {
                                                                        if (in_array((int)$byeId, $lateTeams)) {
                                                                            $lateByes[] = $byeId;
                                                                        } else {
                                                                            $normalByes[] = $byeId;
                                                                        }
                                                                    }
                                                                @endphp
                                                                
                                                                <!-- 1. BYEs Tradicionales -->
                                                                @foreach($normalByes as $byeTeamId)
                                                                    @php
                                                                        $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                    @endphp
                                                                    @if($byeTeam)
                                                                        <div class="nba-card w-60 border-l-4 border-l-orange-500 bg-orange-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                            <div class="absolute right-2 top-2 z-10">
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 border border-orange-200">
                                                                                    BYE
                                                                                </span>
                                                                            </div>
                                                                            <div class="flex items-center gap-3 py-1">
                                                                                <img src="{{ asset('storage/' . ($byeTeam->image_path ?? '')) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                                <div class="flex flex-col min-w-0">
                                                                                    <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate">{{ $byeTeam->name }}</span>
                                                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Avanza Directo</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2 bg-gradient-to-r from-orange-50 to-orange-100/50 text-orange-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-orange-200/60 font-sans">
                                                                                ⚡ Pase Automático
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                <!-- 2. Equipos Tardíos (Abajo del Todo) -->
                                                                @foreach($lateByes as $byeTeamId)
                                                                    @php
                                                                        $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                    @endphp
                                                                    @if($byeTeam)
                                                                        <div class="nba-card w-60 border-l-4 border-l-blue-500 bg-blue-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                            <div class="absolute right-2 top-2 z-10">
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                                                                                    Tardío
                                                                                </span>
                                                                            </div>
                                                                            <div class="flex items-center gap-3 py-1">
                                                                                <img src="{{ asset('storage/' . ($byeTeam->image_path ?? '')) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                                <div class="flex flex-col min-w-0">
                                                                                    <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate">{{ $byeTeam->name }}</span>
                                                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Equipo Registrado</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2 bg-gradient-to-r from-blue-50 to-blue-100/50 text-blue-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-blue-200/60 font-sans">
                                                                                ⚡ Registro Tardío
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- ================================================================= -->
                                            <!-- 2. SECCIÓN CENTRAL: GRAN FINAL & DEFINICIÓN                        -->
                                            <!-- ================================================================= -->
                                            <div class="flex flex-col items-center gap-4 px-4 shrink-0 min-w-[300px]">
                                                <div class="nba-header">GRAN FINAL</div>

                                                <div class="flex-1 flex flex-col justify-center items-center gap-6 w-full">
                                                    <!-- 1. PARTIDO DE GRAN FINAL (GF) -->
                                                    @if(isset($data['bracket']['grand_final']))
                                                        @php 
                                                            $gf = $data['bracket']['grand_final']; 
                                                            $wfLocal = $gf->local_team_score > $gf->away_team_score; 
                                                        @endphp
                                                        
                                                        <div class="w-72 relative z-10">
                                                            <div class="nba-card border-2 border-yellow-500 shadow-2xl bg-white relative overflow-hidden">
                                                                @if($gf->status === 'finished')
                                                                    <div class="absolute inset-0 bg-gradient-to-t from-yellow-100/50 to-transparent opacity-60 z-0 pointer-events-none"></div>
                                                                @endif


                                                                <!-- LOCAL -->
                                                                <div @class([
                                                                    'nba-team-row relative z-10',
                                                                    'nba-winner' => $wfLocal,
                                                                    'nba-loser' => !$wfLocal && $gf->status === 'finished'
                                                                ])>
                                                                    <img src="{{ asset('storage/' . ($gf->localTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                    <span class="nba-team-name">{{ $gf->localTeam->name ?? 'Campeón Winner' }}</span>
                                                                    <span class="nba-team-score {{ is_numeric($gf->local_team_score ?? ($gf->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $gf->local_team_score ?? ($gf->localTeam ? '0' : '-') }}</span>
                                                                </div>

                                                                <!-- VISITANTE -->
                                                                <div @class([
                                                                    'nba-team-row relative z-10',
                                                                    'nba-winner' => !$wfLocal && $gf->status === 'finished',
                                                                    'nba-loser' => $wfLocal
                                                                ])>
                                                                    <img src="{{ asset('storage/' . ($gf->awayTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                    <span class="nba-team-name">{{ $gf->awayTeam->name ?? 'Campeón Loser' }}</span>
                                                                    <span class="nba-team-score {{ is_numeric($gf->away_team_score ?? ($gf->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $gf->away_team_score ?? ($gf->awayTeam ? '0' : '-') }}</span>
                                                                </div>
                                                                <!-- Estado del Juego Centrado -->
                                                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                    @if(($gf->status ?? 'pending') === 'pending')
                                                                        <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                    @elseif(($gf->status ?? 'pending') === 'playing')
                                                                        <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                    @elseif(($gf->status ?? 'pending') === 'finished')
                                                                        <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- 2. PARTIDO DE REVANCHA (GR) -->
                                                    @if(isset($data['bracket']['reset_game']))
                                                        @php 
                                                            $g = $data['bracket']['reset_game']; 
                                                            $wLocal = $g->local_team_score > $g->away_team_score; 
                                                        @endphp
                                                        
                                                        <div class="w-72 mt-2">
                                                            <div class="nba-card border-2 border-yellow-400 shadow-lg bg-white relative overflow-hidden">
                                                                <!-- LOCAL -->
                                                                <div @class([
                                                                    'nba-team-row',
                                                                    'nba-winner' => $wLocal,
                                                                    'nba-loser' => !$wLocal && $g->status === 'finished'
                                                                ])>
                                                                    <img src="{{ asset('storage/' . ($g->localTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                    <span class="nba-team-name">{{ $g->localTeam->name ?? 'Por definir' }}</span>
                                                                    <span class="nba-team-score {{ is_numeric($g->local_team_score ?? ($g->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $g->local_team_score ?? ($g->localTeam ? '0' : '-') }}</span>
                                                                </div>
                                                                
                                                                <!-- VISITANTE -->
                                                                <div @class([
                                                                    'nba-team-row',
                                                                    'nba-winner' => !$wLocal && $g->status === 'finished',
                                                                    'nba-loser' => $wLocal
                                                                ])>
                                                                    <img src="{{ asset('storage/' . ($g->awayTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                    <span class="nba-team-name">{{ $g->awayTeam->name ?? 'Por definir' }}</span>
                                                                    <span class="nba-team-score {{ is_numeric($g->away_team_score ?? ($g->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $g->away_team_score ?? ($g->awayTeam ? '0' : '-') }}</span>
                                                                </div>
                                                                <!-- Estado del Juego Centrado -->
                                                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                    @if(($g->status ?? 'pending') === 'pending')
                                                                        <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                    @elseif(($g->status ?? 'pending') === 'playing')
                                                                        <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                    @elseif(($g->status ?? 'pending') === 'finished')
                                                                        <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(!isset($data['bracket']['grand_final']) && !isset($data['bracket']['reset_game']))
                                                        <div class="w-full text-center py-8 px-4 border-2 border-dashed border-gray-300 rounded-xl bg-white shadow-inner">
                                                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Esperando Finalistas</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- ================================================================= -->
                                            <!-- 3. BRACKET DE PERDEDORES (DERECHA A IZQUIERDA: flex-row-reverse)  -->
                                            <!-- ================================================================= -->
                                            <div class="flex flex-col gap-4 border-l border-dashed border-red-200 pl-6 shrink-0">
                                                <div class="nba-header">BRACKET DE PERDEDORES</div>

                                                <!-- flex-row-reverse coloca la Ronda 1 en el extremo derecho y avanza hacia la izquierda -->
                                                <div class="flex flex-row-reverse items-center gap-2 md:gap-4 h-full">
                                                    @foreach($data['bracket']['loser_bracket'] as $roundIndex => $games)


                                                        <div class="flex flex-col justify-around gap-6 h-full min-w-[240px]">
                                                            <!-- Encabezado de Ronda -->
@php
    $totalLoserRounds = count($data['bracket']['loser_bracket']);
    $roundNum = $roundIndex + 1;
    if ($roundNum == $totalLoserRounds) {
        $label = 'Final';
    } elseif ($roundNum == $totalLoserRounds - 1) {
        $label = 'Semifinal';
    } else {
        $label = 'Ronda ' . $roundNum;
    }
@endphp
<div class="nba-header">{{ $label }}</div>

                                                            <!-- Partidos de la Ronda -->
                                                            <div class="flex flex-col justify-around gap-6 flex-1">
                                                                @foreach($games as $game)
                                                                    <div class="nba-card w-60 shadow-md hover:shadow-xl transition-all duration-200 border-r-4 border-r-red-500 relative">
                                                                        <!-- Local -->
                                                                        <div @class([
                                                                            'nba-team-row',
                                                                            'nba-winner' => $game->local_team_score > $game->away_team_score,
                                                                            'nba-loser' => $game->away_team_score > $game->local_team_score
                                                                        ])>
                                                                            <img src="{{ asset('storage/' . ($game->localTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                            <span class="nba-team-name">{{ $game->localTeam->name ?? 'Por definir' }}</span>
                                                                            <span class="nba-team-score {{ is_numeric($game->local_team_score ?? ($game->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $game->local_team_score ?? ($game->localTeam ? '0' : '-') }}</span>
                                                                        </div>
                                                                        <!-- Visitante -->
                                                                        <div @class([
                                                                            'nba-team-row',
                                                                            'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                            'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                        ])>
                                                                            <img src="{{ asset('storage/' . ($game->awayTeam->image_path ?? '')) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                            <span class="nba-team-name">{{ $game->awayTeam->name ?? 'Por definir' }}</span>
                                                                            <span class="nba-team-score {{ is_numeric($game->away_team_score ?? ($game->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $game->away_team_score ?? ($game->awayTeam ? '0' : '-') }}</span>
                                                                        </div>
                                                                        <!-- Estado del Juego Centrado -->
                                                                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                            @if(($game->status ?? 'pending') === 'pending')
                                                                                <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                            @elseif(($game->status ?? 'pending') === 'playing')
                                                                                <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                            @elseif(($game->status ?? 'pending') === 'finished')
                                                                                <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                                @php
                                                                    $roundByes = $lbByesByRound[$roundIndex + 1] ?? [];
                                                                    $normalByes = [];
                                                                    $lateByes = [];
                                                                    foreach ($roundByes as $byeId) {
                                                                        if (in_array((int)$byeId, $lateTeams)) {
                                                                            $lateByes[] = $byeId;
                                                                        } else {
                                                                            $normalByes[] = $byeId;
                                                                        }
                                                                    }
                                                                @endphp
                                                                
                                                                <!-- 1. BYEs Tradicionales -->
                                                                @foreach($normalByes as $byeTeamId)
                                                                    @php
                                                                        $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                    @endphp
                                                                    @if($byeTeam)
                                                                        <div class="nba-card w-60 border-r-4 border-r-orange-500 bg-orange-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                            <div class="absolute left-2 top-2 z-10">
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 border border-orange-200">
                                                                                    BYE
                                                                                </span>
                                                                            </div>
                                                                            <div class="flex items-center gap-3 py-1 justify-end text-right">
                                                                                <div class="flex flex-col min-w-0 font-sans">
                                                                                    <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate">{{ $byeTeam->name }}</span>
                                                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Avanza Directo</span>
                                                                                </div>
                                                                                <img src="{{ asset('storage/' . ($byeTeam->image_path ?? '')) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                            </div>
                                                                            <div class="mt-2 bg-gradient-to-r from-orange-50 to-orange-100/50 text-orange-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-orange-200/60 font-sans">
                                                                                ⚡ Pase Automático
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                <!-- 2. Equipos Tardíos (Abajo del Todo) -->
                                                                @foreach($lateByes as $byeTeamId)
                                                                    @php
                                                                        $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                    @endphp
                                                                    @if($byeTeam)
                                                                        <div class="nba-card w-60 border-r-4 border-r-blue-500 bg-blue-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                            <div class="absolute left-2 top-2 z-10">
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                                                                                    Tardío
                                                                                </span>
                                                                            </div>
                                                                            <div class="flex items-center gap-3 py-1 justify-end text-right">
                                                                                <div class="flex flex-col min-w-0 font-sans">
                                                                                    <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate">{{ $byeTeam->name }}</span>
                                                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Equipo Registrado</span>
                                                                                </div>
                                                                                <img src="{{ asset('storage/' . ($byeTeam->image_path ?? '')) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                            </div>
                                                                            <div class="mt-2 bg-gradient-to-r from-blue-50 to-blue-100/50 text-blue-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-blue-200/60 font-sans">
                                                                                ⚡ Registro Tardío
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach


                                                </div>
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
                                    
                                    <!-- IZQUIERDA: Título del Grupo y Tipo de Torneo -->
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                            <span><span class="text-gray-400 text-base font-normal mr-2">Grupo:</span>{{ $groupName }}</span>
                                            @php
                                                $tSettings = $tournament->settings ? $tournament->settings->settings : [];
                                                $tType = $tSettings['tournament_type'] ?? 'round_robin';
                                                $typeLabels = [
                                                    'round_robin' => 'Liga (Todos contra todos + Playoffs)',
                                                    'elimination' => 'Eliminatoria Directa',
                                                    'single_elimination' => 'Eliminatoria Directa',
                                                    'double_elimination' => 'Doble Eliminatoria',
                                                    'groups' => 'Fase de Grupos',
                                                    'groups_and_playoffs' => 'Grupos y Liguilla',
                                                ];
                                                $labelText = $typeLabels[$tType] ?? ucfirst(str_replace('_', ' ', $tType));
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                                {{ $labelText }}
                                            </span>
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
                                        @if($tType !== 'single_elimination' && $tType !== 'elimination')
                                            <a href="{{ route('tournaments.schedule', ['tournament' => $tournament, 'group' => $groupName]) }}" 
                                            class="text-orange-500 hover:text-orange-700 hover:bg-orange-50 rounded-full p-1.5 transition-all duration-200 shrink-0"
                                            title="Ver calendario de este grupo">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                            </a>
                                        @endif

                                    </div>
                                </div>

                                <!-- ================================================================= -->
                                <!-- VISUALIZACIÓN DE PLAYOFFS (FINAL) -->
                                <!-- ================================================================= -->
                                @if(isset($data['has_playoffs']) && $data['has_playoffs'])
                                 @php
                                     // Calcular Byes de playoffs dinámicamente
                                     $activePlayoffTeams = ($data['teams'] ?? collect())->pluck('id')->toArray();
                                     $playoffByesByRound = [];
                                     foreach ($data['playoff_rounds'] ?? [] as $rIdx => $rData) {
                                         $rGames = $rData['games'] ?? [];
                                         $played = [];
                                         $winners = [];
                                         foreach ($rGames as $g) {
                                             if ($g->local_team_id) $played[] = (int) $g->local_team_id;
                                             if ($g->away_team_id) $played[] = (int) $g->away_team_id;
                                             if ($g->status === 'finished') {
                                                 $winners[] = ($g->local_team_score > $g->away_team_score) ? (int) $g->local_team_id : (int) $g->away_team_id;
                                             }
                                         }
                                         $byes = array_values(array_diff($activePlayoffTeams, $played));
                                         $playoffByesByRound[$rIdx] = $byes;
                                         $activePlayoffTeams = array_merge($winners, $byes);
                                     }
                                 @endphp
                                <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner">

                                     <!-- 1. TÍTULO Y ACCIONES -->
                                     <div class="mb-6 border-b border-gray-200 pb-3 flex flex-wrap justify-between items-center gap-4">
                                         <h4 class="text-xl font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                             <i class="fa-solid fa-trophy text-yellow-500"></i> Fase Eliminatoria
                                         </h4>

                                         @php
                                             $tSettings = $tournament->settings ? $tournament->settings->settings : [];
                                             $tType = $tSettings['tournament_type'] ?? 'round_robin';
                                             $teamIdsInGroup = $data['team_ids'] ?? [];
                                             $hasFinishedPlayoffs = \App\Models\Game::where('tournament_id', $tournament->id)
                                                 ->where('is_playoff', true)
                                                 ->where('status', 'finished')
                                                 ->where(function($query) use ($teamIdsInGroup) {
                                                     $query->whereIn('local_team_id', $teamIdsInGroup)
                                                           ->orWhereIn('away_team_id', $teamIdsInGroup);
                                                 })
                                                 ->exists();
                                             $isElimRound1 = !$hasFinishedPlayoffs;

                                             // Detectar BYEs: equipos del grupo sin ningún partido playoff
                                             // Primero verificar en current_byes (settings), luego buscar equipos sin partido
                                             $playoffByes = $tSettings['current_byes'][$groupName] ?? [];
                                             if (empty($playoffByes) && !empty($teamIdsInGroup)) {
                                                 // Buscar equipos del grupo que no tienen partido playoff asignado
                                                 $teamsInPlayGames = \App\Models\Game::where('tournament_id', $tournament->id)
                                                     ->where('is_playoff', true)
                                                     ->where(function($q) use ($teamIdsInGroup) {
                                                         $q->whereIn('local_team_id', $teamIdsInGroup)
                                                           ->orWhereIn('away_team_id', $teamIdsInGroup);
                                                     })
                                                     ->get(['local_team_id', 'away_team_id']);
                                                 $teamsWithGame = $teamsInPlayGames
                                                     ->flatMap(fn($g) => [$g->local_team_id, $g->away_team_id])
                                                     ->filter()
                                                     ->unique()
                                                     ->toArray();
                                                 // Equipos sin partido = BYEs implícitos
                                                 $playoffByes = array_values(array_diff($teamIdsInGroup, $teamsWithGame));
                                             }
                                             $hasPlayoffByes = !empty($playoffByes);
                                         @endphp


                                         @if(($tType === 'single_elimination' || $tType === 'elimination') && ($tournament->status === 'active' || $tournament->status === 'in_progress'))
                                             <div class="flex flex-wrap gap-2 items-center">
                                                 @if($isElimRound1)
                                                     <button type="button" onclick="document.getElementById('modalAddNormalLateTeamPlayoffs-{{ Str::slug($groupName) }}').showModal()" class="px-3.5 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg font-bold text-xs uppercase tracking-wider shadow-sm transition flex items-center gap-2">
                                                         <i class="fa-solid fa-user-plus text-gray-500"></i> Inscribir Equipo Normal
                                                     </button>
                                                 @endif

                                                 <a href="{{ route('tournaments.schedule', ['tournament' => $tournament, 'group' => $groupName]) }}" 
                                                    class="px-3.5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow transition flex items-center gap-2"
                                                    title="Ver calendario de este grupo">
                                                     <i class="fa-solid fa-calendar-days"></i> Ver Calendario
                                                 </a>
                                             </div>

                                             <!-- Modal de Inscripción Normal (Playoffs / Eliminatoria Directa) -->
                                             <dialog id="modalAddNormalLateTeamPlayoffs-{{ Str::slug($groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                 <form method="POST" action="{{ route('tournaments.add-normal-late-team', $tournament) }}" class="space-y-4">
                                                     @csrf
                                                     <input type="hidden" name="category_group" value="{{ $groupName }}">

                                                     <div class="flex items-center justify-between border-b pb-2">
                                                         <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                             <i class="fa-solid fa-user-plus text-orange-500"></i> Inscribir Equipo Normal
                                                         </h3>
                                                         <button type="button" onclick="document.getElementById('modalAddNormalLateTeamPlayoffs-{{ Str::slug($groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                     </div>

                                                     @if($hasPlayoffByes)
                                                         <p class="text-xs text-gray-600 leading-relaxed">
                                                             Hay descansos (BYEs) disponibles en la Ronda 1. El nuevo equipo se integrará ocupando el lugar de uno de ellos y jugará en esta ronda.
                                                         </p>
                                                         <div>
                                                             <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo a Integrar</label>
                                                             <div class="flex">
                                                                 <select name="team_id" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug($groupName) }} border-r-0 bg-white">
                                                                     <option value="">-- Selecciona un equipo --</option>
                                                                     @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                         @if(!$tournament->teams->contains($t->id))
                                                                             <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                         @endif
                                                                     @endforeach
                                                                 </select>
                                                                 <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug($groupName) }}', 'team_id')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                     </svg>
                                                                 </button>
                                                             </div>
                                                         </div>
                                                     @else
                                                         {{-- Sin BYE: el usuario elige si agrega 1 equipo (nuevo BYE) o 2 (partido directo) --}}
                                                         @php $noBye_slug = Str::slug($groupName); @endphp
                                                         <p class="text-xs text-gray-500 leading-relaxed">
                                                             No hay descansos (BYEs) disponibles. Elige cómo inscribir al equipo:
                                                         </p>

                                                         {{-- Toggle de modo --}}
                                                         <div class="flex rounded-lg overflow-hidden border border-gray-300 text-xs font-bold">
                                                             <label class="flex-1 flex items-center gap-1.5 px-3 py-2 cursor-pointer has-[:checked]:bg-orange-600 has-[:checked]:text-white transition-colors">
                                                                 <input type="radio" name="inscription_mode_{{ $noBye_slug }}" value="bye" checked
                                                                     class="sr-only"
                                                                     onchange="toggleNoBye_{{ $noBye_slug }}(this.value)">
                                                                 <i class="fa-solid fa-moon text-[10px]"></i> 1 Equipo (BYE)
                                                             </label>
                                                             <label class="flex-1 flex items-center gap-1.5 px-3 py-2 cursor-pointer has-[:checked]:bg-orange-600 has-[:checked]:text-white transition-colors border-l border-gray-300">
                                                                 <input type="radio" name="inscription_mode_{{ $noBye_slug }}" value="match"
                                                                     class="sr-only"
                                                                     onchange="toggleNoBye_{{ $noBye_slug }}(this.value)">
                                                                 <i class="fa-solid fa-handshake text-[10px]"></i> 2 Equipos (Partido)
                                                             </label>
                                                         </div>

                                                         {{-- Modo BYE: un solo equipo --}}
                                                         <div id="noBye_bye_{{ $noBye_slug }}" class="space-y-2">
                                                             <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Equipo a inscribir</label>
                                                             <p class="text-xs text-gray-400">El equipo quedará en espera como pase directo (BYE) para la siguiente ronda.</p>
                                                             <div class="flex">
                                                                 <select name="team_id" class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ $noBye_slug }} border-r-0 bg-white">
                                                                     <option value="">-- Selecciona un equipo --</option>
                                                                     @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                         @if(!$tournament->teams->contains($t->id))
                                                                             <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                         @endif
                                                                     @endforeach
                                                                 </select>
                                                                 <button type="button" onclick="openCreateTeamModalFromStandings('{{ $noBye_slug }}', 'team_id')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition flex items-center justify-center" title="Crear nuevo equipo">
                                                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                                 </button>
                                                             </div>
                                                         </div>

                                                         {{-- Modo PARTIDO: dos equipos --}}
                                                         <div id="noBye_match_{{ $noBye_slug }}" class="space-y-3 hidden">
                                                             <p class="text-xs text-gray-400">Los dos equipos jugarán un partido directo entre sí en la Ronda 1.</p>
                                                             <div>
                                                                 <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Equipo 1</label>
                                                                 <div class="flex">
                                                                     <select name="team_id_1" class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ $noBye_slug }} border-r-0 bg-white">
                                                                         <option value="">-- Selecciona el primer equipo --</option>
                                                                         @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                             @if(!$tournament->teams->contains($t->id))
                                                                                 <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                             @endif
                                                                         @endforeach
                                                                     </select>
                                                                     <button type="button" onclick="openCreateTeamModalFromStandings('{{ $noBye_slug }}', 'team_id_1')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition flex items-center justify-center" title="Crear nuevo equipo">
                                                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                                     </button>
                                                                 </div>
                                                             </div>
                                                             <div>
                                                                 <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Equipo 2</label>
                                                                 <div class="flex">
                                                                     <select name="team_id_2" class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ $noBye_slug }} border-r-0 bg-white">
                                                                         <option value="">-- Selecciona el segundo equipo --</option>
                                                                         @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as $t)
                                                                             @if(!$tournament->teams->contains($t->id))
                                                                                 <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                                             @endif
                                                                         @endforeach
                                                                     </select>
                                                                     <button type="button" onclick="openCreateTeamModalFromStandings('{{ $noBye_slug }}', 'team_id_2')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition flex items-center justify-center" title="Crear nuevo equipo">
                                                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                                     </button>
                                                                 </div>
                                                             </div>
                                                         </div>

                                                         <script>
                                                         function toggleNoBye_{{ $noBye_slug }}(mode) {
                                                             const byeDiv   = document.getElementById('noBye_bye_{{ $noBye_slug }}');
                                                             const matchDiv = document.getElementById('noBye_match_{{ $noBye_slug }}');
                                                             // Mostrar/ocultar paneles
                                                             byeDiv.classList.toggle('hidden', mode !== 'bye');
                                                             matchDiv.classList.toggle('hidden', mode !== 'match');
                                                             // Gestionar required según modo activo
                                                             byeDiv.querySelector('select[name="team_id"]').required = (mode === 'bye');
                                                             const s1 = matchDiv.querySelector('select[name="team_id_1"]');
                                                             const s2 = matchDiv.querySelector('select[name="team_id_2"]');
                                                             s1.required = (mode === 'match');
                                                             s2.required = (mode === 'match');
                                                         }
                                                         // Inicializar: modo BYE por defecto
                                                         document.addEventListener('DOMContentLoaded', function() {
                                                             toggleNoBye_{{ $noBye_slug }}('bye');
                                                         });
                                                         </script>
                                                     @endif


                                                     <div class="flex justify-end gap-2 pt-3 border-t">
                                                         <button type="button" onclick="document.getElementById('modalAddNormalLateTeamPlayoffs-{{ Str::slug($groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                         <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Inscribir Equipo(s)</button>
                                                     </div>
                                                 </form>
                                             </dialog>

                                             <!-- Modal para Crear Nuevo Equipo vía AJAX (Playoffs / Eliminatoria Directa) -->
                                             <dialog id="modalCreateTeamFromStandings-{{ Str::slug($groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                 @php
                                                     $parts = explode(' - ', $groupName, 2);
                                                     $groupCategory = trim($parts[0] ?? 'Varonil');
                                                     $groupStrength = trim($parts[1] ?? 'Libre');
                                                 @endphp
                                                 <form onsubmit="submitCreateTeamFromStandings(event, '{{ Str::slug($groupName) }}')" class="space-y-4">
                                                     @csrf
                                                     <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                                                     <input type="hidden" name="category" value="{{ $groupCategory }}">
                                                     <input type="hidden" name="strength" value="{{ $groupStrength }}">
                                                     <input type="hidden" name="status" value="active">
                                                     <input type="hidden" name="skip_double_elim_late" value="1">

                                                     <div class="flex items-center justify-between border-b pb-2">
                                                         <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                             <i class="fa-solid fa-user-plus text-orange-500"></i> Crear Nuevo Equipo
                                                         </h3>
                                                         <button type="button" onclick="document.getElementById('modalCreateTeamFromStandings-{{ Str::slug($groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                     </div>

                                                     <p class="text-xs text-gray-600 leading-relaxed">
                                                         Se creará un nuevo equipo directamente para el torneo <strong>{{ $tournament->name }}</strong>, categoría <strong>{{ $groupCategory }}</strong> y nivel <strong>{{ $groupStrength }}</strong>.
                                                     </p>

                                                     <div>
                                                         <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nombre del Equipo</label>
                                                         <input type="text" name="name" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm animate-pulse-once" placeholder="Ej. Lakers">
                                                     </div>

                                                     <div>
                                                         <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Entrenador (Coach)</label>
                                                         <select name="coach_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm bg-white">
                                                             <option value="">-- Sin Entrenador --</option>
                                                             @foreach ($coaches as $coach)
                                                                 <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                                             @endforeach
                                                         </select>
                                                     </div>

                                                     <div class="flex justify-end gap-2 pt-3 border-t">
                                                         <button type="button" onclick="document.getElementById('modalCreateTeamFromStandings-{{ Str::slug($groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                         <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Crear Equipo</button>
                                                     </div>
                                                 </form>
                                             </dialog>
                                         @endif
                                     </div>

                                    <div class="flex flex-row gap-6 overflow-x-auto pb-6 nba-scroll snap-x rounds-flex items-center">
                                        @foreach($data['playoff_rounds'] ?? [] as $roundIndex => $round)
                                        <div class="flex-shrink-0 w-full md:w-72 snap-center flex flex-col gap-4">

                                            <!-- Título de la Ronda -->
                                            <div class="nba-header rounded">
                                                {{ $round['name'] ?? 'Ronda' }}
                                            </div>

                                            <!-- Lista de Partidos -->
                                            @foreach($round['games'] ?? [] as $game)
                                            <div class="nba-card relative">

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
                                                    <span class="nba-team-score {{ is_numeric($game->local_team_score ?? ($game->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $game->local_team_score ?? ($game->localTeam ? '0' : '-') }}</span>
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
                                                    <span class="nba-team-score {{ is_numeric($game->away_team_score ?? ($game->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty' }}">{{ $game->away_team_score ?? ($game->awayTeam ? '0' : '-') }}</span>
                                                </div>

                                                <!-- Estado del Juego Centrado -->
                                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                    @if(($game->status ?? 'pending') === 'pending')
                                                        <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                    @elseif(($game->status ?? 'pending') === 'playing')
                                                        <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                    @elseif(($game->status ?? 'pending') === 'finished')
                                                        <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach

                                            <!-- BYEs de esta Ronda -->
                                            @php
                                                $roundByes = $playoffByesByRound[$roundIndex] ?? [];
                                            @endphp
                                            @foreach($roundByes as $byeTeamId)
                                                @php
                                                    $byeTeam = ($data['teams'] ?? collect())->firstWhere('id', $byeTeamId);
                                                @endphp
                                                @if($byeTeam)
                                                    <div class="nba-card relative border-l-4 border-l-orange-500 bg-orange-50/10 shadow-md hover:shadow-xl transition-all duration-200 overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                        <div class="absolute right-2 top-2 z-10">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 border border-orange-200">
                                                                BYE
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-3 py-1">
                                                            <img src="{{ asset('storage/' . ($byeTeam->image_path ?? '')) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                            <div class="flex flex-col min-w-0 text-left">
                                                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate">{{ $byeTeam->name }}</span>
                                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Avanza Directo</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 bg-gradient-to-r from-orange-50 to-orange-100/50 text-orange-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-orange-200/60 font-sans">
                                                            ⚡ Pase Automático
                                                        </div>
                                                    </div>
                                                @endif
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

        function openCreateTeamModalFromStandings(groupSlug, targetSelectName) {
            window.lastTargetSelectClass = 'team-select-' + groupSlug;
            window.lastTargetSelectName = targetSelectName;
            document.getElementById('modalCreateTeamFromStandings-' + groupSlug).showModal();
        }

        function submitCreateTeamFromStandings(event, groupSlug) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            fetch('{{ route("teams.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.team) {
                    const selectClass = window.lastTargetSelectClass;
                    const targetName = window.lastTargetSelectName;
                    const selects = document.querySelectorAll('.' + selectClass);
                    
                    selects.forEach(select => {
                        const option = document.createElement('option');
                        option.value = data.team.id;
                        option.textContent = data.team.name;
                        select.appendChild(option);
                        
                        if (select.name === targetName) {
                            select.value = data.team.id;
                        }
                    });

                    document.getElementById('modalCreateTeamFromStandings-' + groupSlug).close();
                    form.reset();
                    alert('Equipo "' + data.team.name + '" creado con éxito.');
                } else {
                    alert('Error al crear el equipo: respuesta inesperada.');
                }
            })
            .catch(err => {
                console.error(err);
                const errorMsg = err.message || (err.errors ? Object.values(err.errors).flat().join('\n') : 'Error desconocido.');
                alert('Error al crear el equipo: ' + errorMsg);
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
            });
        }
    </script>