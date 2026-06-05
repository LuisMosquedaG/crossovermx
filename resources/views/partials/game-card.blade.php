<div class="nba-card w-full max-w-xs bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 relative min-h-[130px] transition-transform hover:scale-[1.02]">
    
    <div class="flex flex-col h-full">

        <!-- ================== EQUIPO LOCAL ================== -->
        <!-- Agregamos clases condicionales para simular el 'cristal ahumado' si perdió -->
        <div @class([
            'flex items-center justify-between p-3 transition-colors relative z-10',
            // Si ganó: Fondo blanco limpio
            'bg-white' => !($game->status === 'finished' && $game->local_team_score < $game->away_team_score),
            // Si perdió: Cristal ahumado (gris semitransparente + desenfoque)
            'bg-gray-500/30 backdrop-blur-[1px]' => ($game->status === 'finished' && $game->local_team_score < $game->away_team_score)
        ])>
            
            <!-- Izquierda: Logo + Nombre -->
            <div class="flex items-center gap-3 flex-1">
                @if(isset($game->localTeam))
                    <!-- Logo -->
                    @if($game->localTeam->image_path)
                        <img src="{{ asset('storage/' . $game->localTeam->image_path) }}" 
                             class="w-14 h-14 object-contain shrink-0 drop-shadow-sm {{ ($game->status === 'finished' && $game->local_team_score < $game->away_team_score) ? 'opacity-70' : '' }}" 
                             alt="local" onerror="this.style.display='none'">
                    @else
                        <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                            {{ substr($game->localTeam->name, 0, 1) }}
                        </div>
                    @endif

                    <!-- Nombre: Si perdió, se pone gris -->
                    <span class="font-bold text-sm truncate pr-2 {{ ($game->status === 'finished' && $game->local_team_score < $game->away_team_score) ? 'text-gray-500' : 'text-gray-800' }}">
                        {{ $game->localTeam->name }}
                    </span>
                @endif
            </div>

            <!-- Derecha: Marcador -->
            <div class="text-right min-w-[50px] ml-2">
                <span class="text-3xl font-black font-mono {{ ($game->status === 'finished' && $game->local_team_score > $game->away_team_score) ? 'text-green-600' : 'text-gray-300' }}">
                    {{ $game->local_team_score ?? '-' }}
                </span>
            </div>
        </div>

        <!-- Divisor Línea -->
        <div class="h-px bg-gray-100 w-full mx-3"></div>

        <!-- ================== EQUIPO VISITANTE ================== -->
        <div @class([
            'flex items-center justify-between p-3 transition-colors relative z-10',
            'bg-white' => !($game->status === 'finished' && $game->away_team_score < $game->local_team_score),
            'bg-gray-500/30 backdrop-blur-[1px]' => ($game->status === 'finished' && $game->away_team_score < $game->local_team_score)
        ])>
            
            <!-- Izquierda: Logo + Nombre -->
            <div class="flex items-center gap-3 flex-1">
                @if(isset($game->awayTeam))
                    <!-- Logo -->
                    @if($game->awayTeam->image_path)
                        <img src="{{ asset('storage/' . $game->awayTeam->image_path) }}" 
                             class="w-14 h-14 object-contain shrink-0 drop-shadow-sm {{ ($game->status === 'finished' && $game->away_team_score < $game->local_team_score) ? 'opacity-70' : '' }}" 
                             alt="visitante" onerror="this.style.display='none'">
                    @else
                        <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                            {{ substr($game->awayTeam->name, 0, 1) }}
                        </div>
                    @endif

                    <!-- Nombre -->
                    <span class="font-bold text-sm truncate pr-2 {{ ($game->status === 'finished' && $game->away_team_score < $game->local_team_score) ? 'text-gray-500' : 'text-gray-800' }}">
                        {{ $game->awayTeam->name }}
                    </span>
                @endif
            </div>

            <!-- Derecha: Marcador -->
            <div class="text-right min-w-[50px] ml-2">
                <span class="text-3xl font-black font-mono {{ ($game->status === 'finished' && $game->away_team_score > $game->local_team_score) ? 'text-green-600' : 'text-gray-300' }}">
                    {{ $game->away_team_score ?? '-' }}
                </span>
            </div>
        </div>

    </div>
</div>