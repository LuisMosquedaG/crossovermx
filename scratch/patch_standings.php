<?php

$path = 'c:/Users/luism/gemini-work/sistemaTorneos/resources/views/tournaments/standings.blade.php';
$content = file_get_contents($path);

// --- REPLACEMENT 1: calculations block ---
$target1 = "@if(isset(\$data['has_playoffs']) && \$data['has_playoffs'])";
$replacement1 = <<<EOT
@if(isset(\$data['has_playoffs']) && \$data['has_playoffs'])
                                 @php
                                     // Calcular Byes de playoffs dinámicamente
                                     \$activePlayoffTeams = (\$data['teams'] ?? collect())->pluck('id')->toArray();
                                     \$playoffByesByRound = [];
                                     foreach (\$data['playoff_rounds'] ?? [] as \$rIdx => \$rData) {
                                         \$rGames = \$rData['games'] ?? [];
                                         \$played = [];
                                         \$winners = [];
                                         foreach (\$rGames as \$g) {
                                             if (\$g->local_team_id) \$played[] = (int) \$g->local_team_id;
                                             if (\$g->away_team_id) \$played[] = (int) \$g->away_team_id;
                                             if (\$g->status === 'finished') {
                                                 \$winners[] = (\$g->local_team_score > \$g->away_team_score) ? (int) \$g->local_team_id : (int) \$g->away_team_id;
                                             }
                                         }
                                         \$byes = array_values(array_diff(\$activePlayoffTeams, \$played));
                                         \$playoffByesByRound[\$rIdx] = \$byes;
                                         \$activePlayoffTeams = array_merge(\$winners, \$byes);
                                     }
                                 @endphp
EOT;

if (strpos($content, $target1) === false) {
    echo "ERROR: Target 1 not found!\n";
    exit(1);
}
$content = str_replace($target1, $replacement1, $content);

// --- REPLACEMENT 2: Title and action buttons ---
$pattern2 = '#\s*<!-- 1\. TÍTULO -->\s*<div class="mb-6 border-b border-gray-200 pb-3 flex justify-center items-center">\s*<h4 class="text-xl font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">\s*<i class="fa-solid fa-trophy text-gray-600"></i>\s*Fase\s+Eliminatoria\s*</h4>\s*</div>#';

