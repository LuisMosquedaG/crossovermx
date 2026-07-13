<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrossoverMX | Gestión de Torneos</title>
    
    <!-- Fuente: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Iconos: FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-padding { padding: 100px 0; }

        /* --- NAV (Más Transparente) --- */
        header {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1100px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.5); /* CAMBIO: 0.5 para más transparencia */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--brand-orange);
            border-radius: 50px;
            padding: 12px 30px;
            box-shadow: none; 
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

        .nav-links { display: flex; gap: 30px; }

        .nav-links a {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-body);
            transition: color 0.2s;
            position: relative;
            text-shadow: 0 1px 2px rgba(255,255,255,0.8); /* Mejor legibilidad con fondo transparente */
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

        /* --- HERO SECTION --- */
        #hero {
            background-color: var(--brand-blue-light);
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
            padding-top: 180px;
            padding-bottom: 160px;
            position: relative;
            overflow: hidden;
        }

        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.7;
        }
        .shape-1 { width: 400px; height: 400px; background: var(--brand-orange-light); top: -50px; right: -50px; }
        .shape-2 { width: 300px; height: 300px; background: #e2e8f0; bottom: 100px; left: -50px; }

        .hero-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-text h1 {
            font-size: 3.8rem;
            line-height: 1.1;
            margin-bottom: 24px;
            color: var(--brand-blue);
        }

        .gradient-text {
            background: linear-gradient(to right, #ff9100, #ff6b00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: var(--text-body);
            margin-bottom: 40px;
            max-width: 90%;
            line-height: 1.6;
        }

        .wave-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
            z-index: 3;
        }
        .wave-divider svg {
            position: relative;
            display: block;
            width: calc(138% + 1.3px);
            height: 80px;
        }
        .wave-divider .shape-fill { fill: #FFFFFF; }

        .hero-visual {
            position: relative;
            min-height: 400px;
        }

        .floating-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-xl);
            padding: 24px;
            position: absolute;
            box-shadow: var(--shadow-hover);
            animation: float 6s ease-in-out infinite;
        }
        .card-1 { top: 20px; right: 0; width: 280px; z-index: 3; animation-delay: 0s; border-top: 4px solid var(--brand-orange); }
        .card-2 { bottom: -40px; left: 20px; width: 240px; z-index: 4; background: var(--brand-blue); color: white; animation-delay: 1.5s; }

        .fc-stat { font-size: 2.5rem; font-weight: 700; display: block; }
        .card-1 .fc-stat { color: var(--brand-orange); }
        .card-2 .fc-stat { color: white; }
        
        .fc-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;}
        .card-1 .fc-label { color: var(--text-muted); }
        .card-2 .fc-label { color: #94a3b8; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* --- SECTION HEADERS --- */
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--brand-blue);
        }
        .section-header span {
            color: var(--brand-orange);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            display: block;
            margin-bottom: 10px;
        }

        /* --- IMPACTO TOTAL --- */
        #summary {
            background: var(--bg-alt);
            position: relative;
            z-index: 4;
        }
        .role-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 60px;
        }
        .role-item {
            background: white;
            padding: 40px 30px;
            border-radius: var(--radius-xl);
            text-align: center;
            box-shadow: var(--shadow-soft);
            border: 1px solid #f1f5f9;
            border-top: 4px solid var(--brand-orange);
            transition: 0.3s;
        }
        .role-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        .role-avatar {
            width: 80px;
            height: 80px;
            background: white;
            border: 2px solid var(--brand-orange);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--brand-blue);
            transition: 0.3s;
        }
        .role-item:hover .role-avatar {
            background: var(--brand-orange);
            border-color: var(--brand-orange);
            color: white;
        }
        .role-item h3 {
            color: var(--brand-blue);
            font-weight: 800;
            margin-bottom: 10px;
        }

    /* --- SECCION DOLORES DE CABEZA (ESFERA 3D AJUSTADA VISUAL) --- */
    #pain-points {
        background: var(--brand-blue);
        color: white;
        position: relative;
        overflow: hidden;
        padding-bottom: 120px; 
        background: radial-gradient(circle at center, #253347 0%, #1e293b 100%);
    }
    
    #pain-points .section-header h2, #pain-points .section-header span {
        color: white;
    }

    .carousel-3d-scene {
        width: 100%;
        height: 250px;
        perspective: 1000px; 
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 40px;
    }

    .carousel-track-3d {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        transform: rotateY(var(--rotation, 0deg));
        transition: transform 0.1s linear; 
    }

    /* Estado por defecto: TARJETAS LATERALES (BORROSAS Y SIN COLOR) */
    .flip-card {
        background-color: transparent;
        height: 220px;
        width: 220px;
        cursor: pointer;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -110px;
        margin-top: -110px;
        transform: rotateY(calc(var(--i) * 72deg)) translateZ(280px);
        transition: all 0.5s ease; /* Transición suave al cambiar de estado */
        
        /* EFECTO LATERAL: Opacidad baja, desenfoque y escala de grises */
        opacity: 0.15; /* Muy tenue, casi silueta */
        filter: blur(4px) grayscale(100%); 
        pointer-events: none; /* Evita clics accidentales en tarjetas borrosas */
    }

    /* Estado: TARJETA DEL FRENTE (SÓLIDA) */
    .flip-card.active {
        opacity: 1;
        filter: none; /* Quitamos desenfoque y blanco/negro */
        pointer-events: auto; /* Habilita interacción */
        z-index: 10;
    }

    /* Estado: HOVER (INTERACCIÓN MANUAL) */
    /* Si pasas el mouse sobre una tarjeta lateral, se hace sólida temporalmente */
    .flip-card:hover {
        opacity: 1;
        filter: none;
        z-index: 20;
    }

    /* --- ESTILOS INTERNOS DEL FLIP CARD --- */
    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
        border-radius: var(--radius-xl);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }

    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: var(--radius-xl);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .flip-card-front {
        background: #253347; 
        color: #94a3b8;
    }

    .flip-card-back {
        background: var(--brand-orange);
        color: white;
        transform: rotateY(180deg);
    }

    /* Ocultar el reverso por defecto para que no se vea el naranja pasando */
    .flip-card:not(.active):not(:hover) .flip-card-back {
        opacity: 0;
    }

    .pain-icon { font-size: 2.2rem; margin-bottom: 15px; opacity: 0.8; }
    .flip-card-back .pain-icon { color: white; opacity: 1; }
    
    .pain-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; color: white; line-height: 1.2; }
    .pain-desc { font-size: 0.75rem; line-height: 1.4; color: #cbd5e1; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;}
    .flip-card-back .pain-desc { color: white; font-weight: 500; font-size: 0.8rem;}

    .flip-hint {
        margin-top: 10px;
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.5;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .carousel-3d-scene { perspective: 600px; height: 200px; }
        .flip-card {
            width: 180px; 
            height: 180px;
            margin-left: -90px;
            margin-top: -90px;
            transform: rotateY(calc(var(--i) * 72deg)) translateZ(200px);
        }
        .pain-icon { font-size: 1.8rem; }
        .pain-title { font-size: 0.95rem; }
        .pain-desc { font-size: 0.7rem; }
    }

        /* --- TORNEOS --- */
        #features { background: white; }

        .tournaments-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .tournament-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-xl);
            padding: 40px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
        }

        .tournament-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: var(--brand-orange);
        }

        .t-icon-box {
            width: 60px;
            height: 60px;
            background: var(--brand-orange-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--brand-orange);
            margin-bottom: 25px;
        }

        .tournament-card h3 {
            font-size: 1.5rem;
            color: var(--brand-blue);
            margin-bottom: 15px;
        }

        .tournament-card p {
            color: var(--text-body);
            line-height: 1.6;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .t-badge {
            display: inline-block;
            background: var(--brand-blue);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* --- CALENDARIO INTELIGENTE --- */
        #calendar-logic {
            background: var(--brand-blue-light);
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .logic-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-top: 50px;
        }

        .logic-item {
            background: white;
            padding: 30px 20px;
            border-radius: var(--radius-xl);
            text-align: center;
            box-shadow: var(--shadow-soft);
            border: 1px solid white;
            transition: 0.3s;
        }

        .logic-item:hover {
            transform: translateY(-5px);
            border-color: var(--brand-orange);
        }

        .logic-icon {
            font-size: 2rem;
            color: var(--brand-orange);
            margin-bottom: 20px;
            background: var(--brand-orange-light);
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        .logic-item h3 {
            font-size: 1.1rem;
            color: var(--brand-blue);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .logic-item p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* --- DEMO SECTION --- */
        #demo {
            background: var(--bg-alt);
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }
        .demo-container {
            background: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: var(--shadow-hover);
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .scoreboard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--brand-blue);
            padding: 20px;
            border-radius: 16px;
            margin: 30px 0;
            color: white;
            font-family: 'Courier New', monospace;
            border: 2px solid rgba(255,255,255,0.1);
        }
        .sb-team { flex: 1; }
        .sb-score { font-size: 3rem; font-weight: 900; color: var(--brand-orange); }
        .sb-timer { flex: 1; font-size: 2.5rem; font-weight: bold; color: white; border-left: 2px solid rgba(255,255,255,0.2); border-right: 2px solid rgba(255,255,255,0.2); }
        
        .control-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .c-btn {
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            font-size: 0.8rem;
            flex: 1 1 auto;
            max-width: 120px;
        }
        .c-main { background: var(--brand-orange); color: white; box-shadow: 0 4px 10px rgba(255,107,0,0.3); }
        .c-main:hover { background: #e65c00; }
        .c-sub { background: #f1f5f9; color: var(--brand-blue); border: 1px solid #e2e8f0; }
        .c-sub:hover { background: #e2e8f0; }
        .c-foul { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; }

        /* --- BENEFITS --- */
        .benefit-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            margin-bottom: 100px;
        }
        .benefit-row.reverse { direction: rtl; }
        .benefit-row.reverse .benefit-content { direction: ltr; }
        .benefit-icon-box {
            width: 60px;
            height: 60px;
            background: var(--brand-orange);
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
            box-shadow: var(--shadow-orange);
        }
        .benefit-content h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--brand-blue);
        }
        .check-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 1.05rem;
            color: var(--text-body);
        }
        .check-list i { color: var(--brand-orange); margin-top: 5px; }

        /* --- FOOTER --- */
        footer {
            background: var(--brand-blue);
            color: #cbd5e1;
            padding: 60px 0;
            text-align: center;
            border-top: 4px solid var(--brand-orange);
        }
        footer p { color: #e2e8f0; }
        .logo-text-footer {
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            color: white;
            text-transform: uppercase;
        }
        .logo-text-footer span { color: var(--brand-orange); }
        
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
        }
        .social-icon:hover {
            color: var(--brand-orange);
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.05);
        }

        /* --- ANIMATIONS --- */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* --- MEDIA QUERIES --- */
        @media (max-width: 992px) {
            .hero-layout { grid-template-columns: 1fr; text-align: center; gap: 40px; }
            .hero-text h1 { font-size: 3rem; }
            .hero-visual { display: none; }
            .role-cards, .tournaments-grid, .logic-grid { grid-template-columns: repeat(2, 1fr); }
            .pain-grid { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); } /* Más flexible en tablet */
        }

        @media (max-width: 768px) {
            header { width: 95%; padding: 10px 15px; top: 10px; }
            .nav-links { display: none !important; }
            nav { justify-content: space-between; }
            .logo-text { font-size: 1.1rem; }
            .btn { padding: 8px 20px; font-size: 0.8rem; }
            .section-padding { padding: 60px 0; }
            #hero { padding-top: 120px; padding-bottom: 80px; }
            .hero-text h1 { font-size: 2.5rem; line-height: 1.2; }
            .hero-text p { font-size: 1rem; margin: 0 auto 30px auto; }
            
            .role-cards, .pain-grid, .tournaments-grid, .benefit-row, .logic-grid { grid-template-columns: 1fr; }
            .benefit-row.reverse { direction: ltr; }
            .benefit-icon-box { margin: 0 auto 20px auto; }
            .check-list li { justify-content: center; text-align: left; }
            
            .scoreboard { flex-direction: column; gap: 15px; }
            .sb-timer {
                border-left: none; border-right: none;
                border-top: 2px solid rgba(255,255,255,0.2);
                border-bottom: 2px solid rgba(255,255,255,0.2);
                padding: 10px 0; width: 100%;
            }
        }
    </style>
