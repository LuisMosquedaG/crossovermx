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

<style>
    /* --- AJUSTES FULL-SCREEN (MODO TV GLOBAL) --- */
    /* Forzar que el layout ocupe todo el ancho y sin márgenes */
    body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }
    
    main, .container, .max-w-7xl {
        max-width: 100% !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    /* Ocultar elementos de navegación y layout en TODAS las pantallas (Celular, Tablet y PC) */
    nav, aside, .sidebar, .top-bar, .navbar, header, .footer {
        display: none !important;
    }

    /* Asegurar que el contenedor principal no tenga margen interno */
    main {
        padding: 0 !important;
    }

    /* Ocultar barra de scroll para limpieza visual (opcional) */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
        /* --- EFECTO BOTONES (CLICK) --- */
    
    /* Clase para animar puntos (Azul) */
    .animate-click-blue {
        animation: pop-blue 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes pop-blue {
        0% { 
            transform: scale(1); 
            box-shadow: 0 0 0 rgba(59, 130, 246, 0); 
            border-color: rgb(209, 213, 219);
        }
        50% { 
            transform: scale(1.4); /* El botón y el texto se hacen grandes */
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.7); /* Esplandor azul intenso */
            border-color: #3b82f6;
            background-color: #eff6ff;
            z-index: 50;
        }
        100% { 
            transform: scale(1); 
            box-shadow: 0 0 0 rgba(59, 130, 246, 0); 
            border-color: rgb(209, 213, 219);
        }
    }

    /* Clase para animar faltas (Rojo/Naranja) */
    .animate-click-red {
        animation: pop-red 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes pop-red {
        0% { 
            transform: scale(1); 
            box-shadow: 0 0 0 rgba(239, 68, 68, 0); 
            border-color: rgb(209, 213, 219);
        }
        50% { 
            transform: scale(1.4); 
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.7); /* Esplandor rojo */
            border-color: #ef4444;
            background-color: #fef2f2;
            z-index: 50;
        }
        100% { 
            transform: scale(1); 
            box-shadow: 0 0 0 rgba(239, 68, 68, 0); 
            border-color: rgb(209, 213, 219);
        }
    }
</style>

    <!-- Contenedor Principal ajustado a W-FULL (Ancho completo) -->
    <!-- Eliminé max-w-7xl y max-w-1600. Usamos px-2 solo para que no toque el borde físico del monitor, pero es casi full screen -->
    <div class="w-full min-h-screen bg-gray-50 py-2 px-1 md:px-4">
        
        <div class="w-full mx-auto"> 
            
    <!-- Sección del Marcador y Controles -->
    <div class="w-full px-0 md:px-2 mb-4">

    <!-- Marcador y Temporizador -->
    <!-- CAMBIOS: p-2 md:p-6 -> p-1 md:p-3 (Menos padding) -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-1 md:p-3 rounded-2xl shadow-2xl relative overflow-hidden border border-slate-700 ring-1 ring-white/10">

    <!-- LOGO LOCAL (Fondo fantasma sutil) -->
    <?php if($game->localTeam->logo): ?>
    <!-- Reducimos ligeramente el logo absoluto para que no desborde si encogemos mucho -->
    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-24 h-24 md:w-40 md:h-40 opacity-5 pointer-events-none z-0">
    <img src="<?php echo e(asset('storage/' . $game->localTeam->logo)); ?>" alt="<?php echo e($game->localTeam->name); ?>" class="w-full h-full object-contain">
    </div>
    <?php endif; ?>

    <!-- LOGO VISITANTE (Fondo fantasma sutil) -->
    <?php if($game->awayTeam->logo): ?>
    <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-24 h-24 md:w-40 md:h-40 opacity-5 pointer-events-none z-0">
    <img src="<?php echo e(asset('storage/' . $game->awayTeam->logo)); ?>" alt="<?php echo e($game->awayTeam->name); ?>" class="w-full h-full object-contain">
    </div>
    <?php endif; ?>

    <!-- Contenido del Marcador -->
    <!-- CAMBIOS: gap-1 md:gap-4 -> gap-1 md:gap-2 (Menos espacio vertical entre filas) -->
    <div class="relative z-10 grid grid-cols-3 gap-1 md:gap-2 items-center text-center">

    <!-- Equipo Local -->
    <div>
    <h3 class="text-[9px] md:text-lg font-bold uppercase tracking-wider truncate text-blue-400 drop-shadow-md"><?php echo e($game->localTeam->name); ?></h3>
    <!-- CAMBIOS: Fuente reducida para ahorrar altura (md:text-7xl -> md:text-6xl, lg:text-8xl -> lg:text-7xl) -->
    <p class="text-2xl md:text-6xl lg:text-7xl font-black font-mono text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.5)] leading-none" id="localScore"><?php echo e($game->local_team_score ?? 0); ?></p>
    </div>

    <!-- Centro -->
    <!-- CAMBIOS: Padding reducido p-1 md:p-3 -> p-0.5 md:p-1.5 -->
    <div class="bg-black/20 backdrop-blur-sm rounded-lg md:rounded-xl p-0.5 md:p-1.5 border border-white/5 shadow-inner flex flex-col justify-center">
        <p class="text-[8px] uppercase tracking-widest text-gray-400 font-bold mb-0.5">Periodo</p>
        <p class="text-lg md:text-2xl font-bold text-white mb-1 leading-none" id="currentPeriod">1</p>
        <!-- CAMBIOS: Fuente reloj reducida lg:text-5xl -> lg:text-4xl -->
        <p class="text-xl md:text-3xl lg:text-4xl font-mono font-semibold text-yellow-400 drop-shadow-[0_0_8px_rgba(250,204,21,0.4)] leading-none" id="gameTimer">00:00</p>
    </div>

    <!-- Equipo Visitante -->
    <div>
    <h3 class="text-[9px] md:text-lg font-bold uppercase tracking-wider truncate text-red-400 drop-shadow-md"><?php echo e($game->awayTeam->name); ?></h3>
    <!-- CAMBIOS: Fuente reducida para ahorrar altura (md:text-7xl -> md:text-6xl, lg:text-8xl -> lg:text-7xl) -->
    <p class="text-2xl md:text-6xl lg:text-7xl font-black font-mono text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.5)] leading-none" id="awayScore"><?php echo e($game->away_team_score ?? 0); ?></p>
    </div>
    </div>
    </div>

    <!-- Controles del Partido -->
    <!-- Nota: También reduje el padding aquí un poco para que no se vea desproporcionado con el marcador pequeño -->
    <div class="bg-white/70 backdrop-blur-md p-1.5 md:p-3 rounded-2xl border border-gray-200/60 shadow-sm flex flex-wrap justify-between items-center gap-2 md:gap-6 w-full">

    <!-- ZONA EQUIPO LOCAL (Izquierda) -->
    <div class="flex items-center gap-1 md:gap-3 w-1/3 md:w-auto justify-start">
        <!-- CORRECCIÓN: Se agregó el ID "localTimeouts" al span que contiene el número -->
        <button onclick="recordTimeout('local')" class="text-[9px] md:text-sm bg-white text-gray-800 border border-gray-300 rounded-lg px-1.5 py-1 md:px-3 md:py-1.5 font-semibold shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all truncate">
            T. Local (<span id="localTimeouts"><?php echo e($localTimeoutsLeft); ?></span>)
        </button>
        <div id="localTeamFouls" class="text-[9px] md:text-xs font-bold text-gray-500 bg-gray-100/80 px-1.5 py-1 rounded-md border border-gray-200 shadow-sm whitespace-nowrap">
            F: <?php echo e($localTeamFouls); ?>/5
        </div>
    </div>

    <!-- ZONA CONTROLES CENTRALES (Medio) -->
    <div class="flex items-center gap-1 md:gap-4 flex-1 justify-center w-full md:w-auto order-first md:order-none mb-1 md:mb-0">
    <button id="startBtn" class="flex-1 md:flex-none bg-white text-gray-900 border border-gray-300 rounded-full px-3 md:px-6 py-1.5 md:py-1.5 text-[10px] md:text-sm font-bold shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all">
    Iniciar
    </button>
    <button id="pauseBtn" disabled class="flex-1 md:flex-none bg-white text-gray-900 border border-gray-300 rounded-full px-3 md:px-6 py-1.5 md:py-1.5 text-[10px] md:text-sm font-bold shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all">
    Pausar
    </button>
    <button id="endBtn" disabled class="flex-1 md:flex-none bg-white text-gray-900 border border-gray-300 rounded-full px-3 md:px-6 py-1.5 md:py-1.5 text-[10px] md:text-sm font-bold shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all">
    Finalizar
    </button>
    <!-- BOTÓN DESHACER (NUEVO) -->
    <button onclick="undoLastAction()" class="flex-1 md:flex-none bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-full px-3 md:px-6 py-1.5 md:py-1.5 text-[10px] md:text-sm font-bold shadow-sm hover:bg-yellow-200 active:bg-yellow-300 active:scale-95 transition-all">
            Deshacer
    </button>
    </div>

    <!-- ZONA EQUIPO VISITANTE (Derecha) -->
    <div class="flex items-center gap-1 md:gap-3 w-1/3 md:w-auto justify-end">
        <div id="awayTeamFouls" class="text-[9px] md:text-xs font-bold text-gray-500 bg-gray-100/80 px-1.5 py-1 rounded-md border border-gray-200 shadow-sm whitespace-nowrap">
            F: <?php echo e($awayTeamFouls); ?>/5
        </div>
        <!-- CORRECCIÓN: Se agregó el ID "awayTimeouts" al span que contiene el número -->
        <button onclick="recordTimeout('away')" class="text-[9px] md:text-sm bg-white text-gray-800 border border-gray-300 rounded-lg px-1.5 py-1 md:px-3 md:py-1.5 font-semibold shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all truncate">
            T. Visita (<span id="awayTimeouts"><?php echo e($awayTimeoutsLeft); ?></span>)
        </button>
    </div>
    </div>
    </div>

        <!-- Controles de Equipos y Jugadores -->
        <!-- Cambio: grid-cols-1 md:grid-cols-2, pero ahora sin restricciones de ancho, se estirarán para llenar la pantalla -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-4 mb-3 w-full">

            <!-- Panel Equipo LOCAL -->
            <div class="bg-white/70 backdrop-blur-md p-1.5 md:p-3 rounded-xl shadow-sm border border-gray-200 w-full overflow-visible md:overflow-hidden">
                <ul class="space-y-1">
                   <?php for($i = 0; $i < 5; $i++): ?>
                        <?php if(isset($localActivePlayers[$i])): ?>
                            <?php
                                $pid = $localActivePlayers[$i]->id;
                                $s = $localStats[$pid] ?? ['points' => 0, 'fouls' => ['personal' => 0, 'technical' => 0, 'unsportsmanlike' => 0, 'disqualifying' => 0]];
                                $totalFouls = $s['fouls']['personal'] + $s['fouls']['technical'] + $s['fouls']['unsportsmanlike'] + $s['fouls']['disqualifying'];
                            ?>
                            
                            <li id="local-row-<?php echo e($i); ?>" class="flex flex-col md:flex-row md:justify-between items-center py-2 md:py-2.5 hover:bg-gray-50/50 transition-colors w-full border-b border-gray-50 last:border-0 overflow-visible">
                                <!-- INFO JUGADOR: CENTRADO, CON STATS PEGADOS -->
                                <div class="flex items-center justify-center w-full md:w-[12%] gap-2 border-b border-gray-100 md:border-b-0 pb-2 md:pb-0">
                                    <div class="flex flex-col items-center text-center w-full">
                                        <!-- Número -->
                                        <span class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-none tracking-tighter">
                                            <?php echo e($localActivePlayers[$i]->number ?? '-'); ?>

                                        </span>
                                        <!-- Nombre -->
                                        <span class="text-[9px] md:text-[10px] font-bold text-slate-600 uppercase tracking-wide mt-0 leading-none truncate w-full">
                                            <?php echo e($localActivePlayers[$i]->name); ?>

                                        </span>
                                        <!-- Stats PEGADOS (mt-0.5) -->
                                        <div class="flex flex-row gap-1.5 text-[9px] md:text-[10px] text-gray-400 font-semibold mt-0.5 leading-tight">
                                            <span>F: <span class="player-fouls text-gray-600" id="local-<?php echo e($localActivePlayers[$i]->id); ?>-fouls"><?php echo e($totalFouls); ?></span></span>
                                            <span>P: <span class="player-points text-gray-600" id="local-<?php echo e($localActivePlayers[$i]->id); ?>-points"><?php echo e($s['points']); ?></span></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- BOTONES -->
                                <div class="flex items-center gap-1 md:gap-1 lg:gap-1 overflow-x-auto hide-scrollbar w-full md:flex-1 justify-end min-w-0 mt-2 md:mt-0">
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 1, <?php echo e($i); ?>)">+1</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 2, <?php echo e($i); ?>)">+2</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 3, <?php echo e($i); ?>)">+3</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 'foul_personal', <?php echo e($i); ?>)">P</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 'foul_unsportsmanlike', <?php echo e($i); ?>)">A</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 'foul_technical', <?php echo e($i); ?>)">T</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($localActivePlayers[$i]->id); ?>, 'local', 'foul_disqualifying', <?php echo e($i); ?>)">D</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-gray-200 text-gray-400 border border-gray-300 rounded hover:bg-gray-300 active:bg-gray-400 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 change-player-btn shrink-0" data-team-side="local" data-player-id="<?php echo e($localActivePlayers[$i]->id); ?>" data-player-name="<?php echo e($localActivePlayers[$i]->name); ?>" data-player-number="<?php echo e($localActivePlayers[$i]->number ?? ''); ?>" data-slot-index="<?php echo e($i); ?>" title="Cambiar Jugador">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 md:w-5 md:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 8H5M8 5l-3 3 3 3" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 16H19M16 13l3 3-3 3" /></svg>
                                    </button>
                                </div>
                            </li>
                                               
                        <?php else: ?>
                            <li id="local-row-<?php echo e($i); ?>" class="flex flex-col md:flex-row md:justify-between items-center py-2 md:py-2.5 w-full border-b border-gray-50 last:border-0 overflow-visible">
                                <!-- INFO VACANTE: CENTRADO, SIN STATS, NÚMERO TOSCO -->
                                <div class="flex items-center justify-center w-full md:w-[12%] gap-2 border-b border-gray-100 md:border-b-0 pb-2 md:pb-0">
                                    <div class="flex flex-col items-center text-center w-full">
                                        <span class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-300 leading-none tracking-tighter">
                                            -
                                        </span>
                                        <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wide mt-0 leading-none truncate w-full">
                                            Vacante
                                        </span>
                                    </div>
                                </div>

                                <!-- BOTONES VACANTE: Estilo consistente (cuadrados y largos) -->
                                <div class="flex items-center gap-1 md:gap-1 lg:gap-1 overflow-x-auto hide-scrollbar w-full md:flex-1 justify-end min-w-0 mt-2 md:mt-0">
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+1</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+2</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+3</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>P</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>A</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>T</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>D</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-gray-200 text-gray-400 border border-gray-300 rounded hover:bg-gray-300 active:bg-gray-400 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 change-player-btn shrink-0" data-team-side="local" data-player-id="null" data-player-name="Slot Vacante" data-player-number="" data-slot-index="<?php echo e($i); ?>" title="Llenar Espacio">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 md:w-5 md:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 8H5M8 5l-3 3 3 3" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 16H19M16 13l3 3-3 3" /></svg>
                                    </button>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            </div>

            <!-- Panel Equipo VISITANTE -->
            <div class="bg-white/70 backdrop-blur-md p-1.5 md:p-3 rounded-xl shadow-sm border border-gray-200 w-full overflow-visible md:overflow-hidden">
                <ul class="space-y-1">
                    <?php for($i = 0; $i < 5; $i++): ?>
                        <?php if(isset($awayActivePlayers[$i])): ?>
                            <?php
                                $pid = $awayActivePlayers[$i]->id;
                                $s = $awayStats[$pid] ?? ['points' => 0, 'fouls' => ['personal' => 0, 'technical' => 0, 'unsportsmanlike' => 0, 'disqualifying' => 0]];
                                $totalFouls = $s['fouls']['personal'] + $s['fouls']['technical'] + $s['fouls']['unsportsmanlike'] + $s['fouls']['disqualifying'];
                            ?>

                                                        <li id="away-row-<?php echo e($i); ?>" class="flex flex-col md:flex-row md:justify-between items-center py-2 md:py-2.5 hover:bg-gray-50/50 transition-colors w-full border-b border-gray-50 last:border-0 overflow-visible">
                                <!-- INFO JUGADOR: CENTRADO, CON STATS PEGADOS -->
                                <div class="flex items-center justify-center w-full md:w-[12%] gap-2 border-b border-gray-100 md:border-b-0 pb-2 md:pb-0">
                                    <div class="flex flex-col items-center text-center w-full">
                                        <span class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-none tracking-tighter">
                                            <?php echo e($awayActivePlayers[$i]->number ?? '-'); ?>

                                        </span>
                                        <span class="text-[9px] md:text-[10px] font-bold text-slate-600 uppercase tracking-wide mt-0 leading-none truncate w-full">
                                            <?php echo e($awayActivePlayers[$i]->name); ?>

                                        </span>
                                        <!-- Stats PEGADOS -->
                                        <div class="flex flex-row gap-1.5 text-[9px] md:text-[10px] text-gray-400 font-semibold mt-0.5 leading-tight">
                                            <span>F: <span class="player-fouls text-gray-600" id="away-<?php echo e($awayActivePlayers[$i]->id); ?>-fouls"><?php echo e($totalFouls); ?></span></span>
                                            <span>P: <span class="player-points text-gray-600" id="away-<?php echo e($awayActivePlayers[$i]->id); ?>-points"><?php echo e($s['points']); ?></span></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- BOTONES -->
                                <div class="flex items-center gap-1 md:gap-1 lg:gap-1 overflow-x-auto hide-scrollbar w-full md:flex-1 justify-end min-w-0 mt-2 md:mt-0">
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 1, <?php echo e($i); ?>)">+1</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 2, <?php echo e($i); ?>)">+2</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 3, <?php echo e($i); ?>)">+3</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 'foul_personal', <?php echo e($i); ?>)">P</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 'foul_unsportsmanlike', <?php echo e($i); ?>)">A</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 'foul_technical', <?php echo e($i); ?>)">T</button>
                                    <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(<?php echo e($awayActivePlayers[$i]->id); ?>, 'away', 'foul_disqualifying', <?php echo e($i); ?>)">D</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-gray-200 text-gray-400 border border-gray-300 rounded hover:bg-gray-300 active:bg-gray-400 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 change-player-btn shrink-0" data-team-side="away" data-player-id="<?php echo e($awayActivePlayers[$i]->id); ?>" data-player-name="<?php echo e($awayActivePlayers[$i]->name); ?>" data-player-number="<?php echo e($awayActivePlayers[$i]->number ?? ''); ?>" data-slot-index="<?php echo e($i); ?>" title="Cambiar Jugador">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 md:w-5 md:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 8H5M8 5l-3 3 3 3" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 16H19M16 13l3 3-3 3" /></svg>
                                    </button>
                                </div>
                            </li>
                        <?php else: ?>
                            <li id="away-row-<?php echo e($i); ?>" class="flex flex-col md:flex-row md:justify-between items-center py-2 md:py-2.5 w-full border-b border-gray-50 last:border-0 overflow-visible">
                                <!-- INFO VACANTE: CENTRADO, SIN STATS, NÚMERO TOSCO -->
                                <div class="flex items-center justify-center w-full md:w-[12%] gap-2 border-b border-gray-100 md:border-b-0 pb-2 md:pb-0">
                                    <div class="flex flex-col items-center text-center w-full">
                                        <span class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-300 leading-none tracking-tighter">
                                            -
                                        </span>
                                        <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wide mt-0 leading-none truncate w-full">
                                            Vacante
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- BOTONES VACANTE: Estilo consistente (cuadrados y largos) -->
                                <div class="flex items-center gap-1 md:gap-1 lg:gap-1 overflow-x-auto hide-scrollbar w-full md:flex-1 justify-end min-w-0 mt-2 md:mt-0">
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+1</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+2</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+3</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>P</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>A</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>T</button>
                                    <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>D</button>
                                    <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                                    <button class="bg-gray-200 text-gray-400 border border-gray-300 rounded hover:bg-gray-300 active:bg-gray-400 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 change-player-btn shrink-0" data-team-side="away" data-player-id="null" data-player-name="Slot Vacante" data-player-number="" data-slot-index="<?php echo e($i); ?>" title="Llenar Espacio">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 md:w-5 md:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 8H5M8 5l-3 3 3 3" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 16H19M16 13l3 3-3 3" /></svg>
                                    </button>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>

        <!-- Log de Acciones (Versión Limpia y Unificada) -->
        <div class="mt-6 bg-white p-4 rounded-lg shadow-md w-full">
            <h3 class="text-lg font-bold mb-3">Registro de Acciones</h3>
            <div class="max-h-64 overflow-y-auto" id="actionsLog">
                <?php if(isset($actions) && $actions->count() > 0): ?>
                    <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-b pb-2 mb-2">
                            <?php switch($action->action_type):
                                case ('point_scored'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <?php if($action->player): ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->player->name); ?> (<?php echo e($action->player->number ?? 'N/A'); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->team_side === 'local' ? $game->localTeam->name : $game->awayTeam->name); ?>

                                            </span>
                                        <?php endif; ?>
                                        : Anotó <?php echo e($action->value); ?> punto<?php echo e($action->value > 1 ? 's' : ''); ?>

                                    </p>
                                    <?php break; ?>
                                <?php case ('foul_personal'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <?php if($action->player): ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->player->name); ?> (<?php echo e($action->player->number ?? 'N/A'); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->team_side === 'local' ? $game->localTeam->name : $game->awayTeam->name); ?>

                                            </span>
                                        <?php endif; ?>
                                        : Falta Personal
                                    </p>
                                    <?php break; ?>
                                <?php case ('foul_technical'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <?php if($action->player): ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->player->name); ?> (<?php echo e($action->player->number ?? 'N/A'); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->team_side === 'local' ? $game->localTeam->name : $game->awayTeam->name); ?>

                                            </span>
                                        <?php endif; ?>
                                        : Falta Técnica
                                    </p>
                                    <?php break; ?>
                                <?php case ('foul_unsportsmanlike'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <?php if($action->player): ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->player->name); ?> (<?php echo e($action->player->number ?? 'N/A'); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->team_side === 'local' ? $game->localTeam->name : $game->awayTeam->name); ?>

                                            </span>
                                        <?php endif; ?>
                                        : Falta Antideportiva
                                    </p>
                                    <?php break; ?>
                                <?php case ('foul_disqualifying'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <?php if($action->player): ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->player->name); ?> (<?php echo e($action->player->number ?? 'N/A'); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($action->team_side === 'local' ? $game->localTeam->name : $game->awayTeam->name); ?>

                                            </span>
                                        <?php endif; ?>
                                        : Falta Descalificatoria
                                    </p>
                                    <?php break; ?>
                                <?php case ('timeout_called'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
                                        <td class="px-4 py-3 text-center text-gray-500 font-mono">
                                            <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
                                        </td> - 
                                        <span class="font-semibold <?php echo e($action->team_side === 'local' ? 'text-blue-600' : 'text-red-600'); ?>">
                                            <?php echo e($action->team_side === 'local' ? $game->localTeam->name : $game->awayTeam->name); ?>

                                        </span>
                                        : Tiempo Fuera
                                    </p>
                                    <?php break; ?>
                                <?php case ('substitution'): ?>
                                    <?php
                                        $playerOutName = 'Desconocido';
                                        if ($action->value) {
                                            $playerOut = \App\Models\Player::find($action->value);
                                            if ($playerOut) $playerOutName = $playerOut->name;
                                        }
                                    ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <span class="font-semibold text-gray-600">Cambio</span>
                                        <?php if($action->value): ?>
                                            : Sale <?php echo e($playerOutName); ?>

                                        <?php else: ?>
                                            : Entra (Llena Vacante)
                                        <?php endif; ?>
                                    </p>
                                    <?php break; ?>
                                
                                <?php case ('overtime_started'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <span class="font-semibold text-purple-600">Sistema</span>:
                                        Inició Tiempo Extra (<?php echo e($action->value); ?> min)
                                    </p>
                                    <?php break; ?>

                                <?php case ('compensation_added'): ?>
                                    <p class="text-sm">
                                        <!-- Línea NUEVA -->
<td class="px-4 py-3 text-center text-gray-500 font-mono">
    <span class="convert-to-local-time" data-time="<?php echo e($action->created_at->toIso8601String()); ?>">...</span>
</td> - 
                                        <span class="font-semibold text-yellow-600">Sistema</span>:
                                        Agregó <?php echo e($action->value); ?> min de compensación
                                    </p>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500">No hay acciones registradas aún.</p>
                <?php endif; ?>
            </div>
        </div>      
    </div>
</div>
    <!-- Modal de Cambio de Jugador -->
    <div id="substitutionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Cambio de Jugador</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Jugador a salir: <span id="playerOutName" class="font-bold text-gray-800"></span>
                    </p>
                    <p class="text-sm text-gray-500 mt-2 mb-4">Selecciona el jugador que entra desde la banca:</p>
                    
                    <!-- Lista de jugadores de la banca -->
                    <div id="benchList" class="max-h-48 overflow-y-auto border rounded-md text-left">
                        <!-- Se llenará con JS -->
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModalBtn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Expulsión (Icono Centrado) -->
    <div id="ejectionModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <!-- Fondo oscuro -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <!-- Contenido del Modal (Alineación Centrada) -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <!-- CAMBIO PRINCIPAL: Flex column items-center -->
                        <div class="flex flex-col items-center">
                            <!-- Icono centrado arriba -->
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <!-- Texto centrado -->
                            <div class="mt-3 text-center w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">¡Atención!</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" id="ejectionMessageText">Mensaje de la expulsión...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón de Aceptar -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" id="btnConfirmEjection" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Entendido (Reemplazar)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Tiempo Extra -->
    <div id="overtimeModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <!-- Fondo oscuro -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">¡Partido Empatado!</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">El tiempo regular ha finalizado con empate. ¿Deseas iniciar un tiempo extra?</p>
                                    <label for="overtimeMinutes" class="block text-sm font-medium leading-6 text-gray-900">Duración (minutos):</label>
                                    <div class="mt-2">
                                        <input type="number" name="overtimeMinutes" id="overtimeMinutes" value="5" min="1" max="20" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 px-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 sm:gap-3">
                        
                        <!-- Botón Naranja: Iniciar Tiempo Extra -->
                        <button type="button" id="btnStartOvertime" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600 sm:w-auto">
                            Iniciar T. Extra
                        </button>

                        <!-- Botón Rojo: Terminar como Empate -->
                        <button type="button" id="btnFinishAsTie" class="mt-3 inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-500 sm:mt-0 sm:w-auto">
                            Terminar Partido
                        </button>

                        <!-- Botón Gris: Cancelar -->
                        <button type="button" id="btnCancelOvertime" onclick="closeOvertimeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Resumen Final / Confirmación -->
    <div id="finalScoreModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <!-- CAMBIO: Quité 'sm:text-left' para que siempre quede centrado -->
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 text-center">¿Finalizar Partido?</h3>
                                
                                <div class="mt-4 bg-gray-50 rounded-lg p-4 flex justify-around items-center border border-gray-200">
                                    <div class="text-center">
                                        <p class="text-xs font-bold text-blue-600 uppercase"><?php echo e($game->localTeam->name); ?></p>
                                        <p class="text-4xl font-black text-gray-900" id="modalFinalLocalScore">0</p>
                                    </div>
                                    <div class="text-2xl text-gray-400 font-bold">VS</div>
                                    <div class="text-center">
                                        <p class="text-xs font-bold text-red-600 uppercase"><?php echo e($game->awayTeam->name); ?></p>
                                        <p class="text-4xl font-black text-gray-900" id="modalFinalAwayScore">0</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Minutos de compensación (opcional):</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <input type="number" id="compensationMinutes" value="1" min="1" max="10" class="block w-full rounded-l-md border-0 py-1.5 pl-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <button type="button" onclick="addCompensationTime()" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-white bg-yellow-500 hover:bg-yellow-600 ring-1 ring-inset ring-gray-300">
                                            Agregar Tiempo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <!-- CAMBIO: Botón de Naranja (bg-orange-600) en lugar de Verde -->
                        <button type="button" onclick="confirmGameFinish()" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600 sm:ml-3 sm:w-auto">
                            Sí, Finalizar Partido
                        </button>
                        <button type="button" onclick="closeFinalScoreModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- JavaScript para manejar las acciones -->
<script>
    // Variables globales del cronómetro
    let timerInterval;
    let gameId = <?php echo e($game->id); ?>;
    let totalSeconds = <?php echo e($game->seconds_remaining ?? ($gameDurationMinutes * 60)); ?>;
    let gameDurationConfig = <?php echo e($gameDurationMinutes ?? 10); ?> * 60;
    let totalPeriodsConfig = <?php echo e($totalPeriods ?? 4); ?>;
    let currentPeriodDisplay = <?php echo e($game->period ?? 1); ?>;

 // --- NUEVAS VARIABLES: Contadores de Faltas de Equipo ---
    let localTeamFouls = <?php echo e($localTeamFouls ?? 0); ?>;
    let awayTeamFouls = <?php echo e($awayTeamFouls ?? 0); ?>;
    // -----------------------------------------------------

    let maxTimeouts = <?php echo e($timeoutsPerGame ?? 5); ?>;
    let localTimeouts = <?php echo e($localTimeoutsLeft ?? $timeoutsPerGame); ?>; 
    let awayTimeouts = <?php echo e($awayTimeoutsLeft ?? $timeoutsPerGame); ?>;
    
    // LÍMITES DE FALTAS DESDE CONFIGURACIÓN
    let foulLimits = <?php echo json_encode($foulLimits, 15, 512) ?>;
    let playerStats = {};
    let ejectedPlayerIds = []; // Lista para guardar IDs de jugadores expulsados

    // Inicializar estadísticas usando datos de la DB o 0 si es nuevo
    <?php $__currentLoopData = $localActivePlayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $s = $localStats[$player->id] ?? ['points' => 0, 'fouls' => ['personal' => 0, 'technical' => 0, 'unsportsmanlike' => 0, 'disqualifying' => 0]]; ?>
        playerStats['local-<?php echo e($player->id); ?>'] = { 
            points: <?php echo e($s['points']); ?>, 
            fouls: { 
                personal: <?php echo e($s['fouls']['personal']); ?>, 
                technical: <?php echo e($s['fouls']['technical']); ?>, 
                unsportsmanlike: <?php echo e($s['fouls']['unsportsmanlike']); ?>, 
                disqualifying: <?php echo e($s['fouls']['disqualifying']); ?> 
            } 
        };
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $awayActivePlayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $s = $awayStats[$player->id] ?? ['points' => 0, 'fouls' => ['personal' => 0, 'technical' => 0, 'unsportsmanlike' => 0, 'disqualifying' => 0]]; ?>
        playerStats['away-<?php echo e($player->id); ?>'] = { 
            points: <?php echo e($s['points']); ?>, 
            fouls: { 
                personal: <?php echo e($s['fouls']['personal']); ?>, 
                technical: <?php echo e($s['fouls']['technical']); ?>, 
                unsportsmanlike: <?php echo e($s['fouls']['unsportsmanlike']); ?>, 
                disqualifying: <?php echo e($s['fouls']['disqualifying']); ?> 
            } 
        };
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

     // --- NUEVA FUNCIÓN PARA MOSTRAR EL MODAL DE EXPULSIÓN ---
    let pendingSubstitution = null; // Variable temporal para guardar datos del cambio
    
    // --- FUNCIÓN DE ANIMACIÓN DE BOTONES ---
    function triggerButtonEffect() {
        // Obtenemos el elemento que acaba de recibir el foco (el botón clickeado)
        const btn = document.activeElement;
        
        // Verificamos que sea un botón
        if (btn && (btn.tagName === 'BUTTON')) {
            const btnText = btn.innerText.trim();
            
            // Definimos qué color usar según el texto del botón
            // Si es P, A, T, D usamos ROJO, si no (números) usamos AZUL
            let animationClass = 'animate-click-blue';
            if (['P', 'A', 'T', 'D'].includes(btnText)) {
                animationClass = 'animate-click-red';
            }

            // Agregamos la clase para iniciar la animación
            btn.classList.add(animationClass);

            // Eliminamos la clase después de 300ms (la duración de la animación)
            setTimeout(() => {
                btn.classList.remove(animationClass);
            }, 300);
        }
    }

    function showEjectionModal(message, subParams) {
        document.getElementById('ejectionMessageText').textContent = message;
        pendingSubstitution = subParams; // Guardamos los datos para usarlos al cerrar
        
        // Mostramos el modal
        document.getElementById('ejectionModal').classList.remove('hidden');
        
        // Configuramos el botón para que al hacer clic, ejecute el cambio
        const btn = document.getElementById('btnConfirmEjection');
        // Eliminamos cualquier evento anterior (clonando el botón)
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', function() {
            document.getElementById('ejectionModal').classList.add('hidden');
            
            // IMPORTANTE: Aquí es donde llamamos al cambio DESPUÉS de cerrar el modal
            if (pendingSubstitution) {
                openSubstitutionModal(
                    pendingSubstitution.teamSide, 
                    pendingSubstitution.playerId, 
                    pendingSubstitution.playerName, 
                    pendingSubstitution.slotIndex
                );
                pendingSubstitution = null;
            }
        });
    }

    function checkEjection(playerKey, playerName, teamSide, slotIndex) {
        const stats = playerStats[playerKey].fouls;
        
        let reason = null;

        if (stats.personal >= foulLimits.personal) reason = "Límite de Faltas Personales";
        if (stats.technical >= foulLimits.technical) reason = "Límite de Faltas Técnicas";
        if (stats.unsportsmanlike >= foulLimits.unsportsmanlike) reason = "Límite de Faltas Antideportivas";
        if (stats.disqualifying >= foulLimits.disqualifying) reason = "Límite de Faltas Descalificantes";

        if (reason) {
            // 1. Pausar el juego si está corriendo
            if (!document.getElementById('startBtn').disabled) {
                // Ya está pausado, no hacer nada
            } else {
                // Está corriendo, pausamos
                clearInterval(timerInterval);
                syncTimer('stopped');
                document.getElementById('startBtn').disabled = false;
                document.getElementById('pauseBtn').disabled = true;
            }

            // --- Agregar a la lista de expulsados ---
            const playerId = parseInt(playerKey.split('-')[1]);
            if (!ejectedPlayerIds.includes(playerId)) {
                ejectedPlayerIds.push(playerId);
            }

            // 2. Llamamos al NUEVO MODAL pasando los datos necesarios
            showEjectionModal(
                `${playerName} ha alcanzado el ${reason}.\nDebe ser reemplazado inmediatamente.`, 
                {
                    teamSide: teamSide,
                    playerId: parseInt(playerKey.split('-')[1]),
                    playerName: playerName,
                    slotIndex: slotIndex
                }
            );
        }
    }

    // --- FUNCIONES DEL CRONÓMETRO ---
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    function updateDisplay() {
        document.getElementById('gameTimer').textContent = formatTime(totalSeconds);
        document.getElementById('currentPeriod').textContent = currentPeriodDisplay;
        
        const endBtn = document.getElementById('endBtn');
        if (currentPeriodDisplay < totalPeriodsConfig) {
            endBtn.textContent = 'Finalizar Periodo';
            endBtn.classList.remove('bg-red-500', 'hover:bg-red-700');
            endBtn.classList.add('bg-orange-500', 'hover:bg-orange-700');
        } else {
            endBtn.textContent = 'Finalizar Partido';
            endBtn.classList.remove('bg-orange-500', 'hover:bg-orange-700');
            endBtn.classList.add('bg-red-500', 'hover:bg-red-700');
        }
    }

    function tick() {
        if (totalSeconds > 0) {
            totalSeconds--;
            updateDisplay();
            if (totalSeconds % 5 === 0) syncTimer('running');
        } else {
            clearInterval(timerInterval);
            syncTimer('stopped');
            handlePeriodEnd();
        }
    }

    // --- ACTUALIZACIÓN DE handlePeriodEnd ---
    function handlePeriodEnd() {
        // Si NO es el último periodo, preguntamos normalmente
        if (currentPeriodDisplay < totalPeriodsConfig) {
            if(confirm('El tiempo ha terminado. ¿Deseas iniciar el Periodo ' + (currentPeriodDisplay + 1) + '?')) {
                manualNextPeriod();
            } else {
                document.getElementById('startBtn').disabled = false; 
                document.getElementById('pauseBtn').disabled = true;
            }
        } else {
            // ES EL ÚLTIMO PERIODO (o un tiempo extra previo)
            // Llamamos a nextPeriod para que el backend decida si hay empate o fin
            manualNextPeriod(); 
        }
    }

    function syncTimer(status) {
        const data = { game_id: gameId, status: status, seconds: totalSeconds };
        fetch('/games/update-timer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify(data)
        });
    }

    document.getElementById('startBtn').addEventListener('click', function() {
        this.disabled = true;
        document.getElementById('pauseBtn').disabled = false;
        document.getElementById('endBtn').disabled = false;
        syncTimer('running');
        timerInterval = setInterval(tick, 1000);
    });

    document.getElementById('pauseBtn').addEventListener('click', function() {
        this.disabled = true;
        document.getElementById('startBtn').disabled = false;
        clearInterval(timerInterval);
        syncTimer('stopped');
    });

        // --- FUNCIÓN COMPLETA: Actualizar Visual de Faltas de Equipo ---
    function updateTeamFoulsDisplay() {
        const localEl = document.getElementById('localTeamFouls');
        const awayEl = document.getElementById('awayTeamFouls');

        // Actualizar texto
        localEl.textContent = `Faltas: ${localTeamFouls}/5`;
        awayEl.textContent = `Faltas: ${awayTeamFouls}/5`;

        // Cambiar a rojo si llega a 5 (bonificación)
        if (localTeamFouls >= 5) {
            localEl.classList.remove('text-gray-700');
            localEl.classList.add('text-red-600', 'font-bold');
        } else {
            localEl.classList.add('text-gray-700');
            localEl.classList.remove('text-red-600', 'font-bold');
        }

        if (awayTeamFouls >= 5) {
            awayEl.classList.remove('text-gray-700');
            awayEl.classList.add('text-red-600', 'font-bold');
        } else {
            awayEl.classList.add('text-gray-700');
            awayEl.classList.remove('text-red-600', 'font-bold');
        }
    }
    // ------------------------------------------------------------

    // --- ACTUALIZACIÓN DE manualNextPeriod ---
    function manualNextPeriod() {
        const btn = document.getElementById('endBtn');
        if(btn) btn.innerText = 'Procesando...';

        fetch('/games/next-period', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ game_id: gameId, current_period: currentPeriodDisplay })
        })
        .then(res => {
            if (!res.ok) throw new Error(res.statusText);
            return res.json();
        })
        .then(data => {
            // Restaurar texto del botón
            if(btn) btn.innerText = (currentPeriodDisplay < totalPeriodsConfig) ? 'Finalizar Periodo' : 'Finalizar Partido';

            if (data.success) {
                
                // CASO 1: HAY EMPATE -> MOSTRAR MODAL
                if (data.status === 'tied') {
                    document.getElementById('overtimeModal').classList.remove('hidden');
                    return; // Detenemos ejecución aquí
                }

                if (data.status === 'finished') {
                    // CAMBIO: En lugar de alertar y redirigir, mostramos el resumen
                    showFinalScoreModal();
                    return; 
                }

                // CASO 3: SIGUIENTE PERIODO (Normal o Extra)
                currentPeriodDisplay = data.period;
                totalSeconds = data.seconds;
                clearInterval(timerInterval);
                
                // Actualizar UI
                document.getElementById('startBtn').disabled = false;
                document.getElementById('pauseBtn').disabled = true;
                updateDisplay();

                // Resetear faltas de equipo si empieza un nuevo periodo (opcional, según reglas)
                // En básquet usualmente las faltas se resetean por periodo (excepto en algunas ligas)
                // Tu código original las reseteaba, mantenlo si es así:
                if (data.status !== 'overtime') { // Opcional: No resetear faltas si es tiempo extra continuo
                     localTeamFouls = 0;
                     awayTeamFouls = 0;
                     updateTeamFoulsDisplay();
                }
            } else {
                alert("Error: " + (data.message || 'Desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Hubo un error al procesar el periodo.");
            if(btn) btn.disabled = false;
        });
    }

    // --- NUEVAS FUNCIONES DE TIEMPO EXTRA ---

    function closeOvertimeModal() {
        document.getElementById('overtimeModal').classList.add('hidden');
    }

    // Evento para el botón "Terminar Partido (Empate)" (dentro del Overtime Modal)
    document.getElementById('btnFinishAsTie').addEventListener('click', function() {
        if(confirm('¿El tiempo ha terminado y el resultado es empate?')) {
            // Cerramos el modal de tiempo extra
            closeOvertimeModal();
            
            // CAMBIO: Mostramos el resumen final antes de terminar
            showFinalScoreModal();
        }
    });

    // Evento para el botón "Iniciar Tiempo Extra"
    document.getElementById('btnStartOvertime').addEventListener('click', function() {
        const minutesInput = document.getElementById('overtimeMinutes');
        const minutes = parseInt(minutesInput.value);

        if (!minutes || minutes < 1) {
            alert('Por favor ingresa una duración válida.');
            return;
        }

        // Deshabilitar botón para evitar doble click
        const originalText = this.innerText; // Guardamos el texto original
        this.disabled = true;
        this.innerText = 'Iniciando...';

        fetch('/games/start-overtime', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ 
                game_id: gameId, 
                minutes: minutes 
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeOvertimeModal();
                
                // Actualizar variables globales
                currentPeriodDisplay = data.period;
                totalSeconds = data.seconds;
                
                // Actualizar UI
                updateDisplay();
                document.getElementById('startBtn').disabled = false; 
                document.getElementById('pauseBtn').disabled = true;
                
                // Feedback visual temporal
                const timerDisplay = document.getElementById('gameTimer');
                timerDisplay.classList.add('text-red-500'); 
                setTimeout(() => timerDisplay.classList.remove('text-red-500'), 5000);

                // --- NUEVO: REGISTRAR EN EL LOG ---
                if(data.action) {
                    addActionToLog(data.action);
                }
                // ---------------------------------

                // --- CORRECCIÓN: Resetear el botón para la próxima vez ---
                this.disabled = false;
                this.innerText = originalText; // "Iniciar Tiempo Extra"
                // -----------------------------------------------------------

            } else {
                alert('Error al iniciar tiempo extra: ' + (data.message || ''));
                // Resetear botón en caso de error
                this.disabled = false;
                this.innerText = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión.');
            // Resetear botón en caso de error de red
            this.disabled = false;
            this.innerText = originalText;
        });
    });

    document.getElementById('endBtn').addEventListener('click', function() {
        // Si NO es el último periodo, preguntamos normalmente
        if (currentPeriodDisplay < totalPeriodsConfig) { 
            if(confirm(`¿Terminar el Periodo ${currentPeriodDisplay} e ir al Periodo ${currentPeriodDisplay + 1}?`)) {
                manualNextPeriod();
            }
        } else {
            // SI ES EL ÚLTIMO PERIODO (O TIEMPO EXTRA):
            // En lugar de finalizar directo, llamamos a 'manualNextPeriod'
            // Esta función preguntará al Backend si hay empate.
            
            // Preguntamos confirmación al usuario primero
            if(confirm('¿El tiempo ha terminado? Se verificará el marcador.')) {
                manualNextPeriod(); 
                /* 
                Nota: 
                1. Si hay empate -> El Backend devuelve 'tied' y se abre el Modal.
                2. Si hay ganador -> El Backend devuelve 'finished' y redirige al calendario.
                */
            }
        }
    });

    function endGame() {
        const finishBtn = document.querySelector('button[onclick="confirmGameFinish()"]');
        if (finishBtn) {
            finishBtn.innerText = 'Procesando...';
            finishBtn.disabled = true;
        }

        fetch('/games/finish-game', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ game_id: gameId })
        })
        .then(response => {
            // --- NUEVA LÓGICA: DETECTAR SI NOS REDIRIGIERON (Sesión Expirada) ---
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                // Si no recibimos JSON, es probable que sea HTML (Login página o Error 500)
                throw new Error("REDIRECT_DETECTED"); 
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                alert('Error al finalizar: ' + (data.message || 'Desconocido'));
                // Restaurar botón
                if (finishBtn) {
                    finishBtn.innerText = 'Sí, Finalizar Partido';
                    finishBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error en endGame:', error);

            // --- MANEJO DEL ERROR DE REDIRECCIÓN ---
            if (error.message === "REDIRECT_DETECTED") {
                alert("Tu sesión ha expirado o hubo un error de autenticación.\nLa página se recargará para que inicies sesión.");
                window.location.reload(); // Recargamos, Laravel nos mandará al login automáticamente
            } else {
                alert('Ocurrió un error: ' + error.message);
            }

            // Restaurar botón
            if (finishBtn) {
                finishBtn.innerText = 'Sí, Finalizar Partido';
                finishBtn.disabled = false;
            }
        });
    }

    // --- ACCIONES DE JUEGO ---
    
    // Función para registrar puntos (modificada para recibir slotIndex)
    function recordPoint(playerId, teamSide, points, slotIndex) {
        triggerButtonEffect();
        const data = {
            game_id: gameId,
            player_id: playerId,
            team_side: teamSide,
            action_type: 'point_scored',
            value: points,
            period: currentPeriodDisplay,
            seconds: totalSeconds
        };

        sendAction(data, (response) => {
            if (teamSide === 'local') {
                document.getElementById('localScore').textContent = response.localScore;
            } else {
                document.getElementById('awayScore').textContent = response.awayScore;
            }

            const playerKey = `${teamSide}-${playerId}`;
            playerStats[playerKey].points += points;
            document.getElementById(`${playerKey}-points`).textContent = playerStats[playerKey].points;
            addActionToLog(response.action);
        });
    }

    function recordFoul(playerId, teamSide, foulType, slotIndex) {
        triggerButtonEffect();
        const data = {
            game_id: gameId,
            player_id: playerId,
            team_side: teamSide,
            action_type: foulType,
            period: currentPeriodDisplay,
            seconds: totalSeconds
        };

        sendAction(data, (response) => {
            const playerKey = `${teamSide}-${playerId}`;
            
            // Mapeo de tipos
            let typeKey = 'personal';
            if(foulType === 'foul_technical') typeKey = 'technical';
            if(foulType === 'foul_unsportsmanlike') typeKey = 'unsportsmanlike';
            if(foulType === 'foul_disqualifying') typeKey = 'disqualifying';

            // Incrementar contador específico
            playerStats[playerKey].fouls[typeKey]++;

            // Calcular total para display
            const f = playerStats[playerKey].fouls;
            const totalFouls = f.personal + f.technical + f.unsportsmanlike + f.disqualifying;
            
            document.getElementById(`${playerKey}-fouls`).textContent = totalFouls;
            addActionToLog(response.action);

            // --- NUEVO: Incrementar faltas de equipo ---
            if (teamSide === 'local') {
                localTeamFouls++;
            } else {
                awayTeamFouls++;
            }
            updateTeamFoulsDisplay();
            // ---------------------------------------

            // --- CHEQUEO DE EXPULSIÓN AUTOMÁTICA ---
            // CORRECCIÓN: Usamos el nombre que viene del servidor, no una variable inexistente
            const playerName = response.action.player ? response.action.player.name : 'Jugador';
            checkEjection(playerKey, playerName, teamSide, slotIndex);
        });
    }

    function recordTimeout(teamSide) {
        clearInterval(timerInterval);
        syncTimer('stopped');
        document.getElementById('startBtn').disabled = false;
        document.getElementById('pauseBtn').disabled = true;

        if (teamSide === 'local' && localTimeouts <= 0) {
            alert('No quedan tiempos fuera para el equipo local');
            return;
        }
        if (teamSide === 'away' && awayTimeouts <= 0) {
            alert('No quedan tiempos fuera para el equipo visitante');
            return;
        }

        const data = {
            game_id: gameId,
            player_id: null,
            team_side: teamSide,
            action_type: 'timeout_called',
            period: currentPeriodDisplay
        };

        sendAction(data, (response) => {
            if (teamSide === 'local') {
                localTimeouts--;
                document.getElementById('localTimeouts').textContent = localTimeouts;
            } else {
                awayTimeouts--;
                document.getElementById('awayTimeouts').textContent = awayTimeouts;
            }
            addActionToLog(response.action);
        });
    }

    function sendAction(data, callback) {
        fetch('/games/record-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                callback(data);
            } else {
                alert('Error al registrar la acción');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar la acción');
        });
    }

    function addActionToLog(action) {
        const actionsLog = document.getElementById('actionsLog');
        const actionElement = document.createElement('div');
        actionElement.className = 'border-b pb-2 mb-2';
        
        let actionText = '';

        // Determinar color del texto (Azul, Rojo o Gris para Sistema)
        let teamColor = 'text-gray-600'; // Default: Sistema
        if (action.team_side === 'local') teamColor = 'text-blue-600';
        if (action.team_side === 'away') teamColor = 'text-red-600';

        // Determinar nombre del autor (Jugador o Sistema)
        let actorName = 'Sistema';
        if (action.player) {
            actorName = `${action.player.name} (${action.player.number || 'N/A'})`;
        } else if (action.team_side === 'local') {
             // Fallback si es acción de equipo pero sin jugador (ej. timeout)
             actorName = '<?php echo e($game->localTeam->name); ?>';
        } else if (action.team_side === 'away') {
             actorName = '<?php echo e($game->awayTeam->name); ?>';
        }

        switch(action.action_type) {
            case 'point_scored':
                actionText = `Anotó ${action.value} punto${action.value > 1 ? 's' : ''}`;
                break;
            case 'foul_personal':
                actionText = 'Falta Personal';
                break;
            case 'foul_technical':
                actionText = 'Falta Técnica';
                break;
            case 'foul_unsportsmanlike':
                actionText = 'Falta Antideportiva';
                break;
            case 'foul_disqualifying':
                actionText = 'Falta Descalificatoria';
                break;
            case 'timeout_called':
                actionText = 'Tiempo Fuera';
                break;
            case 'substitution':
                if (action.playerOutName) {
                    actionText = `Cambio: Sale ${action.playerOutName}`; 
                } else {
                    actionText = 'Cambio: Entra (Llena Vacante)';
                }
                break;
            // --- NUEVOS CASOS ---
            case 'overtime_started':
                actionText = `Inició Tiempo Extra (${action.value} min)`;
                break;
            case 'compensation_added':
                actionText = `Agregó ${action.value} min de compensación`;
                break;
            // ----------------------
            default:
                actionText = 'Acción registrada';
                break;
        }

        actionElement.innerHTML = `
            <p class="text-sm">
                <span class="font-semibold">${new Date().toLocaleTimeString()}</span> - 
                <span class="font-semibold ${teamColor}">
                    ${actorName}
                </span>
                : ${actionText}
            </p>
        `;
        
        actionsLog.insertBefore(actionElement, actionsLog.firstChild);
    }

    updateDisplay();
    updateTeamFoulsDisplay();

    // Variables globales para el modal
    let currentSubstitution = {
        game_id: gameId,
        team_side: null,
        player_out_id: null
    };

    function openSubstitutionModal(teamSide, playerOutId, playerOutName, slotIndex) {
        currentSubstitution = {
            game_id: gameId,
            team_side: teamSide,
            player_out_id: playerOutId,
            slot_index: slotIndex // <--- CORREGIDO: Faltaba esta línea
        };

        document.getElementById('playerOutName').textContent = playerOutName;
        document.getElementById('benchList').innerHTML = '<p class="p-4 text-center text-gray-500">Cargando jugadores...</p>';
        document.getElementById('substitutionModal').classList.remove('hidden');

        // --- NUEVO: Obtener IDs de jugadores ACTIVOS directamente del DOM para filtrar ---
        const activeRows = document.querySelectorAll(`li[id^="${teamSide}-row-"]`);
        const activePlayerIds = [];
        
        activeRows.forEach(row => {
            const btn = row.querySelector('.change-player-btn');
            if (btn) {
                const pid = btn.getAttribute('data-player-id');
                if (pid && pid !== 'null') {
                    activePlayerIds.push(parseInt(pid));
                }
            }
        });
        // -----------------------------------------------------------------

        fetch(`/games/${gameId}/bench-players?team_side=${teamSide}`)
            .then(response => response.json())
            .then(players => {
                const list = document.getElementById('benchList');
                list.innerHTML = '';

                if (players.error) {
                     list.innerHTML = `<p class="p-4 text-center text-red-500">Error: ${players.error}</p>`;
                     return;
                }
                if (!Array.isArray(players)) {
                     list.innerHTML = '<p class="p-4 text-center text-red-500">Formato inválido.</p>';
                     return;
                }

                // --- FILTRO FINAL: Banca + No Expulsados ---
                const validPlayers = players.filter(p => {
                    return !activePlayerIds.includes(p.id) && !ejectedPlayerIds.includes(p.id);
                });

                if (validPlayers.length === 0) {
                    list.innerHTML = `
                        <div class="p-4 text-center border-b bg-yellow-50">
                            <p class="text-sm font-semibold text-yellow-800 mb-2">No hay jugadores disponibles (banca vacía o todos expulsados).</p>
                        </div>
                    `;
                    
                    const btnVacant = document.createElement('button');
                    btnVacant.className = "w-full text-center px-4 py-4 bg-gray-800 text-white font-bold hover:bg-gray-900 flex justify-center items-center";
                    btnVacant.innerHTML = `
                        <span>DEJAR VACANTE</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    `;
                    btnVacant.onclick = () => executeSubstitution(playerOutId, null);
                    list.appendChild(btnVacant);

                } else {
                    validPlayers.forEach(player => {
                        const btn = document.createElement('button');
                        const isEjected = ejectedPlayerIds.includes(player.id);
                        
                        if (isEjected) {
                            btn.className = "w-full text-left px-4 py-2 bg-gray-100 text-gray-400 border-b last:border-0 flex justify-between items-center cursor-not-allowed";
                            btn.disabled = true;
                            btn.innerHTML = `
                                <span class="font-semibold line-through">${player.name} (${player.number ?? 'N/A'})</span>
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded font-bold">EXPULSADO</span>
                            `;
                        } else {
                            btn.className = "w-full text-left px-4 py-2 hover:bg-blue-50 border-b last:border-0 flex justify-between items-center";
                            btn.innerHTML = `
                                <span class="font-semibold">${player.name} (${player.number ?? 'N/A'})</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">ENTRAR</span>
                            `;
                            btn.onclick = () => executeSubstitution(playerOutId, player.id);
                        }
                        list.appendChild(btn);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('benchList').innerHTML = '<p class="p-4 text-center text-red-500">Error al cargar jugadores.</p>';
            });
    }

    document.getElementById('closeModalBtn').addEventListener('click', () => {
        document.getElementById('substitutionModal').classList.add('hidden');
    });

    function executeSubstitution(playerOutId, playerInId) {
        if(!confirm(playerOutId ? '¿Confirmar cambio de jugador?' : '¿Confirmar que este jugador ocupa el lugar?')) return;

        const data = {
            ...currentSubstitution,
            player_in_id: playerInId, 
            player_out_id: playerOutId
        };

        fetch('/games/substitute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                document.getElementById('substitutionModal').classList.add('hidden');
                
                const teamSide = currentSubstitution.team_side;
                const slotIndex = currentSubstitution.slot_index;
                
                if (typeof slotIndex === 'undefined' || slotIndex === null) {
                    console.error("Error crítico: slot_index no está definido en currentSubstitution");
                    return;
                }

                const playerOutName = result.playerOut ? result.playerOut.name : 'Vacante';
                
                if (result.action) {
                    result.action.playerOutName = playerOutName;
                }

                const rowId = `${teamSide}-row-${slotIndex}`;
                const targetLi = document.getElementById(rowId);

                if (!targetLi) {
                    console.error("No se encontró la fila ID:", rowId);
                    return;
                }

                // --- CASO 1: Dejar Vacante ---
                if (playerInId === null) {
                    targetLi.classList.remove('bg-gray-50/80', 'border-gray-100');
                    targetLi.classList.add('bg-white', 'border-dashed', 'border-gray-300');
                    
                    targetLi.innerHTML = `
                        <div class="flex items-center justify-center w-full md:w-[12%] gap-2 border-b border-gray-100 md:border-b-0 pb-2 md:pb-0">
                            <div class="flex flex-col items-center text-center w-full">
                                <span class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-300 leading-none tracking-tighter">-</span>
                                <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wide mt-0 leading-none truncate w-full">Vacante</span>
                                <!-- Stats Vacantes (Mantenemos estructura para alineación) -->
                                <div class="flex flex-row gap-1.5 text-[9px] md:text-[10px] text-gray-300 font-semibold mt-0.5 leading-tight">
                                    <span>F: -</span>
                                    <span>P: -</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 md:gap-1 lg:gap-1 overflow-x-auto hide-scrollbar w-full md:flex-1 justify-end min-w-0 mt-2 md:mt-0">
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+1</button>
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+2</button>
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>+3</button>
                            <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>P</button>
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>A</button>
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>T</button>
                            <button class="bg-white text-gray-300 border border-gray-200 rounded text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 cursor-not-allowed shrink-0" disabled>D</button>
                            <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                            <button class="bg-gray-200 text-gray-400 border border-gray-300 rounded hover:bg-gray-300 active:bg-gray-400 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-1.5 px-2 md:px-3 lg:px-4 change-player-btn shrink-0" 
                                data-team-side="${teamSide}" 
                                data-player-id="null" 
                                data-player-name="Slot Vacante"
                                data-player-number="" 
                                data-slot-index="${slotIndex}"
                                title="Llenar Espacio">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 md:w-5 md:h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 8H5M8 5l-3 3 3 3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 16H19M16 13l3 3-3 3" />
                                </svg>
                            </button>
                        </div>
                    `;
                } else {
                    // --- CASO 2: Entra Jugador Normal ---
                    const playerIn = result.playerIn;
                    const playerKey = `${teamSide}-${playerIn.id}`;

                    if (!playerStats[playerKey]) {
                        playerStats[playerKey] = { 
                            points: 0, 
                            fouls: { personal: 0, technical: 0, unsportsmanlike: 0, disqualifying: 0 } 
                        };
                    }

                    const f = playerStats[playerKey].fouls;
                    const totalFouls = f.personal + f.technical + f.unsportsmanlike + f.disqualifying;
                    const currentPoints = playerStats[playerKey].points;

                    targetLi.classList.remove('bg-white', 'border-dashed', 'border-gray-300');
                    targetLi.classList.add('border-b', 'border-gray-100'); 

                    targetLi.innerHTML = `
                        <div class="flex items-center justify-center w-full md:w-[12%] gap-2 border-b border-gray-100 md:border-b-0 pb-2 md:pb-0">
                            <div class="flex flex-col items-center text-center w-full">
                                <span class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-none tracking-tighter">
                                    ${playerIn.number ?? '-'}
                                </span>
                                <span class="text-[9px] md:text-[10px] font-bold text-slate-600 uppercase tracking-wide mt-0 leading-none truncate w-full">
                                    ${playerIn.name}
                                </span>
                                <!-- Stats ACTIVOS (Con IDs para actualización JS) -->
                                <div class="flex flex-row gap-1.5 text-[9px] md:text-[10px] text-gray-400 font-semibold mt-0.5 leading-tight">
                                    <span>F: <span class="player-fouls text-gray-600" id="${teamSide}-${playerIn.id}-fouls">${totalFouls}</span></span>
                                    <span>P: <span class="player-points text-gray-600" id="${teamSide}-${playerIn.id}-points">${currentPoints}</span></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-1 md:gap-1 lg:gap-1 overflow-x-auto hide-scrollbar w-full md:flex-1 justify-end min-w-0 mt-2 md:mt-0">
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(${playerIn.id}, '${teamSide}', 1, ${slotIndex})">+1</button>
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(${playerIn.id}, '${teamSide}', 2, ${slotIndex})">+2</button>
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordPoint(${playerIn.id}, '${teamSide}', 3, ${slotIndex})">+3</button>
                            <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                            
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(${playerIn.id}, '${teamSide}', 'foul_personal', ${slotIndex})">P</button>
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(${playerIn.id}, '${teamSide}', 'foul_unsportsmanlike', ${slotIndex})">A</button>
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(${playerIn.id}, '${teamSide}', 'foul_technical', ${slotIndex})">T</button>
                            <button class="bg-white text-gray-800 border border-gray-300 rounded shadow-sm hover:border-gray-400 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 whitespace-nowrap shrink-0" onclick="recordFoul(${playerIn.id}, '${teamSide}', 'foul_disqualifying', ${slotIndex})">D</button>
                            
                            <div class="w-px h-4 md:h-5 bg-gray-300 shrink-0 mx-0.5"></div>
                            
                            <button class="bg-gray-200 text-gray-400 border border-gray-300 rounded hover:bg-gray-300 active:bg-gray-400 active:scale-95 transition-all text-[11px] md:text-xs lg:text-sm font-bold py-2.5 px-2 md:px-3 lg:px-4 change-player-btn shrink-0" 
                                data-team-side="${teamSide}" 
                                data-player-id="${playerIn.id}" 
                                data-player-name="${playerIn.name}" 
                                data-player-number="${playerIn.number ?? ''}"
                                data-slot-index="${slotIndex}"
                                title="Cambiar Jugador">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 md:w-5 md:h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 8H5M8 5l-3 3 3 3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 16H19M16 13l3 3-3 3" />
                                </svg>
                            </button>
                        </div>
                    `;
                }
                
                if(result.action) {
                    addActionToLog(result.action);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al realizar el cambio');
        });
    }
    // --- PUNTO C: MANEJO SEGURO DE EVENTOS PARA CAMBIO DE JUGADOR ---
    // Usamos delegación de eventos para manejar los botones con la clase 'change-player-btn'
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(event) {
            // Verificamos si el clic fue en un botón de cambio o dentro de uno (para que funcione el SVG)
            const btn = event.target.closest('.change-player-btn');
            if (btn) {
                event.preventDefault(); // Evitar comportamiento por defecto si es un link dentro del botón

                // Leemos los datos de forma segura desde los atributos
                const teamSide = btn.getAttribute('data-team-side');
                const playerId = btn.getAttribute('data-player-id') === 'null' ? null : btn.getAttribute('data-player-id');
                const playerName = btn.getAttribute('data-player-name');
                const slotIndex = btn.getAttribute('data-slot-index');

                // Llamamos a la función original con los datos limpios
                openSubstitutionModal(teamSide, playerId, playerName, parseInt(slotIndex));
            }
        });
    });
        // --- PUNTO D: POLLING PARA SINCRONIZACIÓN EN TIEMPO REAL ---
    
    // Función que consulta el estado actual
    function syncGameData() {
        fetch(`/games/${gameId}/status`)
            .then(response => response.json())
            .then(data => {
                // 1. Sincronizar Puntuación
                const currentLocalScore = document.getElementById('localScore').textContent;
                const currentAwayScore = document.getElementById('awayScore').textContent;

                // Solo actualizamos el DOM si cambió (ahorro de renderizado)
                if (data.localScore != currentLocalScore) {
                    document.getElementById('localScore').textContent = data.localScore;
                }
                if (data.awayScore != currentAwayScore) {
                    document.getElementById('awayScore').textContent = data.awayScore;
                }

                // 2. Sincronizar Periodo
                const currentPeriod = document.getElementById('currentPeriod').textContent;
                if (data.period != currentPeriod) {
                    document.getElementById('currentPeriod').textContent = data.period;
                    currentPeriodDisplay = data.period; // Actualizar variable global JS
                    
                    // Si cambió el periodo, resetear visuales de botones de fin
                    const endBtn = document.getElementById('endBtn');
                    if (data.period < totalPeriodsConfig) {
                        endBtn.textContent = 'Finalizar Periodo';
                    } else {
                        endBtn.textContent = 'Finalizar Partido';
                    }
                }

                // 3. Sincronizar Estado del Juego (Si alguien finalizó el juego)
                if (data.status === 'finished') {
                    clearInterval(pollingInterval); // Detener polling
                    // Opcional: Redirigir al usuario
                    // alert('El partido ha finalizado por otro usuario.');
                    // window.location.href = '/tournaments/' + <?php echo e($game->tournament_id); ?> + '/schedule';
                }
            })
            .catch(error => console.log('Error al sincronizar:', error));
    }

    // CORRECCIÓN: Definir la variable globalmente al inicio del script o aquí
    let pollingInterval = null;

    // Iniciar el polling cada 1 segundos
    if (document.getElementById('localScore')) {
        pollingInterval = setInterval(syncGameData, 1000);
    }

    function undoLastAction() {
    if (!confirm('¿Estás seguro de deshacer la última acción?')) return;

    fetch('/games/undo-last-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ game_id: gameId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 1. Actualizar Marcador General
            document.getElementById('localScore').textContent = data.localScore;
            document.getElementById('awayScore').textContent = data.awayScore;

            // 2. Actualizar Tiempos Muertos
            // maxTimeouts está definido arriba en tu script (5)
            const maxT = maxTimeouts; 
            
            localTimeouts = maxT - data.localTimeoutsUsed;
            awayTimeouts = maxT - data.awayTimeoutsUsed;
            
            if(document.getElementById('localTimeouts')) document.getElementById('localTimeouts').textContent = localTimeouts;
            if(document.getElementById('awayTimeouts')) document.getElementById('awayTimeouts').textContent = awayTimeouts;

            // 3. Actualizar Faltas de Equipo
            localTeamFouls = data.localTeamFouls;
            awayTeamFouls = data.awayTeamFouls;
            updateTeamFoulsDisplay();

            // 4. Actualizar Estadísticas de Jugadores
            // data.playerStats es un array nuevo desde cero.
            // Primero, reseteamos los contadores del objeto global JS para evitar errores
            // o sobrescribimos directamente si el jugador existe.
            
            for (const [key, stats] of Object.entries(data.playerStats)) {
                // key es algo como "local-15"
                
                // Actualizar objeto global
                if (!playerStats[key]) {
                    playerStats[key] = { points: 0, fouls: { personal: 0, technical: 0, unsportsmanlike: 0, disqualifying: 0 } };
                }
                playerStats[key].points = stats.points;
                playerStats[key].fouls = stats.fouls;

                // Actualizar DOM
                const totalFouls = stats.fouls.personal + stats.fouls.technical + stats.fouls.unsportsmanlike + stats.fouls.disqualifying;
                
                const pointsEl = document.getElementById(`${key}-points`);
                const foulsEl = document.getElementById(`${key}-fouls`);
                
                if (pointsEl) pointsEl.textContent = stats.points;
                if (foulsEl) foulsEl.textContent = totalFouls;
            }

            // 5. Eliminar primer elemento del Log (visual)
            const log = document.getElementById('actionsLog');
            if (log && log.firstElementChild) {
                log.removeChild(log.firstElementChild);
            }

        } else {
            alert(data.message || 'No se pudo deshacer la acción');
        }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al deshacer la acción');
        });
    }
    // --- FUNCIONES DEL MODAL DE RESUMEN FINAL ---

    function showFinalScoreModal() {
        // 1. Capturar los puntajes actuales del DOM
        const localScore = document.getElementById('localScore').textContent;
        const awayScore = document.getElementById('awayScore').textContent;

        // 2. Ponerlos en el modal
        document.getElementById('modalFinalLocalScore').textContent = localScore;
        document.getElementById('modalFinalAwayScore').textContent = awayScore;

        // 3. Mostrar el modal
        document.getElementById('finalScoreModal').classList.remove('hidden');
    }

    function closeFinalScoreModal() {
        document.getElementById('finalScoreModal').classList.add('hidden');
    }

    // Agregar tiempo de compensación y continuar el juego
    function addCompensationTime() {
        const mins = parseInt(document.getElementById('compensationMinutes').value) || 0;
        
        if (mins <= 0) {
            alert('Por favor ingresa minutos válidos.');
            return;
        }

        // Deshabilitar botón visualmente para evitar doble clic
        const btn = document.querySelector('button[onclick="addCompensationTime()"]');
        const originalText = btn.innerText;
        btn.innerText = 'Agregando...';
        btn.disabled = true;

        // Hacemos la petición al backend
        fetch('/games/add-compensation-time', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                game_id: gameId, 
                minutes: mins 
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 1. Sincronizamos variables locales con lo que devolvió el servidor
                totalSeconds = data.seconds;
                
                // 2. Actualizamos el reloj visualmente
                updateDisplay();
                
                // 3. Habilitamos controles del juego para que sigan
                document.getElementById('startBtn').disabled = false;
                document.getElementById('pauseBtn').disabled = true;

                // 4. Cerramos el modal de finalización
                closeFinalScoreModal();
                
                // 5. Agregamos la entrada al Log Visual
                if(data.action) {
                    addActionToLog(data.action);
                }

            } else {
                alert('Error al agregar tiempo: ' + (data.message || ''));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión al agregar tiempo.');
        })
        .finally(() => {
            // Restaurar botón
            btn.innerText = originalText;
            btn.disabled = false;
        });
    }

    function confirmGameFinish() {
        closeFinalScoreModal();
        endGame();
    }
    // --- SCRIPT PARA CONVERTIR HORAS DEL SERVIDOR A HORA LOCAL ---
    document.addEventListener("DOMContentLoaded", function() {
        const timeElements = document.querySelectorAll('.convert-to-local-time');
        timeElements.forEach(el => {
            const isoTime = el.getAttribute('data-time');
            if (isoTime) {
                // El navegador convierte automáticamente la fecha ISO a la zona horaria del usuario
                const date = new Date(isoTime);
                // Formateamos a HH:MM:SS
                el.textContent = date.toLocaleTimeString('es-MX', { hour12: true }); 
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
<?php endif; ?><?php /**PATH C:\Users\luism\gemini-work\sistemaTorneos\resources\views/games/live.blade.php ENDPATH**/ ?>