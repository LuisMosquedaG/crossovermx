<x-guest-layout>
    
    <style>
        /* Inputs Estilo Pro (Cajones Redondeados) */
        .input-pro {
            background-color: rgba(255, 255, 255, 0.65); /* Fondo blanco semi-transparente */
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }
        .input-pro:focus {
            background-color: rgba(255, 255, 255, 0.9);
            border-color: #ff6b00;
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.15);
            transform: translateY(-1px);
        }
    </style>

    <!-- SIN TARJETA EXTERNA: El contenido vive directamente en la tarjeta de guest.blade.php -->
    <div class="w-full space-y-8">
        
        <!-- 1. HEADER AREA -->
<div class="text-center">
    <!-- Logo (h-24 hace que sea más grande) -->
    <div class="relative inline-block mb-5">
        <img class="relative w-auto h-24 drop-shadow-md" src="{{ asset('images/Logo completo.png') }}" alt="CrossoverMX Logo">
    </div>
    
    <!-- Título Pro -->
    <h2 class="text-4xl font-black text-[#1e293b] tracking-tight leading-none mb-2">
        Iniciar Sesión
    </h2>
    
    <!-- Subtítulo -->
    <p class="text-[#64748b] font-medium text-sm mt-2 tracking-wide">
        Sistema de Gestión de Torneos
    </p>
</div>

        <!-- Mensajes de estado -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Formulario -->
        <form class="space-y-5" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Input Email (Estilo Cajón Redondeado) -->
                <div class="group">
                    <label for="email" class="block text-xs font-bold text-[#1e293b] uppercase tracking-wider mb-2 group-focus-within:text-[#ff6b00] transition-colors">
                        Usuario
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        class="input-pro appearance-none block w-full px-4 py-3.5 border border-slate-200 placeholder-slate-400 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff6b00] focus:border-transparent sm:text-sm" 
                        placeholder="nombre@ejemplo.com" value="{{ old('email') }}">
                </div>
                
                <!-- Input Contraseña (Estilo Cajón Redondeado con Ojito) -->
                <div class="group">
                    <label for="password" class="block text-xs font-bold text-[#1e293b] uppercase tracking-wider mb-2 group-focus-within:text-[#ff6b00] transition-colors">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="input-pro appearance-none block w-full pl-4 pr-11 py-3.5 border border-slate-200 placeholder-slate-400 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff6b00] focus:border-transparent sm:text-sm" 
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#ff6b00] focus:outline-none transition-colors" title="Mostrar/Ocultar contraseña">
                            <!-- Eye Open Icon -->
                            <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Closed Icon -->
                            <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.046 10.046 0 013.122-.463c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-2.163 3.693M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Opciones -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox" 
                        class="h-4 w-4 text-[#ff6b00] focus:ring-[#ff6b00] border-gray-300 rounded cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-xs font-semibold text-[#475569] cursor-pointer">
                        Recordar
                    </label>
                </div>

                <div class="text-xs font-bold">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[#ff6b00] hover:text-[#ea580c] transition-colors hover:underline decoration-2 underline-offset-2">
                            ¿Olvidaste tu clave?
                        </a>
                    @endif
                </div>
            </div>

            <!-- Botón Oscuro Pro -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full relative flex justify-center py-3.5 px-4 border border-transparent text-sm font-black rounded-xl text-white bg-[#1e293b] hover:bg-[#0f172a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ff6b00] shadow-md hover:shadow-[#ff6b00]/30 transform active:scale-[0.98] transition-all duration-200 group">
                    
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <span class="relative flex items-center gap-2 uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-[#ff6b00]"></span>
                        Acceder
                    </span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>