<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="w-[96%] md:w-[90%] mx-auto space-y-8 mb-[10vh]">

            <!-- HEADER BANNER COACH (FONDO BLANCO Y LOGO EN GRANDE TRANSPARENTE DE FONDO) -->
            @php
                $firstTeamWithLogo = $coachTeams->first(fn($t) => !empty($t->image_path));
            @endphp
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
                <!-- LOGO GIGANTE TRANSPARENTE Y VERTICALMENTE CENTRADO DE FONDO -->
                @if($firstTeamWithLogo && $firstTeamWithLogo->image_path)
                    <div class="absolute -right-16 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none z-0">
                        <img src="{{ asset('storage/' . $firstTeamWithLogo->image_path) }}" alt="" class="w-[28rem] h-[28rem] md:w-[42rem] md:h-[42rem] object-contain">
                    </div>
                @endif

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- IZQUIERDA: MENSAJE DE BIENVENIDA -->
                    <div>
                        <span class="inline-block px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-xs font-bold uppercase tracking-widest mb-2 border border-orange-200">
                            🏀 Panel de Entrenador
                        </span>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">
                            ¡Bienvenido, Coach {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Estadísticas en tiempo real de tus equipos y rendimiento individual de jugadores.
                        </p>
                    </div>
                    
                    <!-- DERECHA: RESUMEN DE EQUIPOS Y JUGADORES (MÁS PEQUEÑO) -->
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="flex items-center gap-2 bg-gray-50/80 backdrop-blur-sm p-2 px-3 rounded-xl border border-gray-200 shadow-none text-xs">
                            <div class="text-center px-2.5 border-r border-gray-200">
                                <span class="block text-xl font-black text-gray-900 leading-tight">{{ count($coachTeams) }}</span>
                                <span class="text-[9px] text-gray-500 uppercase tracking-wider font-bold">Equipos</span>
                            </div>
                            <div class="text-center px-2.5">
                                <span class="block text-xl font-black text-orange-600 leading-tight">
                                    {{ $coachTeams->sum(fn($t) => $t->players->count()) }}
                                </span>
                                <span class="text-[9px] text-gray-500 uppercase tracking-wider font-bold">Jugadores</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETAS DE DESTACADOS (4 COLUMNAS - ALTURA REDUCIDA A LA MITAD) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- 1. JUEGO CON MAYOR PUNTUACIÓN GANADO -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                            🏆 Mayor Victoria
                        </span>
                        <span class="text-[10px] text-gray-400 font-medium">{{ $highestScoringWin['date'] ?? '' }}</span>
                    </div>

                    @if($highestScoringWin)
                        <div class="my-auto py-1">
                            <div class="flex items-center justify-between gap-2 text-center">
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[11px] font-bold text-gray-700 truncate" title="{{ $highestScoringWin['coach_team_name'] }}">{{ $highestScoringWin['coach_team_name'] }}</span>
                                    <span class="text-2xl font-black text-emerald-600 leading-tight">{{ $highestScoringWin['coach_score'] }}</span>
                                </div>
                                <span class="text-xs font-black text-gray-300">VS</span>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[11px] font-bold text-gray-500 truncate" title="{{ $highestScoringWin['opponent_team_name'] }}">{{ $highestScoringWin['opponent_team_name'] }}</span>
                                    <span class="text-2xl font-black text-gray-400 leading-tight">{{ $highestScoringWin['opponent_score'] }}</span>
                                </div>
                            </div>
                            <div class="mt-1 text-center">
                                <span class="inline-block text-[10px] font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full truncate max-w-full">
                                    {{ $highestScoringWin['tournament_name'] }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">Sin partidos ganados.</p>
                        </div>
                    @endif
                </div>

                <!-- 2. JUGADOR CON MÁS PUNTOS EN UN PARTIDO (RÉCORD INDIVIDUAL) -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                            ⚡ Récord Partido
                        </span>
                    </div>

                    @if($topSingleGamePlayer)
                        <div class="flex items-center gap-3 my-auto py-1">
                            @if($topSingleGamePlayer->image_path)
                                <img src="{{ asset('storage/' . $topSingleGamePlayer->image_path) }}" alt="{{ $topSingleGamePlayer->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                            @elseif($topSingleGamePlayer->gender === 'hombre')
                                <img src="{{ asset('images/hombre.png') }}" alt="{{ $topSingleGamePlayer->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                            @elseif($topSingleGamePlayer->gender === 'mujer')
                                <img src="{{ asset('images/mujer.png') }}" alt="{{ $topSingleGamePlayer->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-full bg-amber-100 border-2 border-amber-300 flex items-center justify-center text-amber-700 font-bold text-sm shrink-0">
                                    {{ substr($topSingleGamePlayer->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-gray-900 text-xs truncate">
                                    {{ $topSingleGamePlayer->name }}
                                </h4>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-xl font-black text-amber-600 leading-none">{{ $topSingleGamePlayer->points_in_game }}</span>
                                    <span class="text-[10px] font-bold text-gray-400">PTS</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">Sin datos de anotación.</p>
                        </div>
                    @endif
                </div>

                <!-- 3. PRÓXIMO JUEGO (NUEVA TARJETA) -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">
                            📅 Próximo Juego
                        </span>
                        @if($upcomingGame && $upcomingGame->date_time)
                            <span class="text-[10px] text-indigo-600 font-bold">{{ $upcomingGame->date_time->format('d/m H:i') }}</span>
                        @endif
                    </div>

                    @if($upcomingGame)
                        <div class="my-auto py-1 text-center">
                            <div class="text-xs font-extrabold text-gray-900 truncate">
                                <span class="text-blue-600">{{ $upcomingGame->localTeam->name ?? 'Local' }}</span>
                                <span class="text-gray-400 mx-1">vs</span>
                                <span class="text-red-600">{{ $upcomingGame->awayTeam->name ?? 'Visitante' }}</span>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1 truncate">
                                📍 {{ $upcomingGame->court->name ?? 'Cancha por definir' }}
                            </p>
                        </div>
                    @else
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">No hay juegos programados.</p>
                        </div>
                    @endif
                </div>

                <!-- 4. RESUMEN DE MIS EQUIPOS -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                            📋 Equipos Asignados
                        </span>
                    </div>

                    @if(count($coachTeams) > 0)
                        <div class="space-y-1 max-h-20 overflow-y-auto my-auto pr-1">
                            @foreach($coachTeams as $t)
                                <div class="flex items-center justify-between p-1.5 rounded-lg bg-gray-50 border border-gray-100 text-[11px]">
                                    <span class="font-bold text-gray-800 truncate max-w-[110px]">{{ $t->name }}</span>
                                    <span class="px-1.5 py-0.2 rounded-full bg-blue-100 text-blue-800 font-bold text-[9px]">
                                        {{ $t->players->count() }} Jug.
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">No tienes equipos a tu cargo.</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- SECCIÓN 1: GRÁFICA DE PUNTOS POR JUGADOR CON ROTACIÓN DE 10 SEGUNDOS POR EQUIPO -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-200 shadow-sm">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 border-b pb-4 border-gray-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-bold text-gray-900">
                                📊 Puntos Totales por Jugador
                            </h3>
                            <span id="rotationBadge" class="hidden inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200 animate-pulse">
                                🔄 Rotando cada 10s
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Comparativa de anotación total acumulada por cada jugador de tu equipo.
                        </p>
                    </div>

                    <!-- SELECTOR MANUAL O INDICADOR DE ROTACIÓN -->
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <select id="teamChartSelector" onchange="manualTeamSelect(this.value)" class="text-xs font-bold text-gray-700 bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm w-full md:w-auto">
                            <!-- Opciones inyectadas por JS -->
                        </select>
                    </div>
                </div>

                <!-- CONTENEDOR DE LA GRÁFICA -->
                <div class="relative w-full h-[340px] md:h-[400px]">
                    <canvas id="playersPointsChart"></canvas>
                </div>
            </div>

            <!-- SECCIÓN LEADERBOARDS: TOP 5 PUNTOS & TOP 5 FALTAS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- TOP 5 JUGADORES CON MÁS PUNTOS -->
                <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            🔥 Top 5 Jugadores (Puntos Totales)
                        </h3>
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full border border-orange-200">
                            Mis Equipos
                        </span>
                    </div>

                    @if($top5Scorers->count() > 0)
                        <div class="space-y-3">
                            @foreach($top5Scorers as $index => $scorer)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:border-orange-300 transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span @class([
                                            'w-7 h-7 rounded-full flex items-center justify-center font-black text-xs shrink-0',
                                            'bg-amber-400 text-amber-950' => $index === 0,
                                            'bg-slate-300 text-slate-800' => $index === 1,
                                            'bg-amber-600 text-amber-50' => $index === 2,
                                            'bg-gray-200 text-gray-700' => $index > 2,
                                        ])>
                                            {{ $index + 1 }}
                                        </span>
                                        @if($scorer->image_path)
                                            <img src="{{ asset('storage/' . $scorer->image_path) }}" alt="{{ $scorer->name }}" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        @elseif($scorer->gender === 'hombre')
                                            <img src="{{ asset('images/hombre.png') }}" alt="{{ $scorer->name }}" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        @elseif($scorer->gender === 'mujer')
                                            <img src="{{ asset('images/mujer.png') }}" alt="{{ $scorer->name }}" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 font-bold flex items-center justify-center text-sm shrink-0">
                                                {{ substr($scorer->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 text-sm truncate">
                                                {{ $scorer->name }}
                                                @if($scorer->number)
                                                    <span class="text-[10px] font-bold text-gray-500">#{{ $scorer->number }}</span>
                                                @endif
                                            </h4>
                                            <p class="text-xs text-gray-500 truncate">{{ $scorer->team_name }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0 pl-2">
                                        <span class="text-xl font-black text-orange-600">{{ $scorer->total_points }}</span>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">PTS</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <i class="fa-solid fa-chart-line text-3xl mb-2 text-gray-300"></i>
                            <p class="text-xs font-medium">Aún no hay puntos registrados para tus jugadores.</p>
                        </div>
                    @endif
                </div>

                <!-- TOP 5 JUGADORES CON MÁS FALTAS -->
                <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            ⚠️ Top 5 Jugadores con Más Faltas
                        </h3>
                        <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200">
                            Acumulado
                        </span>
                    </div>

                    @if($top5Fouls->count() > 0)
                        <div class="space-y-3">
                            @foreach($top5Fouls as $index => $foulPlayer)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:border-rose-300 transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-rose-100 text-rose-800 flex items-center justify-center font-black text-xs shrink-0 border border-rose-200">
                                            {{ $index + 1 }}
                                        </span>
                                        @if($foulPlayer->image_path)
                                            <img src="{{ asset('storage/' . $foulPlayer->image_path) }}" alt="{{ $foulPlayer->name }}" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        @elseif($foulPlayer->gender === 'hombre')
                                            <img src="{{ asset('images/hombre.png') }}" alt="{{ $foulPlayer->name }}" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        @elseif($foulPlayer->gender === 'mujer')
                                            <img src="{{ asset('images/mujer.png') }}" alt="{{ $foulPlayer->name }}" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 font-bold flex items-center justify-center text-sm shrink-0">
                                                {{ substr($foulPlayer->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 text-sm truncate">
                                                {{ $foulPlayer->name }}
                                                @if($foulPlayer->number)
                                                    <span class="text-[10px] font-bold text-gray-500">#{{ $foulPlayer->number }}</span>
                                                @endif
                                            </h4>
                                            <p class="text-xs text-gray-500 truncate">{{ $foulPlayer->team_name }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0 pl-2">
                                        <span class="text-xl font-black text-rose-600">{{ $foulPlayer->total_fouls }}</span>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Faltas</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <i class="fa-solid fa-shield-cat text-3xl mb-2 text-gray-300"></i>
                            <p class="text-xs font-medium">Aún no hay faltas registradas.</p>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- SCRIPT CHART.JS Y ROTACIÓN CADA 10 SEGUNDOS -->
    <script>
        const teamsData = @json($teamsPlayerPoints);
        let currentTeamIdx = 0;
        let chartInstance = null;
        let rotationTimer = null;

        document.addEventListener('DOMContentLoaded', () => {
            initTeamSelector();
            if (teamsData.length > 0) {
                renderChart(0);
                if (teamsData.length > 1) {
                    document.getElementById('rotationBadge').classList.remove('hidden');
                    startRotation();
                }
            }
        });

        function initTeamSelector() {
            const selector = document.getElementById('teamChartSelector');
            if (!teamsData || teamsData.length === 0) {
                selector.innerHTML = '<option value="">Sin equipos</option>';
                return;
            }
            selector.innerHTML = teamsData.map((t, idx) => `
                <option value="${idx}">Equipo: ${t.team_name}</option>
            `).join('');
        }

        function renderChart(index) {
            currentTeamIdx = index;
            const team = teamsData[index];
            if (!team) return;

            const ctx = document.getElementById('playersPointsChart').getContext('2d');
            
            const labels = team.players.map(p => p.name);
            const dataPoints = team.players.map(p => p.points);

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin Jugadores'],
                    datasets: [{
                        label: `Puntos Totales (${team.team_name})`,
                        data: dataPoints.length ? dataPoints : [0],
                        backgroundColor: 'rgba(234, 88, 12, 0.85)', // Orange-600
                        borderColor: 'rgba(194, 65, 12, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(249, 115, 22, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 800,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Inter, sans-serif',
                                    weight: 'bold',
                                    size: 12
                                },
                                color: '#1e293b'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.raw} Puntos`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#64748b',
                                font: { weight: 'bold' }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#334155',
                                font: { weight: 'bold', size: 11 }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Sincronizar select
            document.getElementById('teamChartSelector').value = index;
        }

        function startRotation() {
            if (rotationTimer) clearInterval(rotationTimer);
            rotationTimer = setInterval(() => {
                currentTeamIdx = (currentTeamIdx + 1) % teamsData.length;
                renderChart(currentTeamIdx);
            }, 10000); // 10 segundos exacta rotación
        }

        function manualTeamSelect(index) {
            const idx = parseInt(index);
            renderChart(idx);
            // Reiniciar timer tras interacción manual
            if (teamsData.length > 1) {
                startRotation();
            }
        }
    </script>
</x-app-layout>
