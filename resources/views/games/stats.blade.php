<x-app-layout>

    <div class="py-4 px-2">
        <div class="max-w-7xl mx-auto">
            
        <div class="mb-4">
            <!-- Botón Volver (Diseño Unificado: Gris/Negro) -->
            <!-- La lógica del href decide si va a Torneos o Partidos Manuales -->
            <a href="{{ $game->tournament_id ? route('tournaments.schedule', $game->tournament_id) : route('games.index') }}" 
               class="w-full md:w-auto shrink-0 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out flex items-center">
                <span class="mr-2">←</span>
                Volver
            </a>
        </div>
            <!-- Encabezado del Partido (AJUSTADO AL BORDE) -->
            <!-- h-36 fija la altura, overflow-hidden y rounded-lg gestionan el borde naranja -->
            <div class="bg-slate-50 border border-orange-500 rounded-lg shadow-sm mb-6 relative overflow-hidden h-36">
                <!-- Patrón de cubos gris tenue -->
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                
                <!-- Contenedor interno SIN padding (p-4) para que el logo toque el borde -->
                <div class="relative z-10 grid grid-cols-3 h-full">
                    
                    <!-- Equipo Local -->
                    <!-- rounded-l-lg coincide perfectamente con el borde padre -->
                    <div class="relative h-full flex items-end p-4 overflow-hidden rounded-l-lg bg-slate-100">
                        @if($game->localTeam->image_path)
                            <!-- Logo ajustado al borde (inset-0) -->
                            <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" 
                                class="absolute inset-0 w-full h-full object-cover opacity-30 z-0" alt="Logo Local">
                        @else
                            <!-- Letra gigante de fondo -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-5 z-0">
                                <span class="text-9xl font-black text-slate-800">{{ strtoupper(substr($game->localTeam->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Marcador Central -->
                    <!-- px-4 da espacio lateral para que el marcador no pegue a las imágenes -->
                    <div class="flex flex-col items-center justify-center px-4 border-l border-r border-slate-200">
                        <div class="text-3xl font-black text-slate-900 leading-none tracking-tight">
                            <span>{{ $game->local_team_score }}</span>
                            <span class="text-slate-300 mx-1">-</span>
                            <span>{{ $game->away_team_score }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium tracking-wide uppercase mt-1">Final</span>
                    </div>

                    <!-- Equipo Visitante -->
                    <!-- rounded-r-lg coincide perfectamente con el borde padre -->
                    <div class="relative h-full flex items-end p-4 overflow-hidden rounded-r-lg bg-slate-100">
                        @if($game->awayTeam->image_path)
                            <!-- Logo ajustado al borde (inset-0) -->
                            <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" 
                                class="absolute inset-0 w-full h-full object-cover opacity-30 z-0" alt="Logo Visitante">
                        @else
                            <!-- Letra gigante de fondo -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-5 z-0">
                                <span class="text-9xl font-black text-slate-800">{{ strtoupper(substr($game->awayTeam->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ... (Encabezado del partido se mantiene igual) ... -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Estadísticas Local -->
                <div class="bg-white p-4 rounded-lg shadow-md ring-1 ring-blue-300">
                    <h3 class="text-lg font-bold text-blue-600 mb-4 border-b pb-2 text-center">{{ $game->localTeam->name }}</h3>
                    @if(empty($localStats))
                        <p class="text-gray-500 text-sm">No hay estadísticas registradas.</p>
                    @else
                        <div class="overflow-x-auto">
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
                                <tbody>
                                    @foreach($localStats as $playerId => $stats)
                                        @if(isset($players[$playerId]))
                                            @php 
                                                $player = $players[$playerId];
                                                $totalPoints = ($stats['points1'] ?? 0) + ($stats['points2'] * 2) + ($stats['points3'] * 3);
                                            @endphp
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-3 py-2 font-medium">{{ $player->name }} ({{ $player->number ?? 'N/A' }})</td>
                                                <td class="px-3 py-2 text-center text-gray-600">{{ $stats['points1'] ?? 0 }}</td>
                                                <td class="px-3 py-2 text-center text-gray-600">{{ $stats['points2'] ?? 0 }}</td>
                                                <td class="px-3 py-2 text-center text-gray-600">{{ $stats['points3'] ?? 0 }}</td>
                                                <td class="px-3 py-2 text-center font-bold text-blue-700">{{ $totalPoints }}</td>
                                                <td class="px-3 py-2 text-center text-orange-600">{{ $stats['fouls'] ?? 0 }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Estadísticas Visitante -->
                <div class="bg-white p-4 rounded-lg shadow-md ring-1 ring-red-300">
                    <h3 class="text-lg font-bold text-red-600 mb-4 border-b pb-2 text-center">{{ $game->awayTeam->name }}</h3>
                    @if(empty($awayStats))
                        <p class="text-gray-500 text-sm">No hay estadísticas registradas.</p>
                    @else
                        <div class="overflow-x-auto">
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
                                <tbody>
                                    @foreach($awayStats as $playerId => $stats)
                                        @if(isset($players[$playerId]))
                                            @php 
                                                $player = $players[$playerId];
                                                $totalPoints = ($stats['points1'] ?? 0) + ($stats['points2'] * 2) + ($stats['points3'] * 3);
                                            @endphp
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-3 py-2 font-medium">{{ $player->name }} ({{ $player->number ?? 'N/A' }})</td>
                                                <td class="px-3 py-2 text-center text-gray-600">{{ $stats['points1'] ?? 0 }}</td>
                                                <td class="px-3 py-2 text-center text-gray-600">{{ $stats['points2'] ?? 0 }}</td>
                                                <td class="px-3 py-2 text-center text-gray-600">{{ $stats['points3'] ?? 0 }}</td>
                                                <td class="px-3 py-2 text-center font-bold text-red-700">{{ $totalPoints }}</td>
                                                <td class="px-3 py-2 text-center text-orange-600">{{ $stats['fouls'] ?? 0 }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Sección Periodos (Simplificada usando variables del controlador) -->
            <div class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- TARJETA EQUIPO LOCAL -->
                    <div class="bg-white p-4 rounded-lg shadow-md ring-1 ring-blue-300">
                        <h3 class="text-lg font-bold text-blue-600 mb-4 border-b pb-2 text-center">{{ $game->localTeam->name }}</h3>
                        <table class="min-w-full text-sm text-center">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="px-3 py-2">Periodo</th>
                                    <th class="px-3 py-2 text-blue-700">Puntos</th>
                                    <th class="px-3 py-2 text-orange-600">Faltas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $totalLocalP = 0; $totalLocalF = 0; @endphp
                                @foreach($localPeriodStats as $period => $stats)
                                    @php 
                                        $totalLocalP += $stats['points'];
                                        $totalLocalF += $stats['fouls'];
                                    @endphp
                                    <tr class="{{ $period % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-3 py-2 font-medium">Q{{ $period }}</td>
                                        <td class="px-3 py-2 font-bold text-blue-600">{{ $stats['points'] }}</td>
                                        <td class="px-3 py-2 text-orange-600">{{ $stats['fouls'] }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-800 text-white font-bold border-t-2 border-gray-900">
                                    <td class="px-3 py-2">TOTAL</td>
                                    <td class="px-3 py-2">{{ $totalLocalP }}</td>
                                    <td class="px-3 py-2">{{ $totalLocalF }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- TARJETA EQUIPO VISITANTE -->
                    <div class="bg-white p-4 rounded-lg shadow-md ring-1 ring-red-300">
                        <h3 class="text-lg font-bold text-red-600 mb-4 border-b pb-2 text-center">{{ $game->awayTeam->name }}</h3>
                        <table class="min-w-full text-sm text-center">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="px-3 py-2">Periodo</th>
                                    <th class="px-3 py-2 text-red-700">Puntos</th>
                                    <th class="px-3 py-2 text-orange-600">Faltas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $totalAwayP = 0; $totalAwayF = 0; @endphp
                                @foreach($awayPeriodStats as $period => $stats)
                                    @php 
                                        $totalAwayP += $stats['points'];
                                        $totalAwayF += $stats['fouls'];
                                    @endphp
                                    <tr class="{{ $period % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-3 py-2 font-medium">Q{{ $period }}</td>
                                        <td class="px-3 py-2 font-bold text-red-600">{{ $stats['points'] }}</td>
                                        <td class="px-3 py-2 text-orange-600">{{ $stats['fouls'] }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-800 text-white font-bold border-t-2 border-gray-900">
                                    <td class="px-3 py-2">TOTAL</td>
                                    <td class="px-3 py-2">{{ $totalAwayP }}</td>
                                    <td class="px-3 py-2">{{ $totalAwayF }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cronología -->
            <div class="w-full max-w-4xl mx-auto mt-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pl-2">Cronología del Partido</h3>
                <div class="bg-white rounded-lg shadow-md overflow-hidden ring-1 ring-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left table-fixed">
                            <thead class="bg-gray-800 text-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-center w-16">Q</th>
                                    <th class="px-4 py-3 text-center w-24">Tiempo</th>
                                    <th class="px-4 py-3 w-32">Equipo</th>
                                    <th class="px-4 py-3">Jugador</th>
                                    <th class="px-4 py-3 w-28">Acción</th>
                                    <th class="px-4 py-3 text-center w-20">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php
                                    // Definimos mapas de nombres para las faltas
                                    $foulLabels = [
                                        'foul_personal' => 'Falta Personal',
                                        'foul_technical' => 'Falta Técnica',
                                        'foul_unsportsmanlike' => 'Falta Antideportiva',
                                        'foul_disqualifying' => 'Falta Descalificatoria'
                                    ];

                                    // Filtro actualizado
                                    $chronologicalActions = $game->actions
                                        ->filter(function($action) {
                                            return in_array($action->action_type, ['point_scored', 'overtime_started', 'compensation_added']) 
                                                || strpos($action->action_type, 'foul') !== false;
                                        })
                                        ->sortBy('created_at');
                                @endphp
                                @forelse($chronologicalActions as $action)
                                    @php
                                        $isSystem = in_array($action->action_type, ['overtime_started', 'compensation_added']);
                                        $isLocal = $action->team_side == 'local';

                                        if ($isSystem) {
                                            $teamName = 'Sistema';
                                            $teamTextClass = 'text-gray-600 font-bold';
                                            $playerName = 'Administrador / Árbitro';
                                        } else {
                                            $teamName = $isLocal ? $game->localTeam->name : $game->awayTeam->name;
                                            $teamTextClass = $isLocal ? 'text-blue-600 font-bold' : 'text-red-600 font-bold';
                                            
                                            $player = isset($players[$action->player_id]) ? $players[$action->player_id] : null;
                                            $playerName = $player ? $player->name . ' (' . ($player->number ?? '-') . ')' : 'N/A';
                                        }

                                        $gameTimeDisplay = isset($action->seconds) ? 
                                            str_pad(floor($action->seconds / 60), 2, '0', STR_PAD_LEFT) . ':' . 
                                            str_pad($action->seconds % 60, 2, '0', STR_PAD_LEFT) : '--:--';
                                        
                                        // Lógica mejorada de etiquetas
                                        if($action->action_type == 'point_scored') {
                                            $actionLabel = 'Anotación';
                                            $badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                                            $valueDisplay = '+' . $action->value;
                                        } elseif (array_key_exists($action->action_type, $foulLabels)) {
                                            // USAMOS EL MAPA DE FALTAS
                                            $actionLabel = $foulLabels[$action->action_type];
                                            $badgeClass = 'bg-orange-100 text-orange-800 border border-orange-200';
                                            $valueDisplay = ''; // Vacío para faltas (más limpio)
                                        } elseif ($action->action_type == 'overtime_started') {
                                            $actionLabel = 'Tiempo Extra';
                                            $badgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                                            $valueDisplay = $action->value . ' min';
                                        } elseif ($action->action_type == 'compensation_added') {
                                            $actionLabel = 'Compensación';
                                            $badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                            $valueDisplay = '+' . $action->value . ' min';
                                        } else {
                                            $actionLabel = 'Evento';
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            $valueDisplay = '-';
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center text-gray-600 font-mono">Q{{ $action->period }}</td>
                                        <td class="px-4 py-3 text-center text-gray-500 font-mono">
                                            <span class="convert-to-local-time" data-time="{{ $action->created_at->toIso8601String() }}">...</span>
                                        </td>
                                        <td class="px-4 py-3 {{ $teamTextClass }} truncate" title="{{ $teamName }}">{{ $teamName }}</td>
                                        <td class="px-4 py-3 text-gray-700 truncate" title="{{ $playerName }}">{{ $playerName }}</td>
                                        <td class="px-4 py-3"><span class="{{ $badgeClass }} px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">{{ $actionLabel }}</span></td>
                                        <td class="px-4 py-3 text-center font-mono font-bold text-gray-800">{{ $valueDisplay }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500 bg-gray-50">No hay registros de jugadas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- SCRIPT PARA CONVERTIR HORAS DEL SERVIDOR A HORA LOCAL ---
        document.addEventListener("DOMContentLoaded", function() {
            // Buscamos todos los elementos que tengan la clase especial
            const timeElements = document.querySelectorAll('.convert-to-local-time');
            
            timeElements.forEach(el => {
                // Obtenemos la fecha en formato ISO desde el atributo data-time
                const isoTime = el.getAttribute('data-time');
                
                if (isoTime) {
                    // El navegador convierte automáticamente la fecha ISO a la zona horaria del usuario
                    const date = new Date(isoTime);
                    
                    // Formateamos a HH:MM:SS (o a.m./p.m.)
                    el.textContent = date.toLocaleTimeString('es-MX', { hour12: false }); 
                    // Si quieres que aparezca AM/PM, cambia 'hour12: false' por 'hour12: true'
                }
            });
        });
    </script>

</x-app-layout>