$replacement2 = <<<EOT


                                     <!-- 1. TÍTULO Y ACCIONES -->
                                     <div class="mb-6 border-b border-gray-200 pb-3 flex flex-wrap justify-between items-center gap-4">
                                         <h4 class="text-xl font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                             <i class="fa-solid fa-trophy text-yellow-500"></i> Fase Eliminatoria
                                         </h4>

                                         @php
                                             \$tSettings = \$tournament->settings ? \$tournament->settings->settings : [];
                                             \$tType = \$tSettings['tournament_type'] ?? 'round_robin';
                                             \$teamIdsInGroup = \$data['team_ids'] ?? [];
                                             \$hasFinishedPlayoffs = \App\Models\Game::where('tournament_id', \$tournament->id)
                                                 ->where('is_playoff', true)
                                                 ->where('status', 'finished')
                                                 ->where(function(\$query) use (\$teamIdsInGroup) {
                                                     \$query->whereIn('local_team_id', \$teamIdsInGroup)
                                                           ->orWhereIn('away_team_id', \$teamIdsInGroup);
                                                 })
                                                 ->exists();
                                             \$isElimRound1 = !\$hasFinishedPlayoffs;
                                             \$playoffByes = \$tSettings['current_byes'][\$groupName] ?? [];
                                             \$hasPlayoffByes = !empty(\$playoffByes);
                                         @endphp

                                         @if((\$tType === 'single_elimination' || \$tType === 'elimination') && (\$tournament->status === 'active' || \$tournament->status === 'in_progress'))
                                             <div class="flex flex-wrap gap-2 items-center">
                                                 @if(\$isElimRound1)
                                                     <button type="button" onclick="document.getElementById('modalAddNormalLateTeamPlayoffs-{{ Str::slug(\$groupName) }}').showModal()" class="px-3.5 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg font-bold text-xs uppercase tracking-wider shadow-sm transition flex items-center gap-2">
                                                         <i class="fa-solid fa-user-plus text-gray-500"></i> Inscribir Equipo Normal
                                                     </button>
                                                 @endif

                                                 <a href="{{ route('tournaments.schedule', ['tournament' => \$tournament, 'group' => \$groupName]) }}" 
                                                    class="px-3.5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow transition flex items-center gap-2"
                                                    title="Ver calendario de este grupo">
                                                     <i class="fa-solid fa-calendar-days"></i> Ver Calendario
                                                 </a>
                                             </div>

                                             <!-- Modal de Inscripción Normal (Playoffs / Eliminatoria Directa) -->
                                             <dialog id="modalAddNormalLateTeamPlayoffs-{{ Str::slug(\$groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                 <form method="POST" action="{{ route('tournaments.add-normal-late-team', \$tournament) }}" class="space-y-4">
                                                     @csrf
                                                     <input type="hidden" name="category_group" value="{{ \$groupName }}">

                                                     <div class="flex items-center justify-between border-b pb-2">
                                                         <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                             <i class="fa-solid fa-user-plus text-orange-500"></i> Inscribir Equipo Normal
                                                         </h3>
                                                         <button type="button" onclick="document.getElementById('modalAddNormalLateTeamPlayoffs-{{ Str::slug(\$groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                     </div>

                                                     @if(\$hasPlayoffByes)
                                                         <p class="text-xs text-gray-600 leading-relaxed">
                                                             Hay descansos (BYEs) disponibles en la Ronda 1. El nuevo equipo se integrará ocupando el lugar de uno de ellos y jugará en esta ronda.
                                                         </p>
                                                         <div>
                                                             <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seleccionar Equipo a Integrar</label>
                                                             <div class="flex">
                                                                 <select name="team_id" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug(\$groupName) }} border-r-0 bg-white">
                                                                     <option value="">-- Selecciona un equipo --</option>
                                                                     @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as \$t)
                                                                         @if(!\$tournament->teams->contains(\$t->id))
                                                                             <option value="{{ \$t->id }}">{{ \$t->name }}</option>
                                                                         @endif
                                                                     @endforeach
                                                                 </select>
                                                                 <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug(\$groupName) }}', 'team_id')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                     </svg>
                                                                 </button>
                                                             </div>
                                                         </div>
                                                     @else
                                                         <p class="text-xs text-gray-600 leading-relaxed text-red-600 font-medium">
                                                             No hay descansos (BYEs) en la Ronda 1. Se deben seleccionar <strong>dos equipos nuevos</strong> que jugarán un enfrentamiento directo entre sí en esta ronda.
                                                         </p>
                                                         <div class="space-y-3">
                                                             <div>
                                                                 <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Equipo 1</label>
                                                                 <div class="flex">
                                                                     <select name="team_id_1" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug(\$groupName) }} border-r-0 bg-white">
                                                                         <option value="">-- Selecciona el primer equipo --</option>
                                                                         @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as \$t)
                                                                             @if(!\$tournament->teams->contains(\$t->id))
                                                                                 <option value="{{ \$t->id }}">{{ \$t->name }}</option>
                                                                             @endif
                                                                         @endforeach
                                                                     </select>
                                                                     <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug(\$groupName) }}', 'team_id_1')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                         </svg>
                                                                     </button>
                                                                 </div>
                                                             </div>
                                                             <div>
                                                                 <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Equipo 2</label>
                                                                 <div class="flex">
                                                                     <select name="team_id_2" required class="flex-1 border-gray-300 rounded-l-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm team-select-{{ Str::slug(\$groupName) }} border-r-0 bg-white">
                                                                         <option value="">-- Selecciona el segundo equipo --</option>
                                                                         @foreach(\App\Models\Team::where('client_id', auth()->user()->client_id ?? 1)->orderBy('name')->get() as \$t)
                                                                             @if(!\$tournament->teams->contains(\$t->id))
                                                                                 <option value="{{ \$t->id }}">{{ \$t->name }}</option>
                                                                             @endif
                                                                         @endforeach
                                                                     </select>
                                                                     <button type="button" onclick="openCreateTeamModalFromStandings('{{ Str::slug(\$groupName) }}', 'team_id_2')" class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-lg border border-l-0 border-orange-600 px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center" title="Crear nuevo equipo">
                                                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                                         </svg>
                                                                     </button>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     @endif

                                                     <div class="flex justify-end gap-2 pt-3 border-t">
                                                         <button type="button" onclick="document.getElementById('modalAddNormalLateTeamPlayoffs-{{ Str::slug(\$groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                         <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Inscribir Equipo(s)</button>
                                                     </div>
                                                 </form>
                                             </dialog>

                                             <!-- Modal para Crear Nuevo Equipo vía AJAX (Playoffs / Eliminatoria Directa) -->
                                             <dialog id="modalCreateTeamFromStandings-{{ Str::slug(\$groupName) }}" class="p-6 rounded-2xl shadow-2xl backdrop:bg-gray-900/50 max-w-md w-full border border-gray-200 text-left">
                                                 @php
                                                     \$parts = explode(' - ', \$groupName, 2);
                                                     \$groupCategory = trim(\$parts[0] ?? 'Varonil');
                                                     \$groupStrength = trim(\$parts[1] ?? 'Libre');
                                                 @endphp
                                                 <form onsubmit="submitCreateTeamFromStandings(event, '{{ Str::slug(\$groupName) }}')" class="space-y-4">
                                                     @csrf
                                                     <input type="hidden" name="tournament_id" value="{{ \$tournament->id }}">
                                                     <input type="hidden" name="category" value="{{ \$groupCategory }}">
                                                     <input type="hidden" name="strength" value="{{ \$groupStrength }}">
                                                     <input type="hidden" name="status" value="active">
                                                     <input type="hidden" name="skip_double_elim_late" value="1">

                                                     <div class="flex items-center justify-between border-b pb-2">
                                                         <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                                             <i class="fa-solid fa-user-plus text-orange-500"></i> Crear Nuevo Equipo
                                                         </h3>
                                                         <button type="button" onclick="document.getElementById('modalCreateTeamFromStandings-{{ Str::slug(\$groupName) }}').close()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                                                     </div>

                                                     <p class="text-xs text-gray-600 leading-relaxed">
                                                         Se creará un nuevo equipo directamente para el torneo <strong>{{ \$tournament->name }}</strong>, categoría <strong>{{ \$groupCategory }}</strong> y nivel <strong>{{ \$groupStrength }}</strong>.
                                                     </p>

                                                     <div>
                                                         <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nombre del Equipo</label>
                                                         <input type="text" name="name" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm animate-pulse-once" placeholder="Ej. Lakers">
                                                     </div>

                                                     <div>
                                                         <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Entrenador (Coach)</label>
                                                         <select name="coach_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm bg-white">
                                                             <option value="">-- Sin Entrenador --</option>
                                                             @foreach (\$coaches as \$coach)
                                                                 <option value="{{ \$coach->id }}">{{ \$coach->name }}</option>
                                                             @endforeach
                                                         </select>
                                                     </div>

                                                     <div class="flex justify-end gap-2 pt-3 border-t">
                                                         <button type="button" onclick="document.getElementById('modalCreateTeamFromStandings-{{ Str::slug(\$groupName) }}').close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-300">Cancelar</button>
                                                         <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md">Crear Equipo</button>
                                                     </div>
                                                 </form>
                                             </dialog>
                                         @endif
                                     </div>
