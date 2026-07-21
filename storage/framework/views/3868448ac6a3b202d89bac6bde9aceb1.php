<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="w-[96%] md:w-[90%] mx-auto space-y-8 mb-[10vh]">

            <!-- HEADER BANNER COACH (FONDO BLANCO Y LOGO EN GRANDE TRANSPARENTE DE FONDO) -->
            <?php
                $firstTeamWithLogo = $coachTeams->first(fn($t) => !empty($t->image_path));
            ?>
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
                <!-- LOGO GIGANTE TRANSPARENTE Y VERTICALMENTE CENTRADO DE FONDO -->
                <?php if($firstTeamWithLogo && $firstTeamWithLogo->image_path): ?>
                    <div class="absolute -right-16 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none z-0">
                        <img src="<?php echo e(asset('storage/' . $firstTeamWithLogo->image_path)); ?>" alt="" class="w-[28rem] h-[28rem] md:w-[42rem] md:h-[42rem] object-contain">
                    </div>
                <?php endif; ?>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <!-- IZQUIERDA: MENSAJE DE BIENVENIDA -->
                    <div>
                        <span class="inline-block px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-xs font-bold uppercase tracking-widest mb-2 border border-orange-200">
                            🏀 Panel de Entrenador
                        </span>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">
                            ¡Bienvenido, Coach <?php echo e(auth()->user()->name); ?>!
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Estadísticas en tiempo real de tus equipos y rendimiento individual de jugadores.
                        </p>
                    </div>
                    
                    <!-- DERECHA: RESUMEN DE EQUIPOS Y JUGADORES (MÁS PEQUEÑO) -->
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="flex items-center gap-2 bg-gray-50/80 backdrop-blur-sm p-2 px-3 rounded-xl border border-gray-200 shadow-none text-xs">
                            <div class="text-center px-2.5 border-r border-gray-200">
                                <span class="block text-xl font-black text-gray-900 leading-tight"><?php echo e(count($coachTeams)); ?></span>
                                <span class="text-[9px] text-gray-500 uppercase tracking-wider font-bold">Equipos</span>
                            </div>
                            <div class="text-center px-2.5">
                                <span class="block text-xl font-black text-orange-600 leading-tight">
                                    <?php echo e($coachTeams->sum(fn($t) => $t->players->count())); ?>

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
                        <span class="text-[10px] text-gray-400 font-medium"><?php echo e($highestScoringWin['date'] ?? ''); ?></span>
                    </div>

                    <?php if($highestScoringWin): ?>
                        <div class="my-auto py-1">
                            <div class="flex items-center justify-between gap-2 text-center">
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[11px] font-bold text-gray-700 truncate" title="<?php echo e($highestScoringWin['coach_team_name']); ?>"><?php echo e($highestScoringWin['coach_team_name']); ?></span>
                                    <span class="text-2xl font-black text-emerald-600 leading-tight"><?php echo e($highestScoringWin['coach_score']); ?></span>
                                </div>
                                <span class="text-xs font-black text-gray-300">VS</span>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[11px] font-bold text-gray-500 truncate" title="<?php echo e($highestScoringWin['opponent_team_name']); ?>"><?php echo e($highestScoringWin['opponent_team_name']); ?></span>
                                    <span class="text-2xl font-black text-gray-400 leading-tight"><?php echo e($highestScoringWin['opponent_score']); ?></span>
                                </div>
                            </div>
                            <div class="mt-1 text-center">
                                <span class="inline-block text-[10px] font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full truncate max-w-full">
                                    <?php echo e($highestScoringWin['tournament_name']); ?>

                                </span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">Sin partidos ganados.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. JUGADOR CON MÁS PUNTOS EN UN PARTIDO (RÉCORD INDIVIDUAL) -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                            ⚡ Récord Partido
                        </span>
                    </div>

                    <?php if($topSingleGamePlayer): ?>
                        <div class="flex items-center gap-3 my-auto py-1">
                            <?php if($topSingleGamePlayer->image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $topSingleGamePlayer->image_path)); ?>" alt="<?php echo e($topSingleGamePlayer->name); ?>" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                            <?php elseif($topSingleGamePlayer->gender === 'hombre'): ?>
                                <img src="<?php echo e(asset('images/hombre.png')); ?>" alt="<?php echo e($topSingleGamePlayer->name); ?>" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                            <?php elseif($topSingleGamePlayer->gender === 'mujer'): ?>
                                <img src="<?php echo e(asset('images/mujer.png')); ?>" alt="<?php echo e($topSingleGamePlayer->name); ?>" class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shadow-sm shrink-0">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full bg-amber-100 border-2 border-amber-300 flex items-center justify-center text-amber-700 font-bold text-sm shrink-0">
                                    <?php echo e(substr($topSingleGamePlayer->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-gray-900 text-xs truncate">
                                    <?php echo e($topSingleGamePlayer->name); ?>

                                </h4>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-xl font-black text-amber-600 leading-none"><?php echo e($topSingleGamePlayer->points_in_game); ?></span>
                                    <span class="text-[10px] font-bold text-gray-400">PTS</span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">Sin datos de anotación.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 3. PRÓXIMO JUEGO (NUEVA TARJETA) -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">
                            📅 Próximo Juego
                        </span>
                        <?php if($upcomingGame && $upcomingGame->date_time): ?>
                            <span class="text-[10px] text-indigo-600 font-bold"><?php echo e($upcomingGame->date_time->format('d/m H:i')); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if($upcomingGame): ?>
                        <div class="my-auto py-1 text-center">
                            <div class="text-xs font-extrabold text-gray-900 truncate">
                                <span class="text-blue-600"><?php echo e($upcomingGame->localTeam->name ?? 'Local'); ?></span>
                                <span class="text-gray-400 mx-1">vs</span>
                                <span class="text-red-600"><?php echo e($upcomingGame->awayTeam->name ?? 'Visitante'); ?></span>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1 truncate">
                                📍 <?php echo e($upcomingGame->court->name ?? 'Cancha por definir'); ?>

                            </p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">No hay juegos programados.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 4. RESUMEN DE MIS EQUIPOS -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                            📋 Equipos Asignados
                        </span>
                    </div>

                    <?php if(count($coachTeams) > 0): ?>
                        <div class="space-y-1 max-h-20 overflow-y-auto my-auto pr-1">
                            <?php $__currentLoopData = $coachTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-1.5 rounded-lg bg-gray-50 border border-gray-100 text-[11px]">
                                    <span class="font-bold text-gray-800 truncate max-w-[110px]"><?php echo e($t->name); ?></span>
                                    <span class="px-1.5 py-0.2 rounded-full bg-blue-100 text-blue-800 font-bold text-[9px]">
                                        <?php echo e($t->players->count()); ?> Jug.
                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3 text-gray-400 my-auto">
                            <p class="text-xs font-medium">No tienes equipos a tu cargo.</p>
                        </div>
                    <?php endif; ?>
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

                    <?php if($top5Scorers->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $top5Scorers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $scorer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:border-orange-300 transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'w-7 h-7 rounded-full flex items-center justify-center font-black text-xs shrink-0',
                                            'bg-amber-400 text-amber-950' => $index === 0,
                                            'bg-slate-300 text-slate-800' => $index === 1,
                                            'bg-amber-600 text-amber-50' => $index === 2,
                                            'bg-gray-200 text-gray-700' => $index > 2,
                                        ]); ?>">
                                            <?php echo e($index + 1); ?>

                                        </span>
                                        <?php if($scorer->image_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $scorer->image_path)); ?>" alt="<?php echo e($scorer->name); ?>" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        <?php elseif($scorer->gender === 'hombre'): ?>
                                            <img src="<?php echo e(asset('images/hombre.png')); ?>" alt="<?php echo e($scorer->name); ?>" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        <?php elseif($scorer->gender === 'mujer'): ?>
                                            <img src="<?php echo e(asset('images/mujer.png')); ?>" alt="<?php echo e($scorer->name); ?>" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-700 font-bold flex items-center justify-center text-sm shrink-0">
                                                <?php echo e(substr($scorer->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 text-sm truncate">
                                                <?php echo e($scorer->name); ?>

                                                <?php if($scorer->number): ?>
                                                    <span class="text-[10px] font-bold text-gray-500">#<?php echo e($scorer->number); ?></span>
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-xs text-gray-500 truncate"><?php echo e($scorer->team_name); ?></p>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0 pl-2">
                                        <span class="text-xl font-black text-orange-600"><?php echo e($scorer->total_points); ?></span>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">PTS</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 text-gray-400">
                            <i class="fa-solid fa-chart-line text-3xl mb-2 text-gray-300"></i>
                            <p class="text-xs font-medium">Aún no hay puntos registrados para tus jugadores.</p>
                        </div>
                    <?php endif; ?>
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

                    <?php if($top5Fouls->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $top5Fouls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $foulPlayer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:border-rose-300 transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-rose-100 text-rose-800 flex items-center justify-center font-black text-xs shrink-0 border border-rose-200">
                                            <?php echo e($index + 1); ?>

                                        </span>
                                        <?php if($foulPlayer->image_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $foulPlayer->image_path)); ?>" alt="<?php echo e($foulPlayer->name); ?>" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        <?php elseif($foulPlayer->gender === 'hombre'): ?>
                                            <img src="<?php echo e(asset('images/hombre.png')); ?>" alt="<?php echo e($foulPlayer->name); ?>" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        <?php elseif($foulPlayer->gender === 'mujer'): ?>
                                            <img src="<?php echo e(asset('images/mujer.png')); ?>" alt="<?php echo e($foulPlayer->name); ?>" class="w-10 h-10 rounded-full object-cover border shrink-0">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 font-bold flex items-center justify-center text-sm shrink-0">
                                                <?php echo e(substr($foulPlayer->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 text-sm truncate">
                                                <?php echo e($foulPlayer->name); ?>

                                                <?php if($foulPlayer->number): ?>
                                                    <span class="text-[10px] font-bold text-gray-500">#<?php echo e($foulPlayer->number); ?></span>
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-xs text-gray-500 truncate"><?php echo e($foulPlayer->team_name); ?></p>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0 pl-2">
                                        <span class="text-xl font-black text-rose-600"><?php echo e($foulPlayer->total_fouls); ?></span>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Faltas</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 text-gray-400">
                            <i class="fa-solid fa-shield-cat text-3xl mb-2 text-gray-300"></i>
                            <p class="text-xs font-medium">Aún no hay faltas registradas.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

    <!-- SCRIPT CHART.JS Y ROTACIÓN CADA 10 SEGUNDOS -->
    <script>
        const teamsData = <?php echo json_encode($teamsPlayerPoints, 15, 512) ?>;
        let currentTeamIdx = 0;
        let chartInstance = null;
        let rotationTimer = null;

        function startCoachDashboardChart() {
            initTeamSelector();
            if (teamsData && teamsData.length > 0) {
                renderChart(0);
                if (teamsData.length > 1) {
                    const badge = document.getElementById('rotationBadge');
                    if (badge) badge.classList.remove('hidden');
                    startRotation();
                }
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(startCoachDashboardChart, 10);
        } else {
            document.addEventListener('DOMContentLoaded', startCoachDashboardChart);
        }

        function initTeamSelector() {
            const selector = document.getElementById('teamChartSelector');
            if (!selector) return;
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

            const canvas = document.getElementById('playersPointsChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
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
                        backgroundColor: 'rgba(249, 115, 22, 0.35)', // Transparente elegante
                        borderColor: 'rgba(234, 88, 12, 0.9)',       // Borde nítido
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(249, 115, 22, 0.65)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 350,
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\luism\gemini-work\sistemaTorneos\resources\views/dashboard_coach.blade.php ENDPATH**/ ?>