<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use App\Models\Team;
use App\Services\ScoresheetScannerService;

$game = Game::find(7693);
if (!$game) {
    echo "Game 7693 not found by ID. Searching...\n";
    $team = Team::where('name', 'like', '%Lagartos%')->first();
    if ($team) {
        $game = Game::where('local_team_id', $team->id)->orWhere('away_team_id', $team->id)->first();
    }
}

if (!$game) {
    // If no game in DB, create or mock
    echo "No matching game in DB. Creating dummy Game model for test...\n";
    $game = new Game();
    $game->id = 7693;
}

echo "Testing with Game #{$game->id}: " . ($game->localTeam->name ?? 'Local') . " vs " . ($game->awayTeam->name ?? 'Away') . "\n";

$scanner = app(ScoresheetScannerService::class);
$imagePath = base_path('Hoja de anotaciones.jpg');

$startTime = microtime(true);
$result = $scanner->scan($imagePath, $game);
$elapsed = round(microtime(true) - $startTime, 2);

echo "\n============================================\n";
echo "RESULTADOS DEL ESCANEO (Tiempo: {$elapsed}s):\n";
echo "============================================\n";
echo "Success: " . ($result['success'] ? 'true' : 'false') . "\n";
echo "Marcador: " . ($game->localTeam->name ?? 'Local') . " " . $result['local_team_score'] . " - " . $result['away_team_score'] . " " . ($game->awayTeam->name ?? 'Away') . "\n";
echo "Notas: " . $result['confidence_notes'] . "\n\n";

echo "JUGADORES LOCALES:\n";
foreach ($result['local_players'] as $p) {
    echo "  #{$p['jersey_number']} {$p['player_name']}: {$p['points']} pts, {$p['fouls']} faltas\n";
}

echo "\nJUGADORES VISITANTES:\n";
foreach ($result['away_players'] as $p) {
    echo "  #{$p['jersey_number']} {$p['player_name']}: {$p['points']} pts, {$p['fouls']} faltas\n";
}
