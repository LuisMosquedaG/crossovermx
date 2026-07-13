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
            background-color: var(--bg-alt);
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

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
            opacity: 1;
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
<body>

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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
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
                    
                    <div class="mb-10 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        
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
            <!-- Sin Torneos Activos banner -->
            <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <i class="fa-solid fa-circle-info text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-bold text-gray-700">No hay torneos activos</h3>
                <p class="text-gray-500 text-sm mt-1 max-w-md mx-auto">Vuelve más tarde cuando comience una nueva competencia de baloncesto en CrossoverMX.</p>
            </div>
        @endif

    </main>

    <footer class="text-center py-8 text-sm text-gray-400 border-t border-gray-100 mt-12 bg-white">
        &copy; {{ date('Y') }} CrossoverMX. Todos los derechos reservados.
    </footer>

</body>
</html>
