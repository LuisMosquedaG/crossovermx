<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $viewTrash = $request->input('trashed') == 'yes';

    $usersQuery = User::with('role');

    // --- NUEVA LÓGICA: Excluir Super Admin del listado ---
    $usersQuery->whereDoesntHave('role', function($query) {
        $query->where('name', 'Super Admin');
    });
    // -----------------------------------------------------

           // --- NUEVO: FILTRO POR CLIENTE ---
        // Solo mostrar usuarios del mismo cliente (a menos que sea Master Admin)
        if (Auth::check() && Auth::user()->client_id && !Auth::user()->hasRole('Master Admin')) {
            $usersQuery->where('client_id', Auth::user()->client_id);
        }
        // ---------------------------------

    // Si queremos ver solo la papelera
    if ($viewTrash) {
        $usersQuery->onlyTrashed();
    }

    // --- LÓGICA DE BÚSQUEDA ---
    if ($search) {
        $usersQuery->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhereHas('role', function($r) use ($search) {
                  $r->where('name', 'like', "%{$search}%");
              });
        });
    }
    // --------------------------------

    $users = $usersQuery->paginate(15)->appends(['search' => $search, 'trashed' => $viewTrash ? 'yes' : null]);

    return view('users.index', compact('users', 'search', 'viewTrash'));
}

    public function store(Request $request)
    {
        // 1. Validación de campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255', 
            'password' => 'required|string|min:8|confirmed', // Confirmed requiere campo password_confirmation
            'role' => 'nullable|string',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        // 2. Lógica del dominio (Cliente)
        $domain = optional(auth()->user()->client)->prefix;
        $usernamePart = trim($request->email);

        // 3. Validación: No permitir @ en el input
        if (str_contains($usernamePart, '@')) {
            // Si es petición JSON (Modal), devolvemos error 422
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Formato de correo inválido.',
                    'errors' => [
                        'email' => ['Por favor, solo escribe el usuario (ej. juan.perez). El dominio se agrega automáticamente.']
                    ]
                ], 422);
            }
            return back()->withErrors(['email' => 'Solo escribe el usuario (sin @).'])->withInput();
        }

        // 4. Construir correo final
        $finalEmail = $domain ? ($usernamePart . '@' . $domain) : $usernamePart;

        // 5. Verificar si existe (INCLUYENDO PAPELERA)
        $existingUser = \App\Models\User::withTrashed()->where('email', $finalEmail)->first();

        $user = null; // Definimos la variable vacía para evitar el error "Undefined variable"

        if ($existingUser) {
            // --- ESCENARIO A: El usuario está en la PAPELERA ---
            if ($existingUser->trashed()) {
                // Restauramos al usuario
                $existingUser->restore();
                
                // Actualizamos sus datos por si cambiaron (Nombre, Password, Rol)
                $existingUser->name = $request->name;
                $existingUser->password = Hash::make($request->password);
                
                if ($request->filled('role_id')) {
                    $existingUser->role_id = $request->role_id;
                } elseif ($request->filled('role')) {
                    $role = \App\Models\Role::where('name', $request->role)->first();
                    if ($role) $existingUser->role_id = $role->id;
                }

                $existingUser->save();
                $user = $existingUser; // Asignamos a $user para usarlo al final
            } 
            // --- ESCENARIO B: El usuario ESTÁ ACTIVO (Error real) ---
            else {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'El correo ya está en uso.',
                        'errors' => [
                            'email' => ['El usuario ' . $finalEmail . ' ya está activo en el sistema.']
                        ]
                    ], 422);
                }
                return back()->withErrors(['email' => 'El usuario ya existe.'])->withInput();
            }
        } else {
            // --- ESCENARIO C: El usuario NO EXISTE (Crear Nuevo) ---
            
            $roleId = null;
            if ($request->filled('role_id')) {
                $roleId = $request->role_id;
            } elseif ($request->filled('role')) {
                $role = \App\Models\Role::where('name', $request->role)->first();
                if ($role) $roleId = $role->id;
            }

            $clientId = null;
            if (Auth::check() && !Auth::user()->hasRole('Master Admin')) {
                $clientId = Auth::user()->client_id;
            }

            // CREACIÓN DEL USUARIO Y ASIGNACIÓN A VARIABLE
            $user = User::create([
                'name' => $request->name,
                'email' => $finalEmail,
                'password' => Hash::make($request->password),
                'role_id' => $roleId,
                'client_id' => $clientId
            ]);
        }

        // 6. Respuesta final
        if ($request->wantsJson()) {
            // Esto soluciona el error de JS y permite que el modal se actualice
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        return redirect()->route('users.index')->with('message', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255', // No validamos unique aquí, lo haremos abajo con lógica
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        // --- NUEVA LÓGICA DE ACTUALIZACIÓN CON HOMOLOGACIÓN DE DOMINIO ---
        $finalEmail = $request->email;

        // Si el usuario logueado pertenece a un cliente, forzamos el formato @prefijo
        if (auth()->check() && auth()->user()->client_id) {
            $domain = auth()->user()->client->prefix;
            
            // Extraemos solo la parte del usuario (todo lo que esté antes del @)
            // Usamos explode con límite 2 para evitar errores si el usuario no puso @
            $usernamePart = explode('@', $request->email)[0];
            
            // Reconstruimos el correo forzando el dominio del cliente
            $finalEmail = $usernamePart . '@' . $domain;

            // Verificamos unicidad EXCLUYENDO al propio usuario que estamos editando
            if (\App\Models\User::where('email', $finalEmail)->where('id', '!=', $user->id)->exists()) {
                return back()->withErrors(['email' => 'El usuario ' . $finalEmail . ' ya está registrado.'])->withInput();
            }
        } else {
            // Si es Master Admin (sin cliente), validamos unicidad normal
            if (\App\Models\User::where('email', $finalEmail)->where('id', '!=', $user->id)->exists()) {
                return back()->withErrors(['email' => 'El correo ' . $finalEmail . ' ya está registrado.'])->withInput();
            }
        }
        // --------------------------------------------------------------------------------

        $user->name = $request->name;
        $user->email = $finalEmail; // Guardamos el email homologado
        
        if ($request->filled('role_id')) {
            $user->role_id = $request->role_id;
        } elseif ($request->filled('role')) {
            $role = \App\Models\Role::where('name', $request->role)->first();
            if ($role) {
                $user->role_id = $role->id;
            }
        }

        // --- SEGURIDAD MANTENER CLIENT_ID ---
        if ($user->client_id && auth()->check() && !auth()->user()->hasRole('Master Admin')) {
             // Si el usuario ya tenía un client_id, lo mantenemos (seguridad)
        } else if (!$user->client_id && auth()->check() && auth()->user()->client_id) {
            // Si por alguna razón estaba null, se lo asignamos
            $user->client_id = auth()->user()->client_id;
        }
        // ---------------------------------------------------

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('users.index')->with('message', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // 1. Buscamos al usuario usando withTrashed() para incluir a los eliminados
        // findOrFail lanza error 404 SOLO si el ID no existe en absoluto en la BD
        $user = User::withTrashed()->findOrFail($id);

        // 2. Validación: No permitir autoeliminarse
        if ($user->id === auth()->id()) {
            // Si es una petición AJAX (modal rápido), devolvemos JSON
            if (request()->wantsJson()) {
                return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
            }
            return back()->withErrors(['No puedes eliminar tu propio usuario.']);
        }

        // 3. Lógica de eliminación
        if ($user->trashed()) {
            // Si ya está en la papelera -> BORRADO PERMANENTE
            $user->forceDelete();
            
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Usuario eliminado permanentemente.']);
            }
            return redirect()->route('users.index', ['trashed' => 'yes'])->with('message', 'Usuario eliminado permanentemente.');
        }

        // Si está activo -> SOFT DELETE (Enviar a papelera)
        $user->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Usuario enviado a la papelera.']);
        }
        return redirect()->route('users.index')->with('message', 'Usuario enviado a la papelera.');
    }

    // NUEVO MÉTODO PARA RESTAURAR
    public function restore($id)
    {
        // Buscamos el usuario incluso si está eliminado
        $user = User::withTrashed()->findOrFail($id);
        
        if ($user->trashed()) {
            $user->restore();
            return redirect()->route('users.index')->with('message', 'Usuario restaurado exitosamente.');
        }

        return redirect()->route('users.index');
    }
   
    public function getRefereesJson()
    {
        // Nota: Esto automáticamente excluirá los eliminados soft delete, 
        // lo cual es correcto para no asignar árbitros dados de baja a nuevos partidos.
        
        $query = User::whereHas('role', function($query) {
            $query->where('name', 'Arbitro');
        });

        // --- NUEVO: FILTRAR ÁRBITROS POR CLIENTE ---
        // Si soy admin de cliente, solo quiero árbitros de mi cliente
        if (Auth::check() && Auth::user()->client_id) {
            $query->where('client_id', Auth::user()->client_id);
        }
        // ----------------------------------------------

        $referees = $query->select(['id', 'name'])->get();

        return response()->json($referees);
    }
}