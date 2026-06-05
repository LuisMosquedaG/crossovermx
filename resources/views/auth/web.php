<?php

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- Rutas para la gestión de torneos ---
Route::resource('tournaments', TournamentController::class)->middleware(['auth', 'verified']);
Route::post('/tournaments/generate-calendar', [TournamentController::class, 'generateCalendar'])->middleware(['auth', 'verified'])->name('tournaments.generateCalendar');
Route::get('/tournaments/{tournament}/schedule', [TournamentController::class, 'showSchedule'])->name('tournaments.schedule')->middleware(['auth', 'verified']);
Route::get('/tournaments/{tournament}/settings', [TournamentController::class, 'getCalendarSettings'])->name('tournaments.getSettings')->middleware(['auth', 'verified']);
Route::delete('/tournaments/{tournament}/calendar', [TournamentController::class, 'deleteCalendar'])->name('tournaments.deleteCalendar')->middleware(['auth', 'verified']);
Route::get('/tournaments/{tournament}/standings', [TournamentController::class, 'showStandings'])->name('tournaments.standings')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de partidos ---
Route::get('/games/{game}/details', [TournamentController::class, 'getGameDetails'])->name('games.details')->middleware(['auth', 'verified']);
Route::post('/games/save-starting-players', [TournamentController::class, 'saveStartingPlayers'])->name('games.saveStartingPlayers')->middleware(['auth', 'verified']);
Route::get('/games/{game}/live', [TournamentController::class, 'showLiveGame'])->name('games.live')->middleware(['auth', 'verified']);
Route::post('/games/record-action', [GameController::class, 'recordAction'])->name('games.recordAction')->middleware(['auth', 'verified']);
Route::post('/games/update-period', [GameController::class, 'updatePeriod'])->name('games.updatePeriod')->middleware(['auth', 'verified']);
Route::post('/games/update-timer', [GameController::class, 'updateTimer'])->name('games.updateTimer')->middleware(['auth', 'verified']);
Route::post('/games/finish-game', [GameController::class, 'finishGame'])->name('games.finishGame')->middleware(['auth', 'verified']);
Route::post('/games/next-period', [GameController::class, 'nextPeriod'])->name('games.nextPeriod')->middleware(['auth', 'verified']);
Route::get('/games/{game}/stats', [GameController::class, 'showStats'])->name('games.stats')->middleware(['auth', 'verified']);
Route::get('/games/{game}/bench-players', [GameController::class, 'getBenchPlayers'])->name('games.bench')->middleware(['auth', 'verified']);
Route::post('/games/substitute', [GameController::class, 'substitutePlayer'])->name('games.substitute')->middleware(['auth', 'verified']);
Route::post('/games/{game}/cancel', [GameController::class, 'cancelGame'])->name('games.cancel')->middleware(['auth', 'verified']);
Route::post('/games/{game}/assign-referee', [GameController::class, 'assignReferee'])->name('games.assignReferee')->middleware(['auth', 'verified']);
Route::get('/games/{game}/comments', [GameController::class, 'getComments'])->name('games.comments')->middleware(['auth', 'verified']);
Route::post('/games/{game}/comments', [GameController::class, 'storeComment'])->name('games.storeComment')->middleware(['auth', 'verified']);
Route::post('/games/{game}/auto-finish-suspended', [GameController::class, 'autoFinishSuspended'])->name('games.auto-finish');


// Rutas para gestión de reglamentos
Route::get('/tournaments/{tournament}/rules', [TournamentController::class, 'getRules'])->name('tournaments.getRules')->middleware(['auth', 'verified']);
Route::post('/tournaments/{tournament}/rules', [TournamentController::class, 'updateRules'])->name('tournaments.updateRules')->middleware(['auth', 'verified']);
Route::post('/teams/{team}/accept-contract', [TeamController::class, 'acceptContract'])->name('teams.acceptContract')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de jugadores ---
Route::resource('players', PlayerController::class)->middleware(['auth', 'verified']);
Route::get('/teams/{team}/players/json', [PlayerController::class, 'getPlayersByTeamJson'])->name('players.byTeamJson')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de equipos ---
Route::resource('teams', TeamController::class)->middleware(['auth', 'verified']);
Route::post('/tournaments/suspend-player', [TournamentController::class, 'suspendPlayer'])->name('tournaments.suspend-player');
Route::post('/tournaments/suspend-team', [TournamentController::class, 'suspendTeam'])->name('tournaments.suspend-team');
Route::get('/teams/{team}/stats', [TeamController::class, 'stats'])->name('teams.stats')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de canchas ---
Route::resource('courts', CourtController::class)->middleware(['auth', 'verified']);

// --- Rutas de Usuarios (Gestión) ---
// IMPORTANTE: Primero ponemos las rutas específicas antes del Resource
Route::get('/users/arbitros-json', [UserController::class, 'getRefereesJson'])->name('users.arbitros-json')->middleware(['auth', 'verified']);
// Después el recurso general
Route::resource('users', UserController::class)->middleware(['auth', 'verified']);

// --- Rutas de Perfil ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';