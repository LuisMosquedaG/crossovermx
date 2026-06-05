<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;       // <--- AGREGAR ESTO
use App\Models\Role;        // <--- AGREGAR ESTO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <--- AGREGAR ESTO
use Illuminate\Support\Facades\Hash; // <--- AGREGAR ESTO

class ClientController extends Controller
{
public function __construct()
{
    $this->middleware(function ($request, $next) {
        // Si NO está logueado O NO tiene el rol "Master Admin"
        if (!auth()->check() || !auth()->user()->hasRole('Master Admin')) {
            abort(403, 'No tienes permiso para acceder al Panel de Control.');
        }
        return $next($request);
    });
}

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $clientsQuery = Client::query();

        // Lógica de búsqueda
        if ($search) {
            $clientsQuery->where('name', 'like', "%{$search}%")
                         ->orWhere('contact_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        }

        $clients = $clientsQuery->paginate(10)->appends(['search' => $search]);
        
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        // 1. Validar que el prefijo sea obligatorio para crear los emails
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'contact_name' => 'nullable|string|max:255',
            'prefix' => 'required|string|max:20|unique:clients,prefix', // Prefix único
            'logo' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'email', 'phone', 'contact_name', 'prefix']);

        // Manejo de imagen
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('clients_logos', 'public');
        }

        // 2. INICIO DE TRANSACCIÓN: Todo o nada
        DB::transaction(function () use ($data) {
            
            // Crear el Cliente
            $client = Client::create($data);

            // 3. Buscar IDs de los roles
            // Asegúrate de que estos roles existan en tu BD
            $roleSuperAdmin = Role::where('name', 'Super Admin')->first();
            $roleAdmin = Role::where('name', 'Admin')->first();

            // Si los roles no existen, lanzamos un error para que nada se guarde
            if (!$roleSuperAdmin || !$roleAdmin) {
                throw new \Exception('Error: Los roles "Super Admin" y "Admin" no existen en el sistema.');
            }

            // 4. Crear Usuario Super Admin del cliente
            User::create([
                'name' => 'Super Admin ' . $client->name,
                'email' => 'sadmin@' . $client->prefix, // Ej: sadmin@cdmx
                'password' => Hash::make('paso1234'),
                'role_id' => $roleSuperAdmin->id,
                'client_id' => $client->id,
                'email_verified_at' => now()
            ]);

            // 5. Crear Usuario Admin del cliente
            User::create([
                'name' => 'Admin ' . $client->name,
                'email' => 'admin@' . $client->prefix, // Ej: admin@cdmx
                'password' => Hash::make('paso1234'),
                'role_id' => $roleAdmin->id,
                'client_id' => $client->id,
                'email_verified_at' => now()
            ]);
        });

        return redirect()->route('clients.index')->with('message', 'Cliente y usuarios creados exitosamente.');
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'contact_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048'
        ]);

         $data = $request->only(['name', 'email', 'phone', 'contact_name', 'prefix']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('clients_logos', 'public');
        }

        $client->update($data);

        return redirect()->route('clients.index')->with('message', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('message', 'Cliente eliminado exitosamente.');
    }
}