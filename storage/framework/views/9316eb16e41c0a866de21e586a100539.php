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
    <!-- Chart.js CDN for beautiful animations and graphics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="w-[96%] md:w-[90%] mx-auto space-y-6 mb-[10vh]">

            <!-- 1. STATS METRICS GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Active Tournaments Card -->
                <div class="bg-gradient-to-tr from-blue-50 to-white p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:scale-[1.01]">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Torneos Activos</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo e($activeTournamentsCount); ?></h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">En curso actualmente</p>
                    </div>
                    <div class="bg-blue-500/10 p-3 rounded-full text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-6.75a1.125 1.125 0 0 0-1.125 1.125v3.375m9 0h-9m5.625-12h-2.25a1.125 1.125 0 0 0-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h2.25a1.125 1.125 0 0 0 1.125-1.125v-1.5a1.125 1.125 0 0 0-1.125-1.125Z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Registered Players Card -->
                <div class="bg-gradient-to-tr from-green-50 to-white p-5 rounded-2xl border border-green-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:scale-[1.01]">
                    <div>
                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Total Jugadores</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo e($totalPlayersCount); ?></h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">Registrados en la plataforma</p>
                    </div>
                    <div class="bg-green-500/10 p-3 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Teams Card -->
                <div class="bg-gradient-to-tr from-purple-50 to-white p-5 rounded-2xl border border-purple-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:scale-[1.01]">
                    <div>
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Total Equipos</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo e($totalTeamsCount); ?></h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">Equipos inscritos</p>
                    </div>
                    <div class="bg-purple-500/10 p-3 rounded-full text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m0 0a5.959 5.959 0 0 1-.94-3.197m0 0a5.056 5.056 0 0 0 1.554-3.516M5.059 15.19A8.974 8.974 0 0 1 3 12c0-2.316.873-4.43 2.305-6.03M6.012 18.72a9.094 9.094 0 0 1-3.741-.479 3 3 0 0 1 4.682-2.72m-.94 3.198.002.031c0 .225.012.447.037.666A11.944 11.944 0 0 0 12 21c2.17 0 4.207-.576 5.963-1.584A6.06 6.06 0 0 0 18 18.72m-12 0a9.094 9.094 0 0 1-3.741-.479 3 3 0 0 1 4.682-2.72m.94 3.198-.002.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.06 6.06 0 0 1 6 18.72m12-6.72a5.056 5.056 0 0 0-1.554-3.516M18.941 12c0-2.316-.873-4.43-2.305-6.03M12 18.75a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Tournaments Card -->
                <div class="bg-gradient-to-tr from-amber-50 to-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:scale-[1.01]">
                    <div>
                        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Total Torneos</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo e($totalTournamentsCount); ?></h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">Histórico registrado</p>
                    </div>
                    <div class="bg-amber-500/10 p-3 rounded-full text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                </div>

                <!-- Total Referees Card -->
                <div class="bg-gradient-to-tr from-rose-50 to-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:scale-[1.01]">
                    <div>
                        <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Total Árbitros</p>
                        <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo e($totalRefereesCount); ?></h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">Cuerpo arbitral activo</p>
                    </div>
                    <div class="bg-rose-500/10 p-3 rounded-full text-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 2. CHARTS VISUALIZATION ROW -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Entity Comparison Chart (Bar Chart) -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Distribución General de Registros</h4>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase bg-slate-100 px-2 py-0.5 rounded-full">Resumen Global</span>
                    </div>
                    <div class="h-64 relative w-full">
                        <canvas id="entityComparisonChart"></canvas>
                    </div>
                </div>

                <!-- Tournament Status Doughnut Chart -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Estados de Torneos</h4>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase bg-slate-100 px-2 py-0.5 rounded-full">Proporción</span>
                    </div>
                    <div class="h-64 relative w-full flex justify-center">
                        <canvas id="tournamentStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 3. LEADERBOARDS & SINGLE-MATCH HIGHLIGHT RECORD ROW -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Top 5 Scorers per Tournament -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm lg:col-span-2" x-data="{ activeTab: '<?php echo e(count($topScorersByTournament) > 0 ? $topScorersByTournament[0]['id'] : ''); ?>' }">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b border-gray-100 pb-3 mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Top 5 Anotadores por Torneo</h4>
                            <p class="text-[10px] text-slate-500">Los líderes de goleo/puntos individuales</p>
                        </div>
                        
                        <!-- Tabs Navigation -->
                        <?php if(count($topScorersByTournament) > 0): ?>
                        <div class="flex flex-wrap gap-1 bg-gray-50 p-1 rounded-lg border border-gray-200/60 max-h-24 overflow-y-auto hide-scrollbar">
                            <?php $__currentLoopData = $topScorersByTournament; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button 
                                @click="activeTab = '<?php echo e($tab['id']); ?>'"
                                :class="activeTab === '<?php echo e($tab['id']); ?>' ? 'bg-white text-slate-800 border-gray-200 shadow-sm' : 'text-slate-500 border-transparent hover:text-slate-800'"
                                class="text-[10px] font-bold px-2.5 py-1 rounded-md border transition-all truncate max-w-[120px]"
                                title="<?php echo e($tab['name']); ?>"
                            >
                                <?php echo e($tab['name']); ?>

                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tabs Content Tables -->
                    <?php if(count($topScorersByTournament) > 0): ?>
                        <?php $__currentLoopData = $topScorersByTournament; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div x-show="activeTab === '<?php echo e($tab['id']); ?>'" x-transition class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                                        <th class="py-2.5 px-3 w-12 text-center">Rank</th>
                                        <th class="py-2.5 px-3">Jugador</th>
                                        <th class="py-2.5 px-3 w-16 text-center">Dorsal</th>
                                        <th class="py-2.5 px-3">Equipo</th>
                                        <th class="py-2.5 px-3 text-right">Puntos Totales</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php $__currentLoopData = $tab['scorers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $scorer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors text-xs text-slate-700">
                                        <td class="py-3 px-3 text-center">
                                            <?php if($index == 0): ?>
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[10px]">1🏆</span>
                                            <?php elseif($index == 1): ?>
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-800 font-extrabold text-[10px]">2🥈</span>
                                            <?php elseif($index == 2): ?>
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-50 text-amber-700 font-extrabold text-[10px]">3🥉</span>
                                            <?php else: ?>
                                                <span class="font-bold text-slate-400"><?php echo e($index + 1); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-3 font-semibold text-slate-800"><?php echo e($scorer->name); ?></td>
                                        <td class="py-3 px-3 text-center font-mono font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-md py-0.5 px-1.5"><?php echo e($scorer->number ?? '-'); ?></td>
                                        <td class="py-3 px-3 text-slate-500"><?php echo e($scorer->team_name); ?></td>
                                        <td class="py-3 px-3 text-right font-black text-slate-800 pr-4"><?php echo e($scorer->total_points); ?> pts</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mx-auto mb-2 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                            </svg>
                            No hay estadísticas de anotación cargadas para los torneos actuales.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Single Match Record Holder Highlight Card -->
                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6 rounded-2xl border border-slate-700 shadow-xl text-white flex flex-col justify-between relative overflow-hidden">
                    <!-- Glowing light effect in background -->
                    <div class="absolute -right-10 -top-10 w-36 h-36 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[9px] font-black uppercase tracking-widest text-amber-400 bg-amber-400/10 px-2.5 py-1 rounded-full border border-amber-400/20 shadow-sm flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.6 3.102-1.196 4.622c-.21.811.679 1.458 1.39 1.04l4.157-2.42 4.156 2.42c.712.418 1.602-.23 1.39-1.04l-1.195-4.622 3.6-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                                </svg>
                                Récord de Anotación
                            </span>
                            <span class="text-[9px] font-semibold text-slate-400">En un Partido</span>
                        </div>

                        <?php if($topSingleGamePlayer): ?>
                        <div class="mt-4 space-y-4">
                            <!-- Large record score bubble -->
                            <div class="flex items-baseline gap-2">
                                <h2 class="text-6xl font-black text-amber-400 tracking-tighter leading-none"><?php echo e($topSingleGamePlayer->points_scored); ?></h2>
                                <span class="text-sm font-bold text-slate-300">Puntos</span>
                            </div>

                            <!-- Player Details -->
                            <div class="space-y-1 pt-2">
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Jugador Destacado</p>
                                <h3 class="text-xl font-black text-white leading-tight">
                                    <?php echo e($topSingleGamePlayer->name); ?>

                                    <span class="text-xs font-bold text-slate-400 bg-white/5 border border-white/10 rounded px-1.5 py-0.5 ml-1 select-none">
                                        #<?php echo e($topSingleGamePlayer->number ?? '-'); ?>

                                    </span>
                                </h3>
                            </div>

                            <!-- Team & Tournament info -->
                            <div class="grid grid-cols-2 gap-4 border-t border-slate-700/60 pt-4 text-xs">
                                <div>
                                    <p class="text-slate-400 uppercase tracking-wider text-[10px] font-semibold">Equipo</p>
                                    <p class="font-bold text-slate-200 mt-0.5 truncate"><?php echo e($topSingleGamePlayer->team_name); ?></p>
                                </div>
                                <div>
                                    <p class="text-slate-400 uppercase tracking-wider text-[10px] font-semibold">Torneo</p>
                                    <p class="font-bold text-slate-200 mt-0.5 truncate"><?php echo e($topSingleGamePlayer->tournament_name ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="py-12 text-center text-slate-400 text-xs">
                            No hay partidos o anotaciones registradas aún en el sistema.
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 border-t border-slate-700/40 pt-4 text-[10px] text-slate-400 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-amber-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        Datos históricos verificados del torneo actual
                    </div>
                </div>

            </div> <!-- CLOSE 3-COLUMN GRID ROW -->

            <!-- 4. UPCOMING GAMES SECTION -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Próximos Juegos</h4>
                        <p class="text-[10px] text-slate-500">Calendario cronológico de partidos programados próximamente</p>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase bg-slate-100 px-2.5 py-1 rounded-full">Cronológico</span>
                </div>

                <?php if($upcomingGames->isNotEmpty()): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <?php $__currentLoopData = $upcomingGames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-slate-50 hover:bg-slate-100/70 border border-slate-200/60 rounded-xl p-4 flex flex-col justify-between transition-all hover:shadow-sm hover:scale-[1.01]">
                        <!-- Top Info: Tournament & Status -->
                        <div class="flex items-center justify-between gap-1 mb-2">
                            <span class="block text-[8px] font-extrabold uppercase text-slate-400 tracking-wider truncate max-w-[100px]" title="<?php echo e($game->tournament->name); ?>">
                                <?php echo e($game->tournament->name); ?>

                            </span>
                            <span class="inline-flex items-center gap-1 text-[8px] font-black text-amber-700 bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.5 rounded-full uppercase leading-none shrink-0">
                                <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                                Programado
                            </span>
                        </div>

                        <!-- Mid Info: VS Matchup (Horizontal) -->
                        <div class="my-4 flex items-center justify-between gap-1.5 border-y border-slate-200/40 py-2.5">
                            <div class="w-[42%] text-right">
                                <p class="text-[10px] md:text-xs font-black text-blue-600 truncate" title="<?php echo e($game->localTeam->name); ?>">
                                    <?php echo e($game->localTeam->name); ?>

                                </p>
                            </div>
                            <div class="shrink-0 text-center">
                                <span class="inline-block text-[8px] md:text-[9px] font-black text-slate-400 bg-white border border-slate-200 px-1.5 py-0.5 rounded shadow-2xs leading-none">
                                    VS
                                </span>
                            </div>
                            <div class="w-[42%] text-left">
                                <p class="text-[10px] md:text-xs font-black text-red-600 truncate" title="<?php echo e($game->awayTeam->name); ?>">
                                    <?php echo e($game->awayTeam->name); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Bottom Info: Court, Date & Time on the same line -->
                        <div class="flex items-center justify-between text-[10px] text-slate-600 font-semibold mt-2 pt-2 border-t border-slate-200/40">
                            <!-- Court -->
                            <div class="flex items-center gap-1 truncate max-w-[50%]" title="<?php echo e($game->court ? $game->court->name : 'Sin asignar'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <span class="truncate"><?php echo e($game->court ? $game->court->name : 'Sin asignar'); ?></span>
                            </div>
                            <!-- Date & Time -->
                            <div class="text-right font-mono font-bold text-slate-700 shrink-0 flex items-center gap-1" title="<?php echo e($game->date_time ? $game->date_time->format('d/m/Y h:i A') : 'No programado'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span><?php echo e($game->date_time ? $game->date_time->format('d/m H:i') : 'No prog.'); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="py-12 text-center text-slate-400 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mx-auto mb-2 text-slate-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    No hay próximos juegos programados en este momento.
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Charts initialization script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: General comparison bar chart
            const comparisonCtx = document.getElementById('entityComparisonChart').getContext('2d');
            new Chart(comparisonCtx, {
                type: 'bar',
                data: {
                    labels: ['Jugadores', 'Equipos', 'Torneos', 'Árbitros'],
                    datasets: [{
                        label: 'Total Registrado',
                        data: [
                            <?php echo e($totalPlayersCount); ?>,
                            <?php echo e($totalTeamsCount); ?>,
                            <?php echo e($totalTournamentsCount); ?>,
                            <?php echo e($totalRefereesCount); ?>

                        ],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.85)',  // Green
                            'rgba(168, 85, 247, 0.85)',  // Purple
                            'rgba(245, 158, 11, 0.85)',  // Amber
                            'rgba(244, 63, 94, 0.85)'    // Rose
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(168, 85, 247)',
                            'rgb(245, 158, 11)',
                            'rgb(244, 63, 94)'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // Chart 2: Tournament statuses doughnut chart
            const statusCtx = document.getElementById('tournamentStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Activos', 'Finalizados', 'Otros/Pendientes'],
                    datasets: [{
                        data: [
                            <?php echo e($activeTournamentsCount); ?>,
                            <?php echo e($finishedTournamentsCount); ?>,
                            <?php echo e($pendingTournamentsCount); ?>

                        ],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.85)',  // Blue
                            'rgba(244, 63, 94, 0.85)',    // Rose
                            'rgba(148, 163, 184, 0.85)'   // Slate/Gray
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16,
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    cutout: '65%'
                }
            });
        });
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
<?php /**PATH C:\Users\luism\gemini-work\sistemaTorneos\resources\views/dashboard.blade.php ENDPATH**/ ?>