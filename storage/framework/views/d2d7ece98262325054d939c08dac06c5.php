<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrossoverMX | Tabla de Posiciones</title>
    
    <!-- Fuente: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Iconos: FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS compiled via Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        /* --- VARIABLES (NARANJA + AZUL MARINO) --- */
        :root {
            --brand-orange: #ff6b00;
            --brand-orange-light: #fff7ed;
            --brand-blue: #1e293b;
            --brand-blue-light: #f1f5f9;
            --brand-blue-glow: rgba(30, 41, 59, 0.1);
            
            --bg-body: #ffffff;
            --bg-alt: #f8fafc;
            
            --text-main: #1e293b;
            --text-body: #334155;
            --text-muted: #64748b;
            --text-white: #ffffff;

            --radius-xl: 24px;
            --radius-lg: 16px;
            --shadow-soft: 0 10px 30px rgba(0,0,0,0.05);
            --shadow-hover: 0 20px 40px rgba(0,0,0,0.08);
            --shadow-blue: 0 10px 30px rgba(30, 41, 59, 0.15);
            --shadow-orange: 0 10px 30px rgba(255, 107, 0, 0.15);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9 !important; 
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px) !important;
            background-size: 24px 24px !important;
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Animación de movimiento de esferas (Misma que Login/Welcome) */
        @keyframes blob-full-screen {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40vw, 30vh) scale(1.1); }
            66% { transform: translate(-30vw, -20vh) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }

        .animate-blob-full {
            animation: blob-full-screen 15s infinite ease-in-out; 
        }
        
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* --- NAV (Copiado de landing) --- */
        header {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1100px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--brand-orange);
            border-radius: 50px;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }

        nav { display: flex; justify-content: space-between; align-items: center; }

        .logo-container { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        
        .logo-img { height: 45px; width: auto; object-fit: contain; }

        .logo-text {
            font-weight: 900;
            font-size: 1.3rem;
            letter-spacing: -0.5px;
            color: var(--brand-blue);
            text-transform: uppercase;
        }
        .logo-text span { color: var(--brand-orange); }

        .nav-links { display: flex; gap: 30px; align-items: center; }

        .nav-links a {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-body);
            transition: color 0.2s;
            position: relative;
            text-shadow: 0 1px 2px rgba(255,255,255,0.8);
        }
        .nav-links a:hover { color: var(--brand-orange); }

        .btn {
            padding: 10px 28px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--brand-orange);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
        }

        .btn-primary:hover {
            background: #e65c00;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.4);
        }

        @media (max-width: 768px) {
            .nav-links { display: none !important; }
        }

        /* --- MODO CLARO STANDINGS (Copiado de la sección administrativa) --- */
        @import url('https://fonts.googleapis.com/css2?family=Graduate&display=swap');

        /* --- MODO CLARO STANDINGS (Copiado de la sección administrativa) --- */
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

        /* --- FOOTER COMPARTIDO CON LANDING --- */
        footer {
            background: #1e293b;
            color: #cbd5e1;
            padding: 60px 0;
            text-align: center;
            border-top: 4px solid #ff6b00;
        }
        footer p { color: #e2e8f0; margin-top: 10px; font-size: 0.875rem; }
        .logo-container-footer {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo-img-footer {
            height: 40px;
            width: auto;
        }
        .logo-text-footer {
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            color: white;
            text-transform: uppercase;
        }
        .logo-text-footer span { color: #ff6b00; }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-top: 20px;
        }
        .social-icon {
            color: #cbd5e1;
            font-size: 1.6rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-decoration: none;
        }
        .social-icon:hover {
            color: #ff6b00;
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="relative min-h-screen font-sans text-gray-900 antialiased">

    <!-- CAPA NUBES DE COLOR (Tonos exactos del Welcome y Login) -->
    <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
        <!-- Nube 1: Naranja light -->
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-[#fff7ed] rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-blob-full"></div>
        <!-- Nube 2: Gris Azulado -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#e2e8f0] rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-blob-full animation-delay-2000"></div>
        <!-- Nube 3: Slate light -->
        <div class="absolute -bottom-40 left-1/3 w-[500px] h-[500px] bg-slate-100 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-blob-full animation-delay-4000"></div>
    </div>

    <!-- Header Navigation -->
    <header id="navbar">
        <nav>
            <div class="logo-container" onclick="window.location.href='<?php echo e(route('home')); ?>'">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="CrossoverMX Logo" class="logo-img">
                <div class="logo-text">Crossover<span>MX</span></div>
            </div>
            
            <ul class="nav-links">
                <li><a href="<?php echo e(route('home')); ?>#summary">Impacto</a></li>
                <li><a href="<?php echo e(route('home')); ?>#features">Torneos</a></li>
                <li><a href="<?php echo e(route('home')); ?>#calendar-logic">Calendario</a></li>
                <li><a href="<?php echo e(route('public.standings')); ?>" style="color: var(--brand-orange); font-weight: bold;">Posiciones</a></li>
            </ul>
            
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Entrar</a>
        </nav>
    </header>

    <!-- Main Container -->
    <main class="w-[96%] md:w-[90%] mx-auto pt-32 pb-[10vh]">
        
        <!-- Titulo y Selector de Torneo -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-white/50 p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tabla de Posiciones</h1>
                    <p class="text-gray-500 mt-1">Selecciona uno de los torneos activos para consultar los resultados y estadísticas en tiempo real.</p>
                </div>
                
                <div class="w-full md:w-auto">
                    <form action="<?php echo e(route('public.standings')); ?>" method="GET" class="flex items-center gap-3">
                        <label for="tournament_id" class="text-sm font-semibold text-gray-600 shrink-0 hidden sm:inline">Torneo:</label>
                        <select name="tournament_id" id="tournament_id" onchange="this.form.submit()" class="block w-full md:w-80 rounded-full border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5 px-4 bg-gray-50 text-gray-800 font-medium">
                            <?php if($tournaments->isEmpty()): ?>
                                <option value="">Sin Torneos</option>
                            <?php else: ?>
                                <option value="" <?php echo e(!$selectedTournamentId ? 'selected' : ''); ?>>Selecciona un torneo</option>
                                <?php $__currentLoopData = $tournaments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($t->id); ?>" <?php echo e($selectedTournamentId == $t->id ? 'selected' : ''); ?>>
                                        <?php echo e($t->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resultados del Torneo Seleccionado -->
        <?php if($tournament): ?>
            <div>
                <!-- Información del Torneo -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide"><?php echo e($tournament->name); ?></h2>
                        <?php if($tournament->status === 'finished'): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 mt-1.5 border border-gray-200">
                                <span class="h-2 w-2 rounded-full bg-gray-500"></span>
                                Terminado
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 mt-1.5">
                                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                                Activo
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- LOOP PRINCIPAL DE GRUPOS / ETAPAS -->
                <?php $__currentLoopData = $standingsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($groupName === 'mode'): ?> <?php continue; ?> <?php endif; ?>
                    
                    <div class="mb-10 bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white/50 shadow-sm">
                        
                        <!-- ================================================================= -->
                        <!--        CASO DOBLE ELIMINATORIA                                    -->
                        <!-- ================================================================= -->
                        <?php if(isset($data['mode']) && $data['mode'] === 'double_elimination_grouped'): ?>
                            
                            <?php
                                $totalEquipos = 0;
                                if (isset($data['bracket']['winner_bracket']) && isset($data['bracket']['winner_bracket'][0])) {
                                    $totalEquipos = count($data['bracket']['winner_bracket'][0]) * 2;
                                }
                            ?>

                            <!-- Encabezado del Grupo -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 border-b pb-3 border-gray-200 gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <span class="text-gray-400 text-base font-normal mr-2">Grupo:</span><?php echo e($groupName); ?>

                                    </h3>
                                    <span class="text-sm text-gray-500">
                                        <?php echo e($totalEquipos); ?> Equipos
                                    </span>
                                </div>
                            </div>

                            <!-- Contenedor del Bracket con Scroll Horizontal -->
                            <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner">
                                <div class="mb-6 border-b border-gray-200 pb-3 flex justify-between items-center">
                                    <h4 class="text-base font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                        <i class="fa-solid fa-trophy text-yellow-500"></i> FASE DOBLE ELIMINATORIA - ÁRBOL DE TORNEO
                                    </h4>
                                </div>

                                <div class="overflow-x-auto pb-6 pt-2 nba-scroll">
                                    <div class="inline-flex items-stretch justify-between min-w-full gap-8 md:gap-12 p-2">
                                        
                                        <!-- ================================================================= -->
                                        <!-- 1. BRACKET DE GANADORES (IZQUIERDA A DERECHA)                     -->
                                        <!-- ================================================================= -->
                                        <?php
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
                                        ?>

                                        <div class="flex flex-col gap-4 border-r border-dashed border-blue-200 pr-6 shrink-0">
                                            <div class="nba-header">BRACKET DE GANADORES</div>

                                            <div class="flex flex-row items-center gap-2 md:gap-4 h-full">
                                                <?php $__currentLoopData = $data['bracket']['winner_bracket']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roundIndex => $games): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex flex-col justify-around gap-6 h-full min-w-[240px]">
                                                        <!-- Encabezado de Ronda -->
                                                        <div class="text-center">
                                                            <?php
                                                                $totalWinnerRounds = count($data['bracket']['winner_bracket']);
                                                                $roundNum = $roundIndex + 1;
                                                                if ($roundNum == $totalWinnerRounds) {
                                                                    $label = 'Final';
                                                                } elseif ($roundNum == $totalWinnerRounds - 1) {
                                                                    $label = 'Semifinal';
                                                                } else {
                                                                    $label = 'Ronda ' . $roundNum;
                                                                }
                                                            ?>
                                                            <div class="nba-header"><?php echo e($label); ?></div>
                                                        </div>

                                                        <!-- Partidos de la Ronda -->
                                                        <div class="flex flex-col justify-around gap-6 flex-1">
                                                            <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="nba-card w-60 shadow-md hover:shadow-xl transition-all duration-200 border-l-4 border-l-blue-500 relative">
                                                                    <!-- Local -->
                                                                    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->local_team_score > $game->away_team_score,
                                                                        'nba-loser' => $game->away_team_score > $game->local_team_score
                                                                    ]); ?>">
                                                                        <img src="<?php echo e(asset('storage/' . ($game->localTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name"><?php echo e($game->localTeam->name ?? 'Por definir'); ?></span>
                                                                        <span class="nba-team-score <?php echo e(is_numeric($game->local_team_score ?? ($game->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($game->local_team_score ?? ($game->localTeam ? '0' : '-')); ?></span>
                                                                    </div>
                                                                    <!-- Visitante -->
                                                                    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                        'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                    ]); ?>">
                                                                        <img src="<?php echo e(asset('storage/' . ($game->awayTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name"><?php echo e($game->awayTeam->name ?? 'Por definir'); ?></span>
                                                                        <span class="nba-team-score <?php echo e(is_numeric($game->away_team_score ?? ($game->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($game->away_team_score ?? ($game->awayTeam ? '0' : '-')); ?></span>
                                                                    </div>
                                                                    <!-- Estado del Juego Centrado -->
                                                                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                        <?php if(($game->status ?? 'pending') === 'pending'): ?>
                                                                            <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                        <?php elseif(($game->status ?? 'pending') === 'playing'): ?>
                                                                            <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                        <?php elseif(($game->status ?? 'pending') === 'finished'): ?>
                                                                            <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            <?php
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
                                                            ?>
                                                            
                                                            <!-- BYEs Tradicionales -->
                                                            <?php $__currentLoopData = $normalByes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $byeTeamId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                ?>
                                                                <?php if($byeTeam): ?>
                                                                    <div class="nba-card w-60 border-l-4 border-l-orange-500 bg-orange-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                        <div class="absolute right-2 top-2 z-10">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 border border-orange-200">
                                                                                BYE
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex items-center gap-3 py-1">
                                                                            <img src="<?php echo e(asset('storage/' . ($byeTeam->image_path ?? ''))); ?>" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                            <div class="flex flex-col min-w-0">
                                                                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate"><?php echo e($byeTeam->name); ?></span>
                                                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Avanza Directo</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-2 bg-gradient-to-r from-orange-50 to-orange-100/50 text-orange-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-orange-200/60 font-sans">
                                                                            ⚡ Pase Automático
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            <!-- Equipos Tardíos -->
                                                            <?php $__currentLoopData = $lateByes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $byeTeamId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                ?>
                                                                <?php if($byeTeam): ?>
                                                                    <div class="nba-card w-60 border-l-4 border-l-blue-500 bg-blue-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                        <div class="absolute right-2 top-2 z-10">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                                                                                Tardío
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex items-center gap-3 py-1">
                                                                            <img src="<?php echo e(asset('storage/' . ($byeTeam->image_path ?? ''))); ?>" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                            <div class="flex flex-col min-w-0">
                                                                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate"><?php echo e($byeTeam->name); ?></span>
                                                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Equipo Registrado</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-2 bg-gradient-to-r from-blue-50 to-blue-100/50 text-blue-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-blue-200/60 font-sans">
                                                                            ⚡ Registro Tardío
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>

                                        <!-- ================================================================= -->
                                        <!-- 2. SECCIÓN CENTRAL: GRAN FINAL & DEFINICIÓN                        -->
                                        <!-- ================================================================= -->
                                        <div class="flex flex-col items-center gap-4 px-4 shrink-0 min-w-[300px]">
                                            <div class="nba-header">GRAN FINAL</div>

                                            <?php
                                                $doubleElimChampion = null;
                                                $doubleElimChampionLogo = null;
                                                
                                                $gf = $data['bracket']['grand_final'] ?? null;
                                                $gr = $data['bracket']['reset_game'] ?? null;
                                                
                                                if ($gr && $gr->status === 'finished') {
                                                    if ($gr->local_team_score > $gr->away_team_score) {
                                                        $doubleElimChampion = $gr->localTeam->name ?? null;
                                                        $doubleElimChampionLogo = $gr->localTeam->image_path ?? null;
                                                    } elseif ($gr->local_team_score < $gr->away_team_score) {
                                                        $doubleElimChampion = $gr->awayTeam->name ?? null;
                                                        $doubleElimChampionLogo = $gr->awayTeam->image_path ?? null;
                                                    }
                                                } elseif ($gf && $gf->status === 'finished') {
                                                    if ($gf->local_team_score > $gf->away_team_score) {
                                                        $doubleElimChampion = $gf->localTeam->name ?? null;
                                                        $doubleElimChampionLogo = $gf->localTeam->image_path ?? null;
                                                    }
                                                }
                                            ?>

                                            <?php if($doubleElimChampion): ?>
                                                <div class="w-72">
                                                    <div class="nba-champion-card border-2 border-yellow-500 shadow-2xl bg-white relative overflow-hidden">
                                                        <?php if($doubleElimChampionLogo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $doubleElimChampionLogo)); ?>" class="champion-bg-logo" alt="logo campeon" onerror="this.style.display='none'">
                                                        <?php endif; ?>
                                                        <div class="champion-content">
                                                            <div class="champion-label">Campeón</div>
                                                            <div class="champion-name">
                                                                <?php echo e($doubleElimChampion); ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-72">
                                                    <div class="flex items-center justify-center border border-dashed border-gray-300 rounded-lg min-h-[80px] w-full bg-white/20">
                                                        <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">Campeón Pendiente</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="flex-1 flex flex-col justify-center items-center gap-6 w-full">
                                                <!-- 1. PARTIDO DE GRAN FINAL (GF) -->
                                                <?php if(isset($data['bracket']['grand_final'])): ?>
                                                    <?php 
                                                        $gf = $data['bracket']['grand_final']; 
                                                        $wfLocal = $gf->local_team_score > $gf->away_team_score; 
                                                    ?>
                                                    
                                                    <div class="w-72 relative z-10">
                                                        <div class="nba-card border-2 border-yellow-500 shadow-2xl bg-white relative overflow-hidden">
                                                            <?php if($gf->status === 'finished'): ?>
                                                                <div class="absolute inset-0 bg-gradient-to-t from-yellow-100/50 to-transparent opacity-60 z-0 pointer-events-none"></div>
                                                            <?php endif; ?>

                                                            <!-- LOCAL -->
                                                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                'nba-team-row relative z-10',
                                                                'nba-winner' => $wfLocal,
                                                                'nba-loser' => !$wfLocal && $gf->status === 'finished'
                                                            ]); ?>">
                                                                <img src="<?php echo e(asset('storage/' . ($gf->localTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                <span class="nba-team-name"><?php echo e($gf->localTeam->name ?? 'Campeón Winner'); ?></span>
                                                                <span class="nba-team-score <?php echo e(is_numeric($gf->local_team_score ?? ($gf->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($gf->local_team_score ?? ($gf->localTeam ? '0' : '-')); ?></span>
                                                            </div>

                                                            <!-- VISITANTE -->
                                                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                'nba-team-row relative z-10',
                                                                'nba-winner' => !$wfLocal && $gf->status === 'finished',
                                                                'nba-loser' => $wfLocal
                                                            ]); ?>">
                                                                <img src="<?php echo e(asset('storage/' . ($gf->awayTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                <span class="nba-team-name"><?php echo e($gf->awayTeam->name ?? 'Campeón Loser'); ?></span>
                                                                <span class="nba-team-score <?php echo e(is_numeric($gf->away_team_score ?? ($gf->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($gf->away_team_score ?? ($gf->awayTeam ? '0' : '-')); ?></span>
                                                            </div>
                                                            <!-- Estado del Juego Centrado -->
                                                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                <?php if(($gf->status ?? 'pending') === 'pending'): ?>
                                                                    <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                <?php elseif(($gf->status ?? 'pending') === 'playing'): ?>
                                                                    <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                <?php elseif(($gf->status ?? 'pending') === 'finished'): ?>
                                                                    <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- 2. PARTIDO DE REVANCHA (GR) -->
                                                <?php if(isset($data['bracket']['reset_game'])): ?>
                                                    <?php 
                                                        $g = $data['bracket']['reset_game']; 
                                                        $wLocal = $g->local_team_score > $g->away_team_score; 
                                                    ?>
                                                    
                                                    <div class="w-72 mt-2">
                                                        <div class="nba-card border-2 border-yellow-400 shadow-lg bg-white relative overflow-hidden">
                                                            <!-- LOCAL -->
                                                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                'nba-team-row',
                                                                'nba-winner' => $wLocal,
                                                                'nba-loser' => !$wLocal && $g->status === 'finished'
                                                            ]); ?>">
                                                                <img src="<?php echo e(asset('storage/' . ($g->localTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                <span class="nba-team-name"><?php echo e($g->localTeam->name ?? 'Por definir'); ?></span>
                                                                <span class="nba-team-score <?php echo e(is_numeric($g->local_team_score ?? ($g->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($g->local_team_score ?? ($g->localTeam ? '0' : '-')); ?></span>
                                                            </div>
                                                            
                                                            <!-- VISITANTE -->
                                                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                'nba-team-row',
                                                                'nba-winner' => !$wLocal && $g->status === 'finished',
                                                                'nba-loser' => $wLocal
                                                            ]); ?>">
                                                                <img src="<?php echo e(asset('storage/' . ($g->awayTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                <span class="nba-team-name"><?php echo e($g->awayTeam->name ?? 'Por definir'); ?></span>
                                                                <span class="nba-team-score <?php echo e(is_numeric($g->away_team_score ?? ($g->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($g->away_team_score ?? ($g->awayTeam ? '0' : '-')); ?></span>
                                                            </div>
                                                            <!-- Estado del Juego Centrado -->
                                                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                <?php if(($g->status ?? 'pending') === 'pending'): ?>
                                                                    <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                <?php elseif(($g->status ?? 'pending') === 'playing'): ?>
                                                                    <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                <?php elseif(($g->status ?? 'pending') === 'finished'): ?>
                                                                    <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if(!isset($data['bracket']['grand_final']) && !isset($data['bracket']['reset_game'])): ?>
                                                    <div class="w-full text-center py-8 px-4 border-2 border-dashed border-gray-300 rounded-xl bg-white shadow-inner">
                                                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Esperando Finalistas</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- ================================================================= -->
                                        <!-- 3. BRACKET DE PERDEDORES (DERECHA A IZQUIERDA: flex-row-reverse)  -->
                                        <!-- ================================================================= -->
                                        <div class="flex flex-col gap-4 border-l border-dashed border-red-200 pl-6 shrink-0">
                                            <div class="nba-header">BRACKET DE PERDEDORES</div>

                                            <div class="flex flex-row-reverse items-center gap-2 md:gap-4 h-full">
                                                <?php $__currentLoopData = $data['bracket']['loser_bracket']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roundIndex => $games): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex flex-col justify-around gap-6 h-full min-w-[240px]">
                                                        <!-- Encabezado de Ronda -->
                                                        <div class="text-center">
                                                            <?php
                                                                $totalLoserRounds = count($data['bracket']['loser_bracket']);
                                                                $roundNum = $roundIndex + 1;
                                                                if ($roundNum == $totalLoserRounds) {
                                                                    $label = 'Final';
                                                                } elseif ($roundNum == $totalLoserRounds - 1) {
                                                                    $label = 'Semifinal';
                                                                } else {
                                                                    $label = 'Ronda ' . $roundNum;
                                                                }
                                                            ?>
                                                            <div class="nba-header"><?php echo e($label); ?></div>
                                                        </div>

                                                        <!-- Partidos de la Ronda -->
                                                        <div class="flex flex-col justify-around gap-6 flex-1">
                                                            <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="nba-card w-60 shadow-md hover:shadow-xl transition-all duration-200 border-r-4 border-r-red-500 relative">
                                                                    <!-- Local -->
                                                                    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->local_team_score > $game->away_team_score,
                                                                        'nba-loser' => $game->away_team_score > $game->local_team_score
                                                                    ]); ?>">
                                                                        <img src="<?php echo e(asset('storage/' . ($game->localTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name"><?php echo e($game->localTeam->name ?? 'Por definir'); ?></span>
                                                                        <span class="nba-team-score <?php echo e(is_numeric($game->local_team_score ?? ($game->localTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($game->local_team_score ?? ($game->localTeam ? '0' : '-')); ?></span>
                                                                    </div>
                                                                    <!-- Visitante -->
                                                                    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                        'nba-team-row',
                                                                        'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                        'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                    ]); ?>">
                                                                        <img src="<?php echo e(asset('storage/' . ($game->awayTeam->image_path ?? ''))); ?>" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                        <span class="nba-team-name"><?php echo e($game->awayTeam->name ?? 'Por definir'); ?></span>
                                                                        <span class="nba-team-score <?php echo e(is_numeric($game->away_team_score ?? ($game->awayTeam ? '0' : null)) ? 'nba-score-numeric' : 'nba-score-empty'); ?>"><?php echo e($game->away_team_score ?? ($game->awayTeam ? '0' : '-')); ?></span>
                                                                    </div>
                                                                    <!-- Estado del Juego Centrado -->
                                                                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none">
                                                                        <?php if(($game->status ?? 'pending') === 'pending'): ?>
                                                                            <span class="nba-status-badge nba-status-pending">Pendiente</span>
                                                                        <?php elseif(($game->status ?? 'pending') === 'playing'): ?>
                                                                            <span class="nba-status-badge nba-status-playing">En Juego</span>
                                                                        <?php elseif(($game->status ?? 'pending') === 'finished'): ?>
                                                                            <span class="nba-status-badge nba-status-finished">Finalizado</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            <?php
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
                                                            ?>
                                                            
                                                            <!-- BYEs Tradicionales -->
                                                            <?php $__currentLoopData = $normalByes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $byeTeamId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                ?>
                                                                <?php if($byeTeam): ?>
                                                                    <div class="nba-card w-60 border-r-4 border-r-orange-500 bg-orange-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                        <div class="absolute left-2 top-2 z-10">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-orange-100 text-orange-800 border border-orange-200">
                                                                                BYE
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex items-center gap-3 py-1 justify-end text-right">
                                                                            <div class="flex flex-col min-w-0 font-sans">
                                                                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate"><?php echo e($byeTeam->name); ?></span>
                                                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Avanza Directo</span>
                                                                            </div>
                                                                            <img src="<?php echo e(asset('storage/' . ($byeTeam->image_path ?? ''))); ?>" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                        </div>
                                                                        <div class="mt-2 bg-gradient-to-r from-orange-50 to-orange-100/50 text-orange-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-orange-200/60 font-sans">
                                                                            ⚡ Pase Automático
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            <!-- Equipos Tardíos -->
                                                            <?php $__currentLoopData = $lateByes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $byeTeamId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $byeTeam = $allGroupTeams->firstWhere('id', $byeTeamId);
                                                                ?>
                                                                <?php if($byeTeam): ?>
                                                                    <div class="nba-card w-60 border-r-4 border-r-blue-500 bg-blue-50/10 shadow-md hover:shadow-xl transition-all duration-200 relative overflow-hidden flex flex-col justify-between py-2 px-3 min-h-[114px]">
                                                                        <div class="absolute left-2 top-2 z-10">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                                                                                Tardío
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex items-center gap-3 py-1 justify-end text-right">
                                                                            <div class="flex flex-col min-w-0 font-sans">
                                                                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide truncate"><?php echo e($byeTeam->name); ?></span>
                                                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Equipo Registrado</span>
                                                                            </div>
                                                                            <img src="<?php echo e(asset('storage/' . ($byeTeam->image_path ?? ''))); ?>" class="w-8 h-8 rounded-full border border-gray-200 object-cover shadow-sm bg-white" alt="logo team" onerror="this.style.display='none'">
                                                                        </div>
                                                                        <div class="mt-2 bg-gradient-to-r from-blue-50 to-blue-100/50 text-blue-700 py-1 px-2 rounded-lg text-center font-extrabold text-[9px] uppercase tracking-wider border border-blue-200/60 font-sans">
                                                                            ⚡ Registro Tardío
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        <?php else: ?>
                            <!-- ================================================================= -->
                            <!-- CASO LIGA / ELIMINACIÓN SENCILLA                                 -->
                            <!-- ================================================================= -->
                            <?php
                                $equiposHeader = 0;
                                if (isset($data['team_ids']) && is_countable($data['team_ids'])) {
                                    $equiposHeader = count($data['team_ids']);
                                } elseif (isset($data['teams']) && is_countable($data['teams'])) {
                                    $equiposHeader = count($data['teams']);
                                } elseif (isset($data['standings']) && is_countable($data['standings'])) {
                                    $equiposHeader = count($data['standings']);
                                }
                            ?>

                            <!-- Encabezado del Grupo -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 border-b pb-3 border-gray-200 gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <span class="text-gray-400 text-base font-normal mr-2">Grupo:</span><?php echo e($groupName); ?>

                                    </h3>
                                    <span class="text-sm text-gray-500">
                                        <?php echo e($equiposHeader); ?> Equipos
                                    </span>
                                </div>
                            </div>

                            <!-- Brackets de Playoffs (Si aplica) -->
                            <?php if(isset($data['has_playoffs']) && $data['has_playoffs']): ?>
                                <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner mb-6">
                                    <div class="mb-6 border-b border-gray-200 pb-3 flex justify-center items-center">
                                        <h4 class="text-base font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                            <i class="fa-solid fa-trophy text-yellow-500"></i> FASE ELIMINATORIA
                                        </h4>
                                    </div>

                                    <div class="flex flex-row gap-6 overflow-x-auto pb-6 nba-scroll snap-x items-center">
                                        <?php $__currentLoopData = $data['playoff_rounds'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $round): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex-shrink-0 w-full md:w-72 snap-center flex flex-col gap-4">
                                                <div class="nba-header rounded">
                                                    <?php echo e($round['name'] ?? 'Ronda'); ?>

                                                </div>

                                                <?php $__currentLoopData = $round['games'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="nba-card">
                                                        <!-- Local -->
                                                        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                            'nba-team-row',
                                                            'nba-winner' => isset($game->local_team_score) && isset($game->away_team_score) && $game->local_team_score > $game->away_team_score,
                                                            'nba-loser' => isset($game->local_team_score) && isset($game->away_team_score) && $game->away_team_score > $game->local_team_score
                                                        ]); ?>">
                                                            <?php if(isset($game->localTeam) && $game->localTeam->image_path): ?>
                                                                <img src="<?php echo e(asset('storage/' . $game->localTeam->image_path)); ?>" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                            <?php endif; ?>
                                                            <span class="nba-team-name"><?php echo e($game->localTeam->name ?? 'Pendiente'); ?></span>
                                                            <span class="nba-team-score"><?php echo e($game->local_team_score ?? '-'); ?></span>
                                                        </div>

                                                        <!-- Visitante -->
                                                        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                            'nba-team-row',
                                                            'nba-winner' => isset($game->local_team_score) && isset($game->away_team_score) && $game->away_team_score > $game->local_team_score,
                                                            'nba-loser' => isset($game->local_team_score) && isset($game->away_team_score) && $game->local_team_score > $game->away_team_score
                                                        ]); ?>">
                                                            <?php if(isset($game->awayTeam) && $game->awayTeam->image_path): ?>
                                                                <img src="<?php echo e(asset('storage/' . $game->awayTeam->image_path)); ?>" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                            <?php endif; ?>
                                                            <span class="nba-team-name"><?php echo e($game->awayTeam->name ?? 'Pendiente'); ?></span>
                                                            <span class="nba-team-score"><?php echo e($game->away_team_score ?? '-'); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <!-- Campeón Playoffs -->
                                        <?php if(isset($data['playoff_champion'])): ?>
                                            <div class="flex-shrink-0 w-full md:w-64 snap-center">
                                                <div class="nba-champion-card">
                                                    <div class="champion-content">
                                                        <div class="champion-label">🏆 Campeón</div>
                                                        <div class="champion-name text-center">
                                                            <?php echo e($data['playoff_champion']); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Tabla General (Round Robin) -->
                            <?php if(isset($data['standings']) && count($data['standings']) > 0): ?>
                                <div class="overflow-x-auto mt-4 rounded-xl border border-gray-100">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50/70">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">#</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Equipo</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PJ</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">G</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">E</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">P</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase bg-emerald-50">PTS</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            <?php $pos = 1; ?>
                                            <?php $__currentLoopData = $data['standings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tid => $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="hover:bg-gray-50/50 transition">
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm font-medium text-gray-500"><?php echo e($pos++); ?></td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm font-bold text-gray-900">
                                                        <div class="flex items-center">
                                                            <?php if(isset($data['teams'][$tid])): ?>
                                                                <?php if($data['teams'][$tid]->image_path): ?>
                                                                    <img src="<?php echo e(asset('storage/' . $data['teams'][$tid]->image_path)); ?>" alt="<?php echo e($data['teams'][$tid]->name); ?>" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-100" onerror="this.style.display='none'">
                                                                <?php else: ?>
                                                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold mr-3 border border-gray-100">
                                                                        <?php echo e(substr($data['teams'][$tid]->name, 0, 1)); ?>

                                                                    </div>
                                                                <?php endif; ?>
                                                                <span><?php echo e($data['teams'][$tid]->name); ?></span>
                                                            <?php else: ?>
                                                                <span class="text-gray-400 italic">Equipo Eliminado</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-gray-500"><?php echo e($stats['played'] ?? 0); ?></td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-green-600 font-bold"><?php echo e($stats['won'] ?? 0); ?></td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-yellow-600 font-bold"><?php echo e($stats['drawn'] ?? 0); ?></td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-red-600 font-bold"><?php echo e($stats['lost'] ?? 0); ?></td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm font-bold bg-emerald-50 text-emerald-800"><?php echo e($stats['points'] ?? 0); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>

                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <?php if($tournaments->isEmpty()): ?>
                <!-- Sin Torneos Activos banner -->
                <div class="text-center py-20 bg-white/80 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm">
                    <i class="fa-solid fa-circle-info text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-700">No hay torneos activos</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-md mx-auto">Vuelve más tarde cuando comience una nueva competencia de baloncesto en CrossoverMX.</p>
                </div>
            <?php else: ?>
                <!-- MODO PANEL GENERAL (DASHBOARD POR TORNEO) -->
                <div class="space-y-12">
                    <?php $__currentLoopData = $dashboardData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-white/50 p-6 md:p-8">
                            <div class="border-b border-gray-100 pb-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase"><?php echo e($tData['tournament_name']); ?></h2>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-wider">
                                        <?php echo e($tData['tournament_type'] === 'round_robin' ? 'Todos contra todos' : 'Eliminatoria'); ?>

                                    </span>
                                    <?php if($tData['tournament_status'] === 'finished'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200 uppercase tracking-wider">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span>
                                            Terminado
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wider">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                            Activo
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Columna 1: Top 3 Jugadores con más puntos -->
                                <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100/50 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-base font-extrabold text-gray-800 mb-4 flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-orange-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-2.133-1A3.75 3.75 0 0 0 12 18Z" />
                                            </svg>
                                            Líderes Anotadores
                                        </h3>
                                        
                                        <?php if($tData['top_scorers']->isEmpty()): ?>
                                            <div class="text-center py-8 text-sm text-gray-400 font-medium">Sin datos de anotación registrados</div>
                                        <?php else: ?>
                                            <div class="space-y-3">
                                                <?php $rank = 1; ?>
                                                <?php $__currentLoopData = $tData['top_scorers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scorer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="relative overflow-hidden flex items-center justify-between bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                                                        <?php if($scorer['team_logo']): ?>
                                                            <img src="<?php echo e(asset('storage/' . $scorer['team_logo'])); ?>" class="absolute right-[-25px] top-1/2 -translate-y-1/2 h-36 w-auto opacity-15 pointer-events-none z-0 object-contain" onerror="this.style.display='none'">
                                                        <?php endif; ?>
                                                        
                                                        <div class="flex items-center gap-3 relative z-10">
                                                            <span class="text-xs font-extrabold text-gray-400 bg-gray-50 h-6 w-6 rounded-full flex items-center justify-center"><?php echo e($rank++); ?></span>
                                                            
                                                            <?php if($scorer['player_logo']): ?>
                                                                <img src="<?php echo e(asset('storage/' . $scorer['player_logo'])); ?>" class="h-8 w-8 rounded-full object-cover border border-gray-100" onerror="this.style.display='none'">
                                                            <?php elseif($scorer['player_gender'] === 'hombre'): ?>
                                                                <img src="<?php echo e(asset('images/hombre.png')); ?>" class="h-8 w-8 rounded-full object-cover border border-gray-100">
                                                            <?php elseif($scorer['player_gender'] === 'mujer'): ?>
                                                                <img src="<?php echo e(asset('images/mujer.png')); ?>" class="h-8 w-8 rounded-full object-cover border border-gray-100">
                                                            <?php else: ?>
                                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500 border border-gray-100">
                                                                    <?php echo e(substr($scorer['player_name'], 0, 1)); ?>

                                                                </div>
                                                            <?php endif; ?>

                                                            <div>
                                                                <div class="text-sm font-bold text-gray-800 truncate w-24 sm:w-32"><?php echo e($scorer['player_name']); ?></div>
                                                                <div class="text-[10px] text-gray-400 font-bold uppercase truncate w-24 sm:w-32"><?php echo e($scorer['team_name']); ?></div>
                                                            </div>
                                                        </div>
                                                        <span class="relative z-10 bg-white/40 backdrop-blur-md border border-white/40 px-3 py-1 rounded-full shadow-sm text-xs font-black text-gray-800 shrink-0">
                                                            <?php echo e($scorer['points']); ?> pts
                                                        </span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Columna 2: Top 3 Equipos -->
                                <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100/50 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-base font-extrabold text-gray-800 mb-4 flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                            </svg>
                                            Mejores Equipos
                                        </h3>
                                        
                                        <?php if(empty($tData['top_teams'])): ?>
                                            <div class="text-center py-8 text-sm text-gray-400 font-medium">Sin estadísticas de juego registradas</div>
                                        <?php else: ?>
                                            <div class="space-y-3">
                                                <?php $teamRank = 1; ?>
                                                <?php $__currentLoopData = $tData['top_teams']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="relative overflow-hidden flex items-center justify-between bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                                                        <!-- Marca de agua de fondo -->
                                                        <?php if($team['team_logo']): ?>
                                                            <img src="<?php echo e(asset('storage/' . $team['team_logo'])); ?>" class="absolute right-[-25px] top-1/2 -translate-y-1/2 h-36 w-auto opacity-15 pointer-events-none z-0 object-contain" onerror="this.style.display='none'">
                                                        <?php endif; ?>
                                                        
                                                        <div class="flex items-center gap-3 relative z-10">
                                                            <span class="text-xs font-extrabold text-gray-400 bg-gray-50 h-6 w-6 rounded-full flex items-center justify-center"><?php echo e($teamRank++); ?></span>
                                                            <span class="text-sm font-bold text-gray-800 truncate w-32 sm:w-40"><?php echo e($team['team_name']); ?></span>
                                                        </div>
                                                        
                                                        <span class="relative z-10 bg-white/40 backdrop-blur-md border border-white/40 px-3 py-1 rounded-full shadow-sm text-xs font-black text-gray-800 shrink-0">
                                                            <?php echo e($team['score']); ?>

                                                        </span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Columna 3: Próximos Encuentros (Carrusel) -->
                                <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100/50 flex flex-col justify-between">
                                    <div>

                                        <h3 class="text-base font-extrabold text-gray-800 mb-4 flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-emerald-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                            </svg>
                                            Próximos Partidos
                                        </h3>

                                        <?php if(empty($tData['upcoming_games']) || count($tData['upcoming_games']) === 0): ?>
                                            <div class="text-center py-8 text-sm text-gray-400 font-medium">Sin partidos programados pendientes</div>
                                        <?php else: ?>
                                            <!-- Listado vertical con scroll si excede el tamaño -->
                                            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1 nba-scroll">
                                                <?php $__currentLoopData = $tData['upcoming_games']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="relative overflow-hidden bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between gap-3">
                                                        <!-- Marcas de agua de los equipos -->
                                                        <?php if($game['local_logo']): ?>
                                                            <img src="<?php echo e(asset('storage/' . $game['local_logo'])); ?>" class="absolute left-[-25px] top-1/2 -translate-y-1/2 h-32 w-auto opacity-10 pointer-events-none z-0 object-contain" onerror="this.style.display='none'">
                                                        <?php endif; ?>
                                                        <?php if($game['away_logo']): ?>
                                                            <img src="<?php echo e(asset('storage/' . $game['away_logo'])); ?>" class="absolute right-[-25px] top-1/2 -translate-y-1/2 h-32 w-auto opacity-10 pointer-events-none z-0 object-contain" onerror="this.style.display='none'">
                                                        <?php endif; ?>

                                                        <div class="relative z-10 flex items-center justify-between gap-1.5 px-3 py-1.5 rounded-full bg-white/40 backdrop-blur-md border border-white/40 text-[9px] font-extrabold text-gray-500 uppercase shadow-sm">
                                                             <span class="truncate max-w-[80px] flex items-center">
                                                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 mr-0.5 text-gray-500 inline shrink-0">
                                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1 1 15 0Z" />
                                                                 </svg>
                                                                 <?php echo e($game['court_name']); ?>

                                                             </span>
                                                             
                                                             <span class="truncate max-w-[110px] text-gray-500 text-[8px] font-bold">
                                                                 <?php echo e($game['category_strength']); ?>

                                                             </span>

                                                             <span class="flex items-center shrink-0">
                                                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 mr-0.5 text-gray-500 inline shrink-0">
                                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                 </svg>
                                                                 <?php echo e($game['date_time']); ?>

                                                             </span>
                                                         </div>
                                                        
                                                        <div class="flex items-center justify-between gap-2 py-1 relative z-10">
                                                            <!-- Local -->
                                                            <div class="flex items-center w-5/12">
                                                                <span class="bg-white/40 backdrop-blur-md border border-white/40 px-3 py-1 rounded-full shadow-sm text-xs font-bold text-gray-800 truncate w-24 sm:w-28 text-center block">
                                                                    <?php echo e($game['local_name']); ?>

                                                                </span>
                                                            </div>
                                                            
                                                            <span class="text-[10px] font-black text-orange-500 bg-orange-50 px-1.5 py-0.5 rounded border border-orange-100 shrink-0">VS</span>
                                                            
                                                            <!-- Visitante -->
                                                            <div class="flex items-center w-5/12 justify-end">
                                                                <span class="bg-white/40 backdrop-blur-md border border-white/40 px-3 py-1 rounded-full shadow-sm text-xs font-bold text-gray-800 truncate w-24 sm:w-28 text-center block">
                                                                    <?php echo e($game['away_name']); ?>

                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 text-center mt-2 uppercase tracking-wider">
                                                <i class="fa-solid fa-angles-down mr-1 animate-pulse"></i> Desliza hacia abajo <i class="fa-solid fa-angles-down ml-1 animate-pulse"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer>
        <div class="container mx-auto px-4">
            <div class="logo-container-footer">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="CrossoverMX Logo" class="logo-img-footer">
                <div class="logo-text-footer">Crossover<span>MX</span></div>
            </div>
            <p>&copy; <?php echo e(date('Y')); ?> CrossoverMX. Todos los derechos reservados.</p>

            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61586587724531" target="_blank" class="social-icon" title="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/crossover_mex" target="_blank" class="social-icon" title="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://wa.me/525511402976" target="_blank" class="social-icon" title="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="mailto:ventas@crossovermx.com" class="social-icon" title="Email">
                    <i class="fa-solid fa-envelope"></i>
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
<?php /**PATH C:\Users\luism\gemini-work\sistemaTorneos\resources\views/public/standings.blade.php ENDPATH**/ ?>