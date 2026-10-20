<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Services\TenantStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Player::with(['team.tournament']);

        if (auth()->check() && auth()->user()->client_id) {
            $query->where('client_id', auth()->user()->client_id);
        }

        if ($search) {
            // Mantenemos búsqueda por nombre, número, equipo, etc.
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('team', function ($tQuery) use ($search) {
                      $tQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $players = $query->orderBy('name')->paginate(15)->appends(['search' => $search]);
        
        $teamsQuery = Team::orderBy('name');
        if (auth()->check() && auth()->user()->client_id) {
            $teamsQuery->where('client_id', auth()->user()->client_id);
        }
        $teams = $teamsQuery->get();

        return view('players.index', compact('players', 'teams', 'search'));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validación
            $request->validate([
                'name' => 'required|string|max:255',            
                'number' => [
                    'required', 
                    'string',
                    'max:3',
                    'regex:/^[0-9]{1,3}$/',
                    Rule::unique('players')->where(function ($query) use ($request) {
                        return $query->where('team_id', $request->team_id);
                    })
                ],
                'position' => 'nullable|string|max:50',
                'gender' => 'nullable|string|in:hombre,mujer',
                'team_id' => 'required|exists:teams,id',
                'rfc' => [
                    'nullable',
                    'string',
                    'max:13',
                    function ($attribute, $value, $fail) {
                        if (empty($value)) return;
                        $cleanRfc = strtoupper(trim($value));
                        $clientId = auth()->user()?->client_id;
                        $players = Player::when($clientId, fn($q) => $q->where('client_id', $clientId))
                            ->whereNotNull('rfc')
                            ->get(['id', 'rfc']);
                        foreach ($players as $p) {
                            if ($p->rfc && strtoupper(trim($p->rfc)) === $cleanRfc) {
                                $fail('El RFC ya está registrado para otro jugador.');
                                break;
                            }
                        }
                    }
                ],
                'curp' => [
                    'nullable',
                    'string',
                    'max:18',
                    function ($attribute, $value, $fail) use ($request) {
                        if (empty($value)) return;
                        $cleanCurp = strtoupper(trim($value));
                        $teamPlayers = Player::where('team_id', $request->team_id)
                            ->whereNotNull('curp')
                            ->get(['id', 'curp']);
                        foreach ($teamPlayers as $tp) {
                            if ($tp->curp && strtoupper(trim($tp->curp)) === $cleanCurp) {
                                $fail('Este jugador ya está inscrito en este equipo.');
                                break;
                            }
                        }
                    }
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'blood_type' => 'nullable|string|max:5',
                'status' => 'required|string|in:active,suspended,expelled',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_relationship' => 'nullable|string|max:100',
                'emergency_contact_address' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
            ], [
                'number.unique' => 'El número de camiseta ya está en uso en este equipo.',
                'number.regex' => 'El número de camiseta debe ser un valor numérico de hasta 3 dígitos.',
                'curp.unique' => 'Este jugador ya está inscrito en este equipo.'
            ]);

            $data = $request->all();

            // 2. Lógica del Equipo y Cliente
            $team = Team::find($request->team_id);
            if ($team) {
                $data['client_id'] = $team->client_id;
            } else {
                return response()->json(['message' => 'El equipo seleccionado no es válido.'], 422);
            }

            $clientId = $data['client_id'] ?? (auth()->check() ? auth()->user()->client_id : null);

            // 3. Imagen aislada por cliente
            if ($request->hasFile('image')) {
                $targetPath = TenantStorageService::path('players', $clientId);
                $imagePath = $request->file('image')->store($targetPath, 'public');
                $data['image_path'] = $imagePath;
            }

            // 4. Crear Jugador
            Player::create($data);
            $newPlayer = Player::latest()->first();

            return response()->json([
                'success' => true, 
                'message' => 'Jugador creado.',
                'player' => $newPlayer 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => [
                'required', 
                'string',
                'max:3',
                'regex:/^[0-9]{1,3}$/',
                Rule::unique('players')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->team_id);
                })->ignore($player->id)
            ],
            'position' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:hombre,mujer',
            'team_id' => 'required|exists:teams,id',
            'rfc' => [
                'nullable',
                'string',
                'max:13',
                function ($attribute, $value, $fail) use ($player) {
                    if (empty($value)) return;
                    $cleanRfc = strtoupper(trim($value));
                    $clientId = auth()->user()?->client_id;
                    $players = Player::when($clientId, fn($q) => $q->where('client_id', $clientId))
                        ->where('id', '!=', $player->id)
                        ->whereNotNull('rfc')
                        ->get(['id', 'rfc']);
                    foreach ($players as $p) {
                        if ($p->rfc && strtoupper(trim($p->rfc)) === $cleanRfc) {
                            $fail('El RFC ya está registrado para otro jugador.');
                            break;
                        }
                    }
                }
            ],
            'curp' => [
                'nullable',
                'string',
                'max:18',
                function ($attribute, $value, $fail) use ($request, $player) {
                    if (empty($value)) return;
                    $cleanCurp = strtoupper(trim($value));
                    $teamPlayers = Player::where('team_id', $request->team_id)
                        ->where('id', '!=', $player->id)
                        ->whereNotNull('curp')
                        ->get(['id', 'curp']);
                    foreach ($teamPlayers as $tp) {
                        if ($tp->curp && strtoupper(trim($tp->curp)) === $cleanCurp) {
                            $fail('Este jugador ya está inscrito en este equipo.');
                            break;
                        }
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blood_type' => 'nullable|string|max:5',
            'status' => 'required|string|in:active,suspended,expelled',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_address' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ], [
            'number.unique' => 'El número de camiseta ya está en uso en este equipo.',
            'number.regex' => 'El número de camiseta debe ser un valor numérico de hasta 3 dígitos.',
            'curp.unique' => 'Este jugador ya está inscrito en este equipo (mismo torneo/categoría).'
        ]);

        $data = $request->all();
        
        // Lógica de equipo/cliente
        $clientId = $player->client_id;
        $team = Team::find($request->team_id);
        if ($team) {
            $data['client_id'] = $team->client_id;
            $clientId = $team->client_id;
        }
        $clientId = $clientId ?? (auth()->check() ? auth()->user()->client_id : null);

        // Imagen aislada por cliente
        if ($request->hasFile('image')) {
            if ($player->image_path && Storage::disk('public')->exists($player->image_path)) {
                Storage::disk('public')->delete($player->image_path);
            }
            $targetPath = TenantStorageService::path('players', $clientId);
            $imagePath = $request->file('image')->store($targetPath, 'public');
            $data['image_path'] = $imagePath;
        }

        // Actualizar
        $player->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Jugador actualizado exitosamente.'
            ]);
        }

        return redirect()->route('players.index')->with('message', 'Jugador actualizado exitosamente.');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('message', 'Jugador eliminado exitosamente.');
    }

    public function getPlayersByTeamJson(Team $team)
    {
        return response()->json($team->players()->orderBy('name')->with(['team.tournament'])->get());
    }
}
