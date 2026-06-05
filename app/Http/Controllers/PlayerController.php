<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
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
            // --- CAMBIO IMPORTANTE ---
            // ELIMINAMOS la búsqueda por 'rfc' y 'curp' usando LIKE
            // porque los datos están encriptados en la BD.
            // Mantenemos búsqueda por nombre, número, equipo, etc.
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('team', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $players = $query->orderBy('name')->paginate(15)->appends(['search' => $search]);
        
        $teams = Team::orderBy('name')->get();
        if (auth()->check() && auth()->user()->client_id) {
            $teams = $teams->where('client_id', auth()->user()->client_id);
        }

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
                    'integer',
                    Rule::unique('players')->where(function ($query) use ($request) {
                        return $query->where('team_id', $request->team_id);
                    })
                ],
                'position' => 'nullable|string|max:50',
                'gender' => 'nullable|string|in:hombre,mujer',
                'team_id' => 'required|exists:teams,id',
                'rfc' => 'nullable|string|max:13|unique:players,rfc',
                'curp' => [
                    'nullable',
                    'string',
                    'max:18',
                    Rule::unique('players')->where(function ($query) use ($request) {
                        return $query->where('team_id', $request->team_id);
                    })
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
                'curp.unique' => 'Este jugador ya está inscrito en este equipo.'
            ]);

            $data = $request->all();

            // 2. Lógica del Equipo y Cliente
            $team = Team::find($request->team_id);
            if ($team) {
                $data['client_id'] = $team->client_id;
            } else {
                // Devolvemos error JSON en lugar de redirección HTML
                return response()->json(['message' => 'El equipo seleccionado no es válido.'], 422);
            }

            // 3. Imagen
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('players', 'public');
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
            // AQUÍ CAPTURAMOS EL ERROR REAL Y LO DEVOLVEMOS AL FRONTEND
            return response()->json([
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Player $player)
    {
        // 1. Validación (Mantén tu validación existente)
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => [
                'required',
                'integer',
                Rule::unique('players')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->team_id);
                })->ignore($player->id)
            ],
            'position' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:hombre,mujer',
            'team_id' => 'required|exists:teams,id',
            'rfc' => 'nullable|string|max:13|unique:players,rfc,' . $player->id,
            'curp' => [
                'nullable',
                'string',
                'max:18',
                Rule::unique('players')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->team_id);
                })->ignore($player->id)
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
            'curp.unique' => 'Este jugador ya está inscrito en este equipo (mismo torneo/categoría).'
        ]);

        $data = $request->all();
        
        // Lógica de equipo/cliente
        $team = Team::find($request->team_id);
        if ($team) {
            $data['client_id'] = $team->client_id;
        }

        // Imagen
        if ($request->hasFile('image')) {
            if ($player->image_path) {
                Storage::disk('public')->delete($player->image_path);
            }
            $imagePath = $request->file('image')->store('players', 'public');
            $data['image_path'] = $imagePath;
        }

        // Actualizar
        $player->update($data);

        // --- CAMBIO CLAVE: RESPUESTA CONDICIONAL ---
        if ($request->wantsJson()) {
            // Si la petición es AJAX (nuestro modal), devolvemos JSON
            return response()->json([
                'success' => true, 
                'message' => 'Jugador actualizado exitosamente.'
            ]);
        }

        // Si la petición es normal (página de jugadores), redirigimos
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