<div class="relative z-20">
    <!-- ESPACIADOR SUPERIOR (Mantiene el contenido tapado por la barra flotante) -->
    <div class="h-16 w-full"></div>

    <!-- NAVEGACIÓN PRINCIPAL (BARRA FLOTANTE) -->
    <nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[96%] md:w-[90%] z-50 transition-all duration-300
               bg-white/80 backdrop-blur-md border border-orange-500/50 rounded-[50px] px-2 py-2 shadow-[0_4px_30px_rgba(0,0,0,0.1)]
               flex justify-between items-center">
        
        <!-- 1. IZQUIERDA: LOGO (Actúa como Dashboard/Inicio) -->
        <div class="shrink-0 flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group pl-2 pr-4 py-1 rounded-full hover:bg-orange-50 transition-colors" title="Inicio / Dashboard">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto transition-transform group-hover:scale-105">
                <span class="hidden md:block font-bold text-xl tracking-wide uppercase" style="color: #1e293b;">
                    Crossover<span style="color: #ff6b00;">MX</span>
                </span>
            </a>
        </div>

        <!-- 2. CENTRO: ICONOS DE NAVEGACIÓN (SOLO MÓVIL) -->
        <div class="flex lg:hidden items-center justify-center flex-1 gap-1 sm:gap-4 mx-1">
            
            <!-- Torneos -->
            @if(!auth()->user()->hasRole('Master Admin'))
                <a href="{{ route('tournaments.index') }}" class="p-2 rounded-full {{ request()->routeIs('tournaments.*') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:bg-gray-100' }} transition-colors" title="Torneos">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 0 0-.584.859 6.753 6.753 0 0 0 6.138 5.6 6.73 6.73 0 0 0 2.743 1.346A6.707 6.707 0 0 1 9.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 0 0-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-2.25-2.25h-.75v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 0 1-1.112-3.173 6.73 6.73 0 0 0 2.743-1.347 6.753 6.753 0 0 0 6.139-5.6.75.75 0 0 0-.585-.858 47.077 47.077 0 0 0-3.07-.543V2.62a.75.75 0 0 0-.658-.744 49.22 49.22 0 0 0-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 0 0-.657.744zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 0 1 3.16 5.337a45.6 45.6 0 0 1 2.006-.343v.256zm13.5 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 0 1-2.863 3.207 6.72 6.72 0 0 0 .857-3.294Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            <!-- Partidos Manuales (Nuevo Módulo) -->
            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro'))
                <a href="{{ route('games.index') }}" class="p-2 rounded-full {{ request()->routeIs('games.index', 'games.create', 'games.*') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:bg-gray-100' }} transition-colors" title="Partidos Manuales">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121.75 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021.75 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121.75 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                </a>
            @endif

            <!-- Equipos -->
            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro'))
                <a href="{{ route('teams.index') }}" class="p-2 rounded-full {{ request()->routeIs('teams.*') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:bg-gray-100' }} transition-colors" title="Equipos">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </a>
            @endif

            <!-- Jugadores -->
            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach'))
                <a href="{{ route('players.index') }}" class="p-2 rounded-full {{ request()->routeIs('players.*') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:bg-gray-100' }} transition-colors" title="Jugadores">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </a>
            @endif

            <!-- Panel (Solo Master Admin en móvil) -->
            @if(auth()->user()->hasRole('Master Admin'))
                <a href="{{ route('clients.index') }}" class="p-2 rounded-full {{ request()->routeIs('clients.*') ? 'text-orange-600 bg-orange-50' : 'text-gray-500 hover:bg-gray-100' }} transition-colors" title="Panel Admin">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- 3. CENTRO: LINKS DE TEXTO (SOLO ESCRITORIO) -->
        <div class="hidden lg:flex items-center space-x-6">
            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach'))
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-orange-500 transition-colors {{ request()->routeIs('dashboard') ? 'text-orange-600' : '' }}">
                    Dashboard
                </a>
            @endif

            @if(!auth()->user()->hasRole('Master Admin'))
                <a href="{{ route('tournaments.index') }}" class="text-sm font-semibold text-gray-600 hover:text-orange-500 transition-colors {{ request()->routeIs('tournaments.*') ? 'text-orange-600' : '' }}">
                    Torneos
                </a>
            @endif

            @if(!auth()->user()->hasRole('Master Admin'))
                <a href="{{ route('games.index') }}" class="text-sm font-semibold text-gray-600 hover:text-orange-500 transition-colors {{ request()->routeIs('games.*') ? 'text-orange-600' : '' }}">
                    Partidos
                </a>
            @endif

            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro'))
                <a href="{{ route('teams.index') }}" class="text-sm font-semibold text-gray-600 hover:text-orange-500 transition-colors {{ request()->routeIs('teams.*') ? 'text-orange-600' : '' }}">
                    Equipos
                </a>
            @endif

            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach'))
                <a href="{{ route('players.index') }}" class="text-sm font-semibold text-gray-600 hover:text-orange-500 transition-colors {{ request()->routeIs('players.*') ? 'text-orange-600' : '' }}">
                    Jugadores
                </a>
            @endif

            @if(auth()->user()->hasRole('Master Admin'))
                <a href="{{ route('clients.index') }}" class="text-sm font-semibold text-gray-600 hover:text-orange-500 transition-colors {{ request()->routeIs('clients.*') ? 'text-orange-600' : '' }}">
                    Panel
                </a>
            @endif
        </div>

        <!-- 4. DERECHA: CONFIGURACIÓN Y SALIR -->
        <div class="flex items-center gap-1 pl-2 border-l border-gray-200 ml-2">
            
            {{-- CONFIGURACIÓN (Icono Siempre Visible) --}}
            @if(!auth()->user()->hasRole('Master Admin') && !auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach'))
                <div class="relative config-dropdown-container">
                    <button id="config-desktop-btn" class="p-2 text-gray-500 hover:text-orange-600 hover:bg-orange-50 rounded-full transition-all duration-200" title="Ajustes">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                    </button>

                    <!-- Menú Desplegable -->
                    <div id="config-desktop-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50 transition-all duration-200 ease-out origin-top-right transform scale-95 opacity-0 border border-gray-100">
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 {{ request()->routeIs('users.*') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">Usuarios</a>
                        <a href="{{ route('courts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 {{ request()->routeIs('courts.*') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">Canchas</a>
                        <a href="{{ route('strengths.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 {{ request()->routeIs('strengths.*') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">Fuerzas</a>
                        <div class="h-px bg-gray-100 my-1 mx-2"></div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 {{ request()->routeIs('profile.edit') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">Mi Perfil</a>
                    </div>
                </div>
            @endif

            {{-- BOTÓN DE CERRAR SESIÓN --}}
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres salir del sistema?');">
                @csrf
                <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-all duration-200" title="Cerrar Sesión">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                    </svg>
                </button>
            </form>
        </div>
    </nav>

    <!-- YA NO SE NECESITA LA BARRA INFERIOR, SE HA ELIMINADO PARA AHORRAR ESPACIO -->

</div>

{{-- SCRIPT PARA EL MENÚ CONFIGURACIÓN (Mantiene su funcionalidad) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función genérica para alternar menús
        function setupMenu(triggerId, menuId) {
            const trigger = document.getElementById(triggerId);
            const menu = document.getElementById(menuId);

            if (!trigger || !menu) return;

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = menu.classList.contains('hidden');
                closeAllConfigMenus();

                if (isHidden) {
                    menu.classList.remove('hidden');
                    setTimeout(() => {
                        menu.classList.remove('opacity-0', 'scale-95');
                        menu.classList.add('opacity-100', 'scale-100');
                    }, 10);
                }
            });
        }

        function closeAllConfigMenus() {
            const menus = document.querySelectorAll('[id$="-menu"]');
            menus.forEach(menu => {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.remove('opacity-100', 'scale-100');
                    menu.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        if (menu.classList.contains('opacity-0')) {
                            menu.classList.add('hidden');
                        }
                    }, 200);
                }
            });
        }

        setupMenu('config-desktop-btn', 'config-desktop-menu');
        // setupMenu('config-mobile-btn', 'config-mobile-menu'); // Ya no existe el botón móvil, se usa el mismo de escritorio

        document.addEventListener('click', (e) => {
            const isClickInsideDesktop = e.target.closest('#config-desktop-btn') || e.target.closest('#config-desktop-menu');
            // Ya no revisamos el menú móvil
            if (!isClickInsideDesktop) {
                closeAllConfigMenus();
            }
        });
    });
</script>