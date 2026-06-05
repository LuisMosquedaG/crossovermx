<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\StrengthController;

Route::post('/aceptar-terminos', [TermsController::class, 'accept'])->name('terms.accept');

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
Route::post('/tournaments/{tournament}/generate-second-round', [TournamentController::class, 'generateSecondRound'])->name('tournaments.secondRound')->middleware(['auth', 'verified']);
Route::post('/tournaments/{tournament}/generate-elimination', [TournamentController::class, 'generateElimination'])->name('tournaments.elimination')->middleware(['auth', 'verified']);
Route::post('/tournaments/{tournament}/update-progression', [TournamentController::class, 'updateProgression'])->name('tournaments.update-progression');
Route::post('/tournaments/{tournament}/swap-global', [TournamentController::class, 'swapTeamsGlobal'])->name('tournaments.swap-global');

// --- Rutas para la gestión de partidos ---
// CORRECCIÓN: Rutas movidas y actualizadas para usar GameController
Route::get('/games/{game}/live', [GameController::class, 'showLiveGame'])->name('games.live')->middleware(['auth', 'verified']);
Route::get('/games/{game}/details', [GameController::class, 'getGameDetails'])->name('games.details')->middleware(['auth', 'verified']);
Route::post('/games/save-starting-players', [GameController::class, 'saveStartingPlayers'])->name('games.saveStartingPlayers')->middleware(['auth', 'verified']);

Route::post('/games/record-action', [GameController::class, 'recordAction'])->name('games.recordAction')->middleware(['auth', 'verified']);
Route::post('/games/update-period', [GameController::class, 'updatePeriod'])->name('games.updatePeriod')->middleware(['auth', 'verified']);
Route::post('/games/update-timer', [GameController::class, 'updateTimer'])->name('games.updateTimer')->middleware(['auth', 'verified']);
Route::post('/games/finish-game', [GameController::class, 'finishGame'])->name('games.finishGame')->middleware(['auth', 'verified']);
Route::post('/games/next-period', [GameController::class, 'nextPeriod'])->name('games.nextPeriod')->middleware(['auth', 'verified']);
Route::post('/games/start-overtime', [GameController::class, 'startOvertime'])->name('games.startOvertime')->middleware(['auth', 'verified']);
Route::get('/games/{game}/stats', [GameController::class, 'showStats'])->name('games.stats')->middleware(['auth', 'verified']);
Route::get('/games/{game}/bench-players', [GameController::class, 'getBenchPlayers'])->name('games.bench')->middleware(['auth', 'verified']);
Route::post('/games/substitute', [GameController::class, 'substitutePlayer'])->name('games.substitute')->middleware(['auth', 'verified']);
Route::post('/games/{game}/cancel', [GameController::class, 'cancelGame'])->name('games.cancel')->middleware(['auth', 'verified']);
Route::post('/games/{game}/assign-referee', [GameController::class, 'assignReferee'])->name('games.assignReferee')->middleware(['auth', 'verified']);
Route::get('/games/{game}/comments', [GameController::class, 'getComments'])->name('games.comments')->middleware(['auth', 'verified']);
Route::post('/games/{game}/comments', [GameController::class, 'storeComment'])->name('games.storeComment')->middleware(['auth', 'verified']);
Route::post('/games/{game}/auto-finish-suspended', [GameController::class, 'autoFinishSuspended'])->name('games.auto-finish');
Route::get('/games/{game}/status', [GameController::class, 'getGameStatus'])->name('games.status');
Route::post('/games/undo-last-action', [GameController::class, 'undoLastAction']);
Route::post('/games/start-overtime', [GameController::class, 'startOvertime'])->name('games.startOvertime')->middleware(['auth', 'verified']);
Route::post('/games/add-compensation-time', [GameController::class, 'addCompensationTime'])->name('games.addCompensationTime')->middleware(['auth', 'verified']);
Route::resource('games', GameController::class)->middleware(['auth', 'verified']);

// Rutas para gestión de reglamentos
Route::get('/tournaments/{tournament}/rules', [TournamentController::class, 'getRules'])->name('tournaments.getRules')->middleware(['auth', 'verified']);
Route::post('/tournaments/{tournament}/rules', [TournamentController::class, 'updateRules'])->name('tournaments.updateRules')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de equipos ---
Route::get('/teams/{team}/schedules', [TeamController::class, 'getSchedules'])->name('teams.getSchedules');
Route::post('/teams/{team}/schedules', [TeamController::class, 'updateSchedules'])->name('teams.schedules');
Route::resource('teams', TeamController::class)->middleware(['auth', 'verified']);
Route::post('/teams/{team}/accept-contract', [TeamController::class, 'acceptContract'])->name('teams.acceptContract')->middleware(['auth', 'verified']);
Route::get('/teams/{team}/stats', [TeamController::class, 'stats'])->name('teams.stats')->middleware(['auth', 'verified']);
Route::post('/teams/strengths/store', [TeamController::class, 'storeStrength'])->name('teams.strengths.store');

// CORRECCIÓN: Rutas de suspensión movidas lógicamente a la gestión de juegos/partidos, apuntando a GameController
Route::post('/tournaments/suspend-player', [GameController::class, 'suspendPlayer'])->name('tournaments.suspend-player')->middleware(['auth', 'verified']);
Route::post('/tournaments/suspend-team', [GameController::class, 'suspendTeam'])->name('tournaments.suspend-team')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de jugadores ---
Route::resource('players', PlayerController::class)->middleware(['auth', 'verified']);
Route::get('/teams/{team}/players/json', [PlayerController::class, 'getPlayersByTeamJson'])->name('players.byTeamJson')->middleware(['auth', 'verified']);

// --- Rutas para la gestión de canchas ---
Route::resource('courts', CourtController::class)->middleware(['auth', 'verified']);
Route::get('/courts/{court}/schedules', [CourtController::class, 'getSchedules'])->name('courts.getSchedules');
Route::post('/courts/{court}/schedules', [CourtController::class, 'updateSchedules'])->name('courts.schedules');

// Panel de Control (Solo accesible por Master Admin)
Route::resource('clients', ClientController::class)->names('clients');

// --- Rutas de Usuarios (Gestión) ---
// IMPORTANTE: Definimos rutas específicas ANTES del resource para que tengan prioridad
Route::get('/users/arbitros-json', [UserController::class, 'getRefereesJson'])->name('users.arbitros-json')->middleware(['auth', 'verified']);
Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore')->middleware(['auth', 'verified']);

// Resource para el CRUD completo (Create, Read, Update, Delete/SoftDelete)
// Al tener el middleware 'auth', cualquier usuario logueado puede intentar acceder.
// La lógica para ocultar/mostrar el Super Admin está en el Controlador, no aquí.
Route::resource('users', UserController::class)->middleware(['auth', 'verified']);

// Rutas para gestión de Fuerzas
Route::resource('strengths', StrengthController::class)->middleware(['auth', 'verified']);

// --- Rutas de Perfil ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/aceptar-terminos', [TermsController::class, 'accept'])->name('terms.accept');

require __DIR__.'/auth.php';