</head>
<body>

    <header id="navbar">
        <nav>
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="CrossoverMX Logo" class="logo-img">
                <div class="logo-text">Crossover<span>MX</span></div>
            </div>
            
            <ul class="nav-links">
                <li><a href="#summary">Impacto</a></li>
                <li><a href="#features">Torneos</a></li>
                <li><a href="#calendar-logic">Calendario</a></li>
                <li><a href="{{ route('public.standings') }}" style="color: var(--brand-orange); font-weight: bold;">Posiciones</a></li>
            </ul>
            
            <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        
        <div class="container hero-layout">
            <div class="hero-text reveal">
                <h1>Domina tu Juego <br><span class="gradient-text">Con CrossoverMX</span></h1>
                <p>
                    El sistema definitivo para ligas de baloncesto. Algoritmos de calendario complejos, control de partidos en tiempo real y gestión administrativa total.
                </p>
                <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 15px 40px; font-size: 1rem;">Empezar Ahora</a>
            </div>
            
            <div class="hero-visual">
                <div class="floating-card card-1">
                    <span class="fc-label">Torneos Activos</span>
                    <span class="fc-stat">12</span>
                    <div style="height: 4px; background: #f1f5f9; margin-top: 10px; border-radius: 2px;">
                        <div style="width: 75%; height: 100%; background: var(--brand-orange); border-radius: 2px;"></div>
                    </div>
                </div>
                <div class="floating-card card-2">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-check-circle" style="color: var(--brand-orange);"></i>
                        <span style="font-weight: 700; color: white;">Calendario OK</span>
                    </div>
                    <p style="font-size: 0.8rem; color: #cbd5e1;">Generado sin conflictos.</p>
                </div>
            </div>
        </div>

        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Impacto Total -->
    <section id="summary" class="section-padding">
        <div class="container">
            <div class="section-header reveal">
                <span>Impacto Total</span>
                <h2>Resumen Ejecutivo</h2>
                <p style="color: var(--text-muted);">Una solución integral para cada rol.</p>
            </div>

            <div class="role-cards">
                <div class="role-item reveal" style="transition-delay: 0ms;">
                    <div class="role-avatar"><i class="fas fa-user-tie"></i></div>
                    <h3>Organizador</h3>
                    <p style="color: var(--text-body); margin-top: 10px; font-size: 0.95rem;">
                        Reduce el trabajo manual de cuadrar horarios complejos a un clic. Algoritmos inteligentes que respetan disponibilidad de canchas, árbitros y equipos.
                    </p>
                </div>
                <div class="role-item reveal" style="transition-delay: 100ms;">
                    <div class="role-avatar"><i class="fas fa-user-shield"></i></div>
                    <h3>Árbitro</h3>
                    <p style="color: var(--text-body); margin-top: 10px; font-size: 0.95rem;">
                        Herramienta digital segura. Manejo de cronómetros, actas digitales y expulsiones automáticas que eliminan el error humano en el marcador.
                    </p>
                </div>
                <div class="role-item reveal" style="transition-delay: 200ms;">
                    <div class="role-avatar"><i class="fas fa-basketball-ball"></i></div>
                    <h3>Entrenador</h3>
                    <p style="color: var(--text-body); margin-top: 10px; font-size: 0.95rem;">
                        Portal transparente para consultar estadísticas en tiempo real, aceptar contratos digitales y descargar credenciales oficiales.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de Torneo -->
    <section id="features" class="section-padding">
        <div class="container">
            <div class="section-header reveal">
                <span>Flexibilidad Total</span>
                <h2>Tipos de Torneos</h2>
                <p style="color: var(--text-muted);">Genera formatos profesionales sin importar la complejidad.</p>
            </div>

            <div class="tournaments-grid">
                <!-- Liga / Round Robin -->
                <div class="tournament-card reveal" style="transition-delay: 0ms;">
                    <div class="t-icon-box"><i class="fas fa-sync-alt"></i></div>
                    <h3>Liga (Round Robin)</h3>
                    <p>
                        Todos contra todos, sin excepciones. El sistema genera automáticamente vueltas de ida y vuelta, calcula la tabla de posiciones en tiempo real y permite activar una fase de Playoffs al finalizar la temporada regular.
                    </p>
                    <span class="t-badge">Más Popular</span>
                </div>

                <!-- Eliminación Directa -->
                <div class="tournament-card reveal" style="transition-delay: 100ms;">
                    <div class="t-icon-box"><i class="fas fa-trophy"></i></div>
                    <h3>Eliminación Directa</h3>
                    <p>
                        No hay segundas oportunidades.Cruces automáticos por posiciones o semillas. Pierdes y te vas.
                    </p>
                    <span class="t-badge">Playoffs</span>
                </div>

                <!-- Doble Eliminatoria -->
                <div class="tournament-card reveal" style="transition-delay: 200ms;">
                    <div class="t-icon-box"><i class="fas fa-sitemap"></i></div>
                    <h3>Doble Eliminatoria</h3>
                    <p>
                        Equipos impares. Sin potencias. Sin problemas. El sistema genera el calendario, asigna BYE automáticamente y gestiona ganadores y perdedores.¿El perdedor gana la final? Reset Game activado. El juego decide.
                    </p>
                    <span class="t-badge">Avanzado</span>
                </div>

                <!-- Formatos Ilimitados -->
                <div class="tournament-card reveal" style="transition-delay: 300ms;">
                    <div class="t-icon-box"><i class="fas fa-infinity"></i></div>
                    <h3>Formatos sin límites</h3>
                    <p>
                        Nuestro motor de calendario rompe las reglas. Diseña ligas 3x3, 2x2, merce y cualquier formato que tengas en mente. Tú pones la idea, el sistema se adapta.
                    </p>
                    <span class="t-badge">Versátil</span>
                </div>
            </div>
        </div>
    </section>

    <!-- INTELIGENCIA EN HORARIOS (ACTUALIZADO CON 8 PUNTOS) -->
    <section id="calendar-logic" class="section-padding">
        <div class="container">
            <div class="section-header reveal">
                <span>Detalles Técnicos</span>
                <h2>Motor de Calendarios</h2>
                <p style="color: var(--text-muted);">8 pilares de la planificación inteligente.</p>
            </div>

            <div class="logic-grid">
                <!-- Punto 1: Optimización de Tiempos -->
                <div class="logic-item reveal" style="transition-delay: 0ms;">
                    <div class="logic-icon"><i class="fas fa-stopwatch"></i></div>
                    <h3>Optimización Absoluta</h3>
                    <p>Maximiza tu capacidad. Ajusta los tiempos al minuto real (cuartos + descansos) para programar más partidos y ahorrar en alquileres.</p>
                </div>

                <!-- Punto 2: Gestión Multi-Escenario -->
                <div class="logic-item reveal" style="transition-delay: 100ms;">
                    <div class="logic-icon"><i class="fas fa-building"></i></div>
                    <h3>Gestión Multi-Escenario</h3>
                    <p>Mezcla canchas con horarios diferentes. El sistema respeta restricciones específicas (ej. escuelas) y horarios globales.</p>
                </div>

                <!-- Punto 3: Prevención de Fatiga -->
                <div class="logic-item reveal" style="transition-delay: 200ms;">
                    <div class="logic-icon"><i class="fas fa-heart-pulse"></i></div>
                    <h3>Bienestar y Reglas</h3>
                    <p>Evita la fatiga y lesiones. Configura descansos obligatorios entre juegos o días para asegurar un torneo justo y seguro.</p>
                </div>

                <!-- Punto 4: Solución de Conflictos -->
                <div class="logic-item reveal" style="transition-delay: 300ms;">
                    <div class="logic-icon"><i class="fas fa-magic"></i></div>
                    <h3>Solución de Conflictos</h3>
                    <p>Inteligencia adaptativa. Si no hay espacio ideal, el sistema busca la mejor alternativa automáticamente en lugar de fallar.</p>
                </div>

                <!-- Punto 5: Control de Flujo -->
                <div class="logic-item reveal" style="transition-delay: 400ms;">
                    <div class="logic-icon"><i class="fas fa-random"></i></div>
                    <h3>Control de Flujo</h3>
                    <p>Decide el ritmo: Intercala categorías y fuerzas para un espectáculo continuo, o agrúpalos para cerrar brackets más rápido.</p>
                </div>

                <!-- Punto 6: Generación en Segundos -->
                <div class="logic-item reveal" style="transition-delay: 500ms;">
                    <div class="logic-icon"><i class="fas fa-bolt"></i></div>
                    <h3>Generación en Segundos</h3>
                    <p>Olvídate de semanas de Excel. Genera calendarios maestros con múltiples categorías interconectadas en un solo clic.</p>
                </div>

                <!-- Punto 7: Equidad Matemática -->
                <div class="logic-item reveal" style="transition-delay: 600ms;">
                    <div class="logic-icon"><i class="fas fa-balance-scale"></i></div>
                    <h3>Equidad Matemática</h3>
                    <p>Manejo automático de números impares y descansos ('byes'). Rotación justa que elimina sospechas de favoritismo.</p>
                </div>

                <!-- NUEVO Punto 8: Tipos de Liga -->
                <div class="logic-item reveal" style="transition-delay: 700ms;">
                    <div class="logic-icon"><i class="fas fa-layer-group"></i></div>
                    <h3>Tipos de Liga</h3>
                    <p>Soporta múltiples categorías (Varonil, Femenil, Mixto) y fuerzas en un mismo torneo, intercalando partidos para un mejor flujo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section id="benefits" class="section-padding">
        <div class="container">
            <div class="benefit-row reveal">
                <div class="benefit-content">
                    <div class="benefit-icon-box"><i class="fas fa-rocket"></i></div>
                    <h3>Eficiencia Absoluta</h3>
                    <p style="color: var(--text-body); margin-bottom: 20px; font-size: 1.1rem;">
                        Haz más. En menos tiempo.
                    </p>
                    <ul class="check-list">
                        <li><i class="fas fa-check"></i> Calendarios creados en segundos, no en horas</li>
                        <li><i class="fas fa-check"></i> Control automático de suspensiones de equipos y jugadores</li>
                        <li><i class="fas fa-check"></i> Credenciales de jugadores listas para imprimir</li>
                        <li><i class="fas fa-check"></i> Tablas de posiciones actualizadas en automático</li>
                        <li><i class="fas fa-check"></i> Reagendamiento automático de partidos, sin caos</li>
                        <li><i class="fas fa-check"></i> Hoja de anotaciones electrónica, directa al sistema</li>
                    <p style="color: var(--text-body); margin-bottom: 20px; font-size: 1.1rem;">
                        Menos operación. Menos errores.Más juego.
                    </p>

                    </ul>
                </div>
                <div style="background: var(--brand-orange-light); height: 300px; border-radius: 24px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-line" style="font-size: 6rem; color: var(--brand-orange); opacity: 0.4;"></i>
                </div>
            </div>

            <div class="benefit-row reverse reveal">
                <div class="benefit-content">
                    <div class="benefit-icon-box"><i class="fas fa-mobile-alt"></i></div>
                    <h3>UX Flexible y Escalable</h3>
                    <p style="color: var(--text-body); margin-bottom: 20px; font-size: 1.1rem;">
                        Diseñado para crecer con tu liga. Funciona en cualquier modalidad: desde categorías infantiles hasta ligas profesionales, desde tablets en la cancha hasta PC en la oficina.
                    </p>
                    <ul class="check-list">
                        <li><i class="fas fa-check"></i> Validaciones inteligentes que previenen errores críticos</li>
                        <li><i class="fas fa-check"></i> Configuración granular por torneo, reglas y formatos a la medida</li>
                        <li><i class="fas fa-check"></i> Experiencia de uso clara, rápida y consistente para todos los roles</li>
                        <p style="color: var(--text-body); margin-bottom: 20px; font-size: 1.1rem;">
                        Un sistema que se adapta a tu operación. No al revés.
                        </p>
                    </ul>
                </div>
                <div style="background: #f1f5f9; height: 300px; border-radius: 24px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-universal-access" style="font-size: 6rem; color: var(--brand-blue); opacity: 0.2;"></i>
                </div>
            </div>
        </div>

        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="logo-container" style="justify-content: center; margin-bottom: 20px;">
                <img src="{{ asset('images/logo.png') }}" alt="CrossoverMX Logo" class="logo-img">
                <div class="logo-text-footer">Crossover<span>MX</span></div>
            </div>
            <p>2026 Sistema de Gestión de Torneos. Todos los derechos reservados.</p>

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

    <script>
        // Intersection Observer para animaciones de reveal
        const reveals = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => revealObserver.observe(el));

        // Lógica del Marcador (Demo)
        let clockRunning = false;
        let timeLeft = 600;
        let timerInterval;
        let scoreHome = 0;
        let scoreAway = 0;

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        }

        function updateDisplay() {
            document.getElementById('game-clock').innerText = formatTime(timeLeft);
            document.getElementById('score-home').innerText = scoreHome;
            document.getElementById('score-away').innerText = scoreAway;
        }

        function toggleClock() {
            const btn = document.getElementById('btn-play');
            const log = document.getElementById('game-log');
            if (clockRunning) {
                clearInterval(timerInterval);
                clockRunning = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Reanudar';
                log.innerText = "Cronómetro pausado.";
                log.style.color = "var(--text-muted)";
            } else {
                if(timeLeft <= 0) timeLeft = 600;
                clockRunning = true;
                btn.innerHTML = '<i class="fas fa-pause"></i> Pausar';
                log.innerText = "Partido en curso...";
                log.style.color = "var(--brand-orange)";
                timerInterval = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateDisplay();
                    } else {
                        clearInterval(timerInterval);
                        clockRunning = false;
                        btn.innerHTML = '<i class="fas fa-play"></i> Iniciar';
                        log.innerText = "Tiempo terminado.";
                        log.style.color = "#ef4444";
                    }
                }, 1000);
            }
        }

        function resetClock() {
            clearInterval(timerInterval);
            clockRunning = false;
            timeLeft = 600;
            scoreHome = 0;
            scoreAway = 0;
            updateDisplay();
            document.getElementById('btn-play').innerHTML = '<i class="fas fa-play"></i> Iniciar';
            document.getElementById('game-log').innerText = "Listo para iniciar.";
        }

        function addPoints(team, points) {
            if (team === 'home') scoreHome += points;
            else scoreAway += points;
            updateDisplay();
            const log = document.getElementById('game-log');
            log.innerText = `+${points} puntos (${team === 'home' ? 'Local' : 'Visitante'})`;
            log.style.color = "var(--brand-blue)";
        }

        function registerFoul() {
            const log = document.getElementById('game-log');
            log.innerText = "Falta registrada. Verificando reglamento...";
            log.style.color = "#ef4444";
            setTimeout(() => {
                log.innerText = "Sanción aplicada correctamente.";
                log.style.color = "var(--brand-orange)";
            }, 1000);
        }
    // --- LÓGICA ESFERA 3D (CON DETECCIÓN DE TARJETA ACTIVA) ---
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('track3d');
        const cards = document.querySelectorAll('.flip-card');
        
        let rotation = 0;
        let isPaused = false;
        let speed = 0.1; 

        function animate() {
            if (!isPaused) {
                rotation -= speed; 
                track.style.setProperty('--rotation', rotation + 'deg');
                
                // --- NUEVA LÓGICA: Detectar tarjeta del frente ---
                // Normalizamos la rotación entre 0 y 360
                let currentRot = rotation % 360;
                if (currentRot < 0) currentRot += 360;

                cards.forEach((card, index) => {
                    // Cada tarjeta está separada por 72 grados (360 / 5)
                    // Calculamos el ángulo absoluto de esta tarjeta
                    let cardAngle = (currentRot + (index * 72)) % 360;
                    
                    // Si el ángulo está cerca de 0 (o 360), está al frente
                    // Usamos un rango de +/- 36 grados (mitad de la separación)
                    const isFront = (cardAngle > 324) || (cardAngle < 36);

                    if (isFront) {
                        card.classList.add('active');
                    } else {
                        card.classList.remove('active');
                    }
                });
                // -------------------------------------------
            }
            requestAnimationFrame(animate);
        }

        requestAnimationFrame(animate);

        track.addEventListener('mouseenter', () => { isPaused = true; });
        track.addEventListener('mouseleave', () => { isPaused = false; });
        
        track.addEventListener('touchstart', () => { isPaused = true; });
        track.addEventListener('touchend', () => { isPaused = false; });
    });
    </script>
</body>
</html>