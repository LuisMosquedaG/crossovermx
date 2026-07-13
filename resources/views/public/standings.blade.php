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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        .nba-team-score {
            position: relative;
            z-index: 10;
            margin-left: auto;
            font-family: 'Arial', sans-serif;
            line-height: 1;
        }

        .nba-winner .nba-team-score {
            color: #059669;
            font-weight: 900;
            font-size: 1.8rem;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }

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

        .nba-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .nba-scroll::-webkit-scrollbar-track { background: #f3f4f6; }
        .nba-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .nba-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        .nba-team-name {
            position: relative;
            z-index: 10;
            flex-grow: 1;
            text-align: center;
            padding-left: 75px;
            padding-right: 30px;
            font-weight: 700;
            font-size: 0.85rem;
            color: #374151;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: 0.5px;
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
            <div class="logo-container" onclick="window.location.href='{{ route('home') }}'">
                <img src="{{ asset('images/logo.png') }}" alt="CrossoverMX Logo" class="logo-img">
                <div class="logo-text">Crossover<span>MX</span></div>
            </div>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}#summary">Impacto</a></li>
                <li><a href="{{ route('home') }}#features">Torneos</a></li>
                <li><a href="{{ route('home') }}#calendar-logic">Calendario</a></li>
                <li><a href="{{ route('public.standings') }}" style="color: var(--brand-orange); font-weight: bold;">Posiciones</a></li>
            </ul>
            
            <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
        </nav>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16">
        
        <!-- Titulo y Selector de Torneo -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-white/50 p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tabla de Posiciones</h1>
                    <p class="text-gray-500 mt-1">Selecciona uno de los torneos activos para consultar los resultados y estadísticas en tiempo real.</p>
                </div>
                
                <div class="w-full md:w-auto">
                    <form action="{{ route('public.standings') }}" method="GET" class="flex items-center gap-3">
                        <label for="tournament_id" class="text-sm font-semibold text-gray-600 shrink-0 hidden sm:inline">Torneo:</label>
                        <select name="tournament_id" id="tournament_id" onchange="this.form.submit()" class="block w-full md:w-80 rounded-full border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5 px-4 bg-gray-50 text-gray-800 font-medium">
                            @if($tournaments->isEmpty())
                                <option value="">Sin Torneos Activos</option>
                            @else
                                <option value="" {{ !$selectedTournamentId ? 'selected' : '' }}>Selecciona un torneo</option>
                                @foreach($tournaments as $t)
                                    <option value="{{ $t->id }}" {{ $selectedTournamentId == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resultados del Torneo Seleccionado -->
        @if($tournament)
            <div>
                <!-- Información del Torneo -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-wide">{{ $tournament->name }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 mt-1.5">
                            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                            Activo
                        </span>
                    </div>
                </div>

                <!-- LOOP PRINCIPAL DE GRUPOS / ETAPAS -->
                @foreach($standingsData as $groupName => $data)
                    @if($groupName === 'mode') @continue @endif
                    
                    <div class="mb-10 bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white/50 shadow-sm">
                        
                        <!-- ================================================================= -->
                        <!--        CASO DOBLE ELIMINATORIA                                    -->
                        <!-- ================================================================= -->
                        @if(isset($data['mode']) && $data['mode'] === 'double_elimination_grouped')
                            
                            @php
                                $totalEquipos = 0;
                                if (isset($data['bracket']['winner_bracket']) && isset($data['bracket']['winner_bracket'][0])) {
                                    $totalEquipos = count($data['bracket']['winner_bracket'][0]) * 2;
                                }
                            @endphp

                            <!-- Encabezado del Grupo -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 border-b pb-3 border-gray-200 gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <span class="text-gray-400 text-base font-normal mr-2">Grupo:</span>{{ $groupName }}
                                    </h3>
                                    <span class="text-sm text-gray-500">
                                        {{ $totalEquipos }} Equipos
                                    </span>
                                </div>
                            </div>

                            <!-- Contenedor del Bracket -->
                            <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner">
                                <div class="mb-6 border-b border-gray-200 pb-3 flex justify-center items-center">
                                    <h4 class="text-base font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                        <i class="fa-solid fa-trophy text-yellow-500"></i> FASE DOBLE ELIMINATORIA
                                    </h4>
                                </div>

                                <div class="overflow-y-auto max-h-[800px] nba-scroll pr-2">
                                    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start justify-center min-w-full">
                                        
                                        <!-- BRACKET GANADORES -->
                                        <div class="w-full lg:w-1/2 flex flex-col gap-8 border-r-0 lg:border-r-2 border-dashed border-gray-200 pr-0 lg:pr-8">
                                            <h4 class="text-center text-xs font-bold text-blue-900 uppercase tracking-widest bg-blue-50 py-2 rounded-full border border-blue-200 shadow-sm">
                                                Bracket Ganadores
                                            </h4>

                                            @foreach($data['bracket']['winner_bracket'] as $roundIndex => $games)
                                                <div class="w-full">
                                                    @if($loop->first)
                                                        <div class="flex justify-between items-center mb-2 px-2">
                                                            <span class="text-[10px] font-bold text-gray-400 uppercase">Ronda {{ $roundIndex + 1 }}</span>
                                                            <div class="h-px bg-gray-200 flex-1 ml-2"></div>
                                                        </div>
                                                    @else
                                                        <div class="flex justify-center mb-4">
                                                            <div class="h-6 border-l-4 border-blue-200"></div>
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
                                                                    @if($game->localTeam && $game->localTeam->image_path)
                                                                        <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                    @endif
                                                                    <span class="nba-team-name">{{ $game->localTeam->name ?? 'Pendiente' }}</span>
                                                                    <span class="nba-team-score">{{ $game->local_team_score ?? '-' }}</span>
                                                                </div>
                                                                <!-- Visitante -->
                                                                <div @class([
                                                                    'nba-team-row',
                                                                    'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                    'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                ])>
                                                                    @if($game->awayTeam && $game->awayTeam->image_path)
                                                                        <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                    @endif
                                                                    <span class="nba-team-name">{{ $game->awayTeam->name ?? 'Pendiente' }}</span>
                                                                    <span class="nba-team-score">{{ $game->away_team_score ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- BRACKET PERDEDORES -->
                                        <div class="w-full lg:w-1/2 flex flex-col gap-8 pl-0 lg:pl-8 mt-8 lg:mt-0">
                                            <h4 class="text-center text-xs font-bold text-red-900 uppercase tracking-widest bg-red-50 py-2 rounded-full border border-red-200 shadow-sm">
                                                Bracket Perdedores
                                            </h4>

                                            @foreach($data['bracket']['loser_bracket'] as $roundIndex => $games)
                                                <div class="w-full">
                                                    @if($loop->first)
                                                        <div class="flex justify-between items-center mb-2 px-2">
                                                            <span class="text-[10px] font-bold text-gray-400 uppercase">Ronda {{ $roundIndex + 1 }}</span>
                                                            <div class="h-px bg-gray-200 flex-1 ml-2"></div>
                                                        </div>
                                                    @else
                                                        <div class="flex justify-center mb-4">
                                                            <div class="h-6 border-l-4 border-red-200"></div>
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
                                                                    @if($game->localTeam && $game->localTeam->image_path)
                                                                        <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                                    @endif
                                                                    <span class="nba-team-name">{{ $game->localTeam->name ?? 'Pendiente' }}</span>
                                                                    <span class="nba-team-score">{{ $game->local_team_score ?? '-' }}</span>
                                                                </div>
                                                                <!-- Visitante -->
                                                                <div @class([
                                                                    'nba-team-row',
                                                                    'nba-winner' => $game->away_team_score > $game->local_team_score,
                                                                    'nba-loser' => $game->local_team_score > $game->away_team_score
                                                                ])>
                                                                    @if($game->awayTeam && $game->awayTeam->image_path)
                                                                        <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                                    @endif
                                                                    <span class="nba-team-name">{{ $game->awayTeam->name ?? 'Pendiente' }}</span>
                                                                    <span class="nba-team-score">{{ $game->away_team_score ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- GRAN FINAL -->
                                    <div class="mt-12 pt-8 border-t border-gray-200 w-full max-w-3xl mx-auto">
                                        <div class="flex flex-col items-center justify-center gap-10">
                                            @if(isset($data['bracket']['grand_final']))
                                                @php $gf = $data['bracket']['grand_final']; $wfLocal = $gf->local_team_score > $gf->away_team_score; @endphp
                                                
                                                <div class="w-full max-w-md relative z-10">
                                                    <div class="text-center mb-4 relative">
                                                        <div class="absolute inset-0 bg-gray-800 opacity-5 rounded-lg"></div>
                                                        <span class="relative inline-block px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest bg-gray-900 text-white shadow-md border-b-2 border-yellow-500">
                                                            🏆 Gran Final
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="nba-card border border-gray-300 shadow-lg bg-white relative overflow-hidden">
                                                        <!-- LOCAL -->
                                                        <div @class([
                                                            'nba-team-row relative z-10',
                                                            'nba-winner' => $wfLocal,
                                                            'nba-loser' => !$wfLocal
                                                        ])>
                                                            @if($gf->localTeam && $gf->localTeam->image_path)
                                                                <img src="{{ asset('storage/' . $gf->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                            @endif
                                                            <span class="nba-team-name">{{ $gf->localTeam->name }}</span>
                                                            <span class="nba-team-score">{{ $gf->local_team_score ?? '-' }}</span>
                                                        </div>

                                                        <!-- VISITANTE -->
                                                        <div @class([
                                                            'nba-team-row relative z-10',
                                                            'nba-winner' => !$wfLocal,
                                                            'nba-loser' => $wfLocal
                                                        ])>
                                                            @if($gf->awayTeam && $gf->awayTeam->image_path)
                                                                <img src="{{ asset('storage/' . $gf->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                            @endif
                                                            <span class="nba-team-name">{{ $gf->awayTeam->name }}</span>
                                                            <span class="nba-team-score">{{ $gf->away_team_score ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(isset($data['bracket']['reset_game']))
                                                @php $g = $data['bracket']['reset_game']; $wLocal = $g->local_team_score > $g->away_team_score; @endphp
                                                
                                                <div class="w-full max-w-md">
                                                    <div class="text-center mb-3">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-yellow-50 text-yellow-800 border border-yellow-200 shadow-sm">
                                                            Si es necesario (Desempate)
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="nba-card border border-yellow-300 shadow-md">
                                                        <!-- LOCAL -->
                                                        <div @class([
                                                            'nba-team-row',
                                                            'nba-winner' => $wLocal,
                                                            'nba-loser' => !$wLocal
                                                        ])>
                                                            @if($g->localTeam && $g->localTeam->image_path)
                                                                <img src="{{ asset('storage/' . $g->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                            @endif
                                                            <span class="nba-team-name">{{ $g->localTeam->name }}</span>
                                                            <span class="nba-team-score">{{ $g->local_team_score ?? '-' }}</span>
                                                        </div>
                                                        
                                                        <!-- VISITANTE -->
                                                        <div @class([
                                                            'nba-team-row',
                                                            'nba-winner' => !$wLocal,
                                                            'nba-loser' => $wLocal
                                                        ])>
                                                            @if($g->awayTeam && $g->awayTeam->image_path)
                                                                <img src="{{ asset('storage/' . $g->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                            @endif
                                                            <span class="nba-team-name">{{ $g->awayTeam->name }}</span>
                                                            <span class="nba-team-score">{{ $g->away_team_score ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- ================================================================= -->
                            <!-- CASO LIGA / ELIMINACIÓN SENCILLA                                 -->
                            <!-- ================================================================= -->
                            @php
                                $equiposHeader = 0;
                                if (isset($data['team_ids']) && is_countable($data['team_ids'])) {
                                    $equiposHeader = count($data['team_ids']);
                                } elseif (isset($data['teams']) && is_countable($data['teams'])) {
                                    $equiposHeader = count($data['teams']);
                                } elseif (isset($data['standings']) && is_countable($data['standings'])) {
                                    $equiposHeader = count($data['standings']);
                                }
                            @endphp

                            <!-- Encabezado del Grupo -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 border-b pb-3 border-gray-200 gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <span class="text-gray-400 text-base font-normal mr-2">Grupo:</span>{{ $groupName }}
                                    </h3>
                                    <span class="text-sm text-gray-500">
                                        {{ $equiposHeader }} Equipos
                                    </span>
                                </div>
                            </div>

                            <!-- Brackets de Playoffs (Si aplica) -->
                            @if(isset($data['has_playoffs']) && $data['has_playoffs'])
                                <div class="mt-6 nba-bg p-4 md:p-6 shadow-inner mb-6">
                                    <div class="mb-6 border-b border-gray-200 pb-3 flex justify-center items-center">
                                        <h4 class="text-base font-bold text-gray-900 tracking-wider uppercase flex items-center gap-2">
                                            <i class="fa-solid fa-trophy text-yellow-500"></i> FASE ELIMINATORIA
                                        </h4>
                                    </div>

                                    <div class="flex flex-row gap-6 overflow-x-auto pb-6 nba-scroll snap-x items-center">
                                        @foreach($data['playoff_rounds'] ?? [] as $round)
                                            <div class="flex-shrink-0 w-full md:w-72 snap-center flex flex-col gap-4">
                                                <div class="nba-header rounded">
                                                    {{ $round['name'] ?? 'Ronda' }}
                                                </div>

                                                @foreach($round['games'] ?? [] as $game)
                                                    <div class="nba-card">
                                                        <!-- Local -->
                                                        <div @class([
                                                            'nba-team-row',
                                                            'nba-winner' => isset($game->local_team_score) && isset($game->away_team_score) && $game->local_team_score > $game->away_team_score,
                                                            'nba-loser' => isset($game->local_team_score) && isset($game->away_team_score) && $game->away_team_score > $game->local_team_score
                                                        ])>
                                                            @if(isset($game->localTeam) && $game->localTeam->image_path)
                                                                <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" class="nba-team-logo" alt="logo local" onerror="this.style.display='none'">
                                                            @endif
                                                            <span class="nba-team-name">{{ $game->localTeam->name ?? 'Pendiente' }}</span>
                                                            <span class="nba-team-score">{{ $game->local_team_score ?? '-' }}</span>
                                                        </div>

                                                        <!-- Visitante -->
                                                        <div @class([
                                                            'nba-team-row',
                                                            'nba-winner' => isset($game->local_team_score) && isset($game->away_team_score) && $game->away_team_score > $game->local_team_score,
                                                            'nba-loser' => isset($game->local_team_score) && isset($game->away_team_score) && $game->local_team_score > $game->away_team_score
                                                        ])>
                                                            @if(isset($game->awayTeam) && $game->awayTeam->image_path)
                                                                <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" class="nba-team-logo" alt="logo visitante" onerror="this.style.display='none'">
                                                            @endif
                                                            <span class="nba-team-name">{{ $game->awayTeam->name ?? 'Pendiente' }}</span>
                                                            <span class="nba-team-score">{{ $game->away_team_score ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach

                                        <!-- Campeón Playoffs -->
                                        @if(isset($data['playoff_champion']))
                                            <div class="flex-shrink-0 w-full md:w-64 snap-center">
                                                <div class="nba-champion-card">
                                                    <div class="champion-content">
                                                        <div class="champion-label">🏆 Campeón</div>
                                                        <div class="champion-name text-center">
                                                            {{ $data['playoff_champion'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Tabla General (Round Robin) -->
                            @if(isset($data['standings']) && count($data['standings']) > 0)
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
                                            @php $pos = 1; @endphp
                                            @foreach($data['standings'] as $tid => $stats)
                                                <tr class="hover:bg-gray-50/50 transition">
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm font-medium text-gray-500">{{ $pos++ }}</td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm font-bold text-gray-900">
                                                        <div class="flex items-center">
                                                            @if(isset($data['teams'][$tid]))
                                                                @if($data['teams'][$tid]->image_path)
                                                                    <img src="{{ asset('storage/' . $data['teams'][$tid]->image_path) }}" alt="{{ $data['teams'][$tid]->name }}" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-100" onerror="this.style.display='none'">
                                                                @else
                                                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold mr-3 border border-gray-100">
                                                                        {{ substr($data['teams'][$tid]->name, 0, 1) }}
                                                                    </div>
                                                                @endif
                                                                <span>{{ $data['teams'][$tid]->name }}</span>
                                                            @else
                                                                <span class="text-gray-400 italic">Equipo Eliminado</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-gray-500">{{ $stats['played'] ?? 0 }}</td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-green-600 font-bold">{{ $stats['won'] ?? 0 }}</td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-yellow-600 font-bold">{{ $stats['drawn'] ?? 0 }}</td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm text-red-600 font-bold">{{ $stats['lost'] ?? 0 }}</td>
                                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm font-bold bg-emerald-50 text-emerald-800">{{ $stats['points'] ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        @endif
                    </div>
                @endforeach
            </div>
        @else
            @if($tournaments->isEmpty())
                <!-- Sin Torneos Activos banner -->
                <div class="text-center py-20 bg-white/80 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm">
                    <i class="fa-solid fa-circle-info text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-700">No hay torneos activos</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-md mx-auto">Vuelve más tarde cuando comience una nueva competencia de baloncesto en CrossoverMX.</p>
                </div>
            @else
                <!-- MODO PANEL GENERAL (DASHBOARD POR TORNEO) -->
                <div class="space-y-12">
                    @foreach($dashboardData as $tData)
                        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-white/50 p-6 md:p-8">
                            <div class="border-b border-gray-100 pb-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">{{ $tData['tournament_name'] }}</h2>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-wider">
                                    {{ $tData['tournament_type'] === 'round_robin' ? 'Todos contra todos' : 'Eliminatoria' }}
                                </span>
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
                                        
                                        @if($tData['top_scorers']->isEmpty())
                                            <div class="text-center py-8 text-sm text-gray-400 font-medium">Sin datos de anotación registrados</div>
                                        @else
                                            <div class="space-y-3">
                                                @php $rank = 1; @endphp
                                                @foreach($tData['top_scorers'] as $scorer)
                                                    <div class="flex items-center justify-between bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-xs font-extrabold text-gray-400 bg-gray-50 h-6 w-6 rounded-full flex items-center justify-center">{{ $rank++ }}</span>
                                                            
                                                            @if($scorer['player_logo'])
                                                                <img src="{{ asset('storage/' . $scorer['player_logo']) }}" class="h-8 w-8 rounded-full object-cover border border-gray-100" onerror="this.style.display='none'">
                                                            @elseif($scorer['player_gender'] === 'hombre')
                                                                <img src="{{ asset('images/hombre.png') }}" class="h-8 w-8 rounded-full object-cover border border-gray-100">
                                                            @elseif($scorer['player_gender'] === 'mujer')
                                                                <img src="{{ asset('images/mujer.png') }}" class="h-8 w-8 rounded-full object-cover border border-gray-100">
                                                            @else
                                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500 border border-gray-100">
                                                                    {{ substr($scorer['player_name'], 0, 1) }}
                                                                </div>
                                                            @endif

                                                            <div>
                                                                <div class="text-sm font-bold text-gray-800 truncate w-24 sm:w-32">{{ $scorer['player_name'] }}</div>
                                                                <div class="text-[10px] text-gray-400 font-bold uppercase truncate w-24 sm:w-32">{{ $scorer['team_name'] }}</div>
                                                            </div>
                                                        </div>
                                                        <span class="text-sm font-black text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg">{{ $scorer['points'] }} pts</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
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
                                        
                                        @if(empty($tData['top_teams']))
                                            <div class="text-center py-8 text-sm text-gray-400 font-medium">Sin estadísticas de juego registradas</div>
                                        @else
                                            <div class="space-y-3">
                                                @php $teamRank = 1; @endphp
                                                @foreach($tData['top_teams'] as $team)
                                                    <div class="flex items-center justify-between bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-xs font-extrabold text-gray-400 bg-gray-50 h-6 w-6 rounded-full flex items-center justify-center">{{ $teamRank++ }}</span>
                                                            <div class="flex items-center gap-2">
                                                                @if($team['team_logo'])
                                                                    <img src="{{ asset('storage/' . $team['team_logo']) }}" class="h-6 w-6 rounded-full object-cover border border-gray-100" onerror="this.style.display='none'">
                                                                @else
                                                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ substr($team['team_name'], 0, 1) }}</div>
                                                                @endif
                                                                <span class="text-sm font-bold text-gray-800 truncate w-32 sm:w-40">{{ $team['team_name'] }}</span>
                                                            </div>
                                                        </div>
                                                        <span class="text-xs font-black text-blue-700 bg-blue-50 px-2 py-1 rounded">{{ $team['score'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
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

                                        @if(empty($tData['upcoming_games']) || count($tData['upcoming_games']) === 0)
                                            <div class="text-center py-8 text-sm text-gray-400 font-medium">Sin partidos programados pendientes</div>
                                        @else
                                            <!-- Listado vertical con scroll si excede el tamaño -->
                                            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1 nba-scroll">
                                                @foreach($tData['upcoming_games'] as $game)
                                                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between gap-3">
                                                        <div class="text-[10px] font-extrabold text-gray-400 uppercase flex justify-between border-b pb-1 border-gray-50 items-center">
                                                            <span class="truncate w-32 flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 mr-1 text-gray-400 inline">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1 1 15 0Z" />
                                                                </svg>
                                                                {{ $game['court_name'] }}
                                                            </span>
                                                            <span class="flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 mr-1 text-gray-400 inline">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                </svg>
                                                                {{ $game['date_time'] }}
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="flex items-center justify-between gap-2 py-1">
                                                            <!-- Local -->
                                                            <div class="flex items-center gap-2 w-5/12">
                                                                @if($game['local_logo'])
                                                                    <img src="{{ asset('storage/' . $game['local_logo']) }}" class="h-6 w-6 rounded-full object-cover border border-gray-100" onerror="this.style.display='none'">
                                                                @else
                                                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ substr($game['local_name'], 0, 1) }}</div>
                                                                @endif
                                                                <span class="text-xs font-bold text-gray-700 truncate w-16 sm:w-20">{{ $game['local_name'] }}</span>
                                                            </div>
                                                            
                                                            <span class="text-[10px] font-black text-orange-500 bg-orange-50 px-1.5 py-0.5 rounded border border-orange-100">VS</span>
                                                            
                                                            <!-- Visitante -->
                                                            <div class="flex items-center gap-2 w-5/12 justify-end">
                                                                <span class="text-xs font-bold text-gray-700 truncate w-16 sm:w-20 text-right">{{ $game['away_name'] }}</span>
                                                                @if($game['away_logo'])
                                                                    <img src="{{ asset('storage/' . $game['away_logo']) }}" class="h-6 w-6 rounded-full object-cover border border-gray-100" onerror="this.style.display='none'">
                                                                @else
                                                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ substr($game['away_name'], 0, 1) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 text-center mt-2 uppercase tracking-wider">
                                                <i class="fa-solid fa-angles-down mr-1 animate-pulse"></i> Desliza hacia abajo <i class="fa-solid fa-angles-down ml-1 animate-pulse"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

    </main>

    <footer class="text-center py-8 text-sm text-gray-400 border-t border-gray-100 mt-12 bg-white">
        &copy; {{ date('Y') }} CrossoverMX. Todos los derechos reservados.
    </footer>

</body>
</html>