EOT;

$count = 0;
$content = preg_replace($pattern2, $replacement2, $content, 1, $count);
if ($count !== 1) {
    echo "ERROR: Target 2 not found via regex!\n";
    exit(1);
}

// --- REPLACEMENT 3: Loop index key modification ---
$target3 = '@foreach($data[\'playoff_rounds\'] ?? [] as $round)';
$replacement3 = '@foreach($data[\'playoff_rounds\'] ?? [] as $roundIndex => $round)';

if (strpos($content, $target3) === false) {
    echo "ERROR: Target 3 not found!\n";
    exit(1);
}
$content = str_replace($target3, $replacement3, $content);

// --- REPLACEMENT 4: BYEs rendering loop insertion ---
$pattern4 = '#\s*<!-- Estado del Juego Centrado -->\s*<div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">\s*@if\(\(\$game->status\s+\?\?\s+\'pending\'\)\s+===\s+\'pending\'\)\s*<span class="nba-status-badge nba-status-pending">Pendiente</span>\s*@elseif\(\(\$game->status\s+\?\?\s+\'pending\'\)\s+===\s+\'playing\'\)\s*<span class="nba-status-badge nba-status-playing">En Juego</span>\s*@elseif\(\(\$game->status\s+\?\?\s+\'pending\'\)\s+===\s+\'finished\'\)\s*<span class="nba-status-badge nba-status-finished">Finalizado</span>\s*@endif\s*</div>\s*</div>\s*@endforeach\s*</div>\s*@endforeach#';

$replacement4 = <<<EOT


                                                <!-- Estado del Juego Centrado -->
                                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                    @if((\$game->status ?? 'pending') === 'pending')
                                                        <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                    @elseif((\$game->status ?? 'pending') === 'playing')
                                                        <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                    @elseif((\$game->status ?? 'pending') === 'finished')
                                                        <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach

                                            <!-- BYEs de esta Ronda -->
                                            @php
                                                \$roundByes = \$playoffByesByRound[\$roundIndex] ?? [];
                                            @endphp
                                            @foreach(\$roundByes as \$byeTeamId)
                                                @php
                                                    \$byeTeam = (\$data['teams'] ?? collect())->firstWhere('id', \$byeTeamId);
                                                @endphp
                                                @if(\$byeTeam)
                                                    <div class="nba-card relative border-l-4 border-l-orange-500 bg-orange-50/10 shadow-md hover:shadow-xl transition-all duration-200 overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                        <div class="absolute right-2 top-2 z-10">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 border border-orange-200">
                                                                BYE
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-3 py-1">
                                                            <img src="{{ asset('storage/' . (\$byeTeam->image_path ?? '')) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                            <div class="flex flex-col min-w-0 text-left">
                                                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate">{{ \$byeTeam->name }}</span>
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
EOT;

$count = 0;
$content = preg_replace($pattern4, $replacement4, $content, 1, $count);
if ($count !== 1) {
    echo "ERROR: Target 4 not found via regex!\n";
    exit(1);
}

file_put_contents($path, $content);
echo "SUCCESS!\n";
