<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <link rel="icon" type="image/png" href="<?php echo e(asset('logo.png')); ?>">

        <!-- Fonts: Outfit (Igual que la Welcome) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <style>
            /* --- COPIA EXACTA DE ESTILOS DE LA WELCOME --- */
            body {
                margin: 0;
                font-family: 'Outfit', sans-serif;
                
                /* FORZAR el color exacto del Welcome: #f1f5f9 */
                background-color: #f1f5f9 !important; 
                
                /* FORZAR el patrón de puntos exacto */
                background-image: radial-gradient(#cbd5e1 1px, transparent 1px) !important;
                background-size: 24px 24px !important;
                
                overflow-x: hidden;
            }

            /* Animación de movimiento (Misma que antes) */
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
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen flex items-center justify-center relative">
        
        <!-- CAPA 1: FONDO -->
        <!-- Nota: Ya lo controlamos via CSS en 'body', pero mantenemos esto por seguridad de capas -->
        <div class="absolute inset-0 -z-20 h-full w-full bg-transparent pointer-events-none"></div>

        <!-- CAPA 2: NUBES DE COLOR (Tonos exactos de Welcome) -->
        <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
            
            <!-- Nube 1: #fff7ed (Brand Orange Light) -->
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-[#fff7ed] rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-blob-full"></div>
            
            <!-- Nube 2: #e2e8f0 (Gris Azulado de Welcome) -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#e2e8f0] rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-blob-full animation-delay-2000"></div>
            
            <!-- Nube 3 -->
            <div class="absolute -bottom-40 left-1/3 w-[500px] h-[500px] bg-slate-100 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-blob-full animation-delay-4000"></div>
        </div>

        <!-- CONTENEDOR PRINCIPAL -->
        <div class="w-full sm:max-w-md px-6 py-8 relative z-10">
            
            <!-- TARJETA DE LOGIN (Vidrio) -->
            <!-- Fondo blanco semi-transparente para ver los puntos de atrás -->
            <div class="bg-white/75 backdrop-blur-xl shadow-2xl rounded-2xl border border-white/50 p-8 ring-1 ring-gray-900/5">
                <?php echo e($slot); ?>

            </div>
        </div>
        
    </body>
</html><?php /**PATH C:\xampp\htdocs\sistemaTorneos\resources\views/layouts/guest.blade.php ENDPATH**/ ?>