<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
 public function index(Request $request)
    {
        // Obtenemos el término de búsqueda, si existe
        $search = $request->input('search');

        // Iniciamos la consulta
        $query = Court::query();

                // --- NUEVO: FILTRO POR CLIENTE ---
        // Solo mostrar canchas del cliente del usuario logueado
        if (auth()->check() && auth()->user()->client_id) {
            $query->where('client_id', auth()->user()->client_id);
        }
        // ------------------------------------

        // Si hay búsqueda, filtramos por nombre, ubicación o superficie
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('surface_type', 'like', "%{$search}%");
        }

        // Paginamos los resultados (20 por página) y mantenemos el parámetro de búsqueda
        $courts = $query->orderBy('name')->paginate(15)->appends(['search' => $search]);

        return view('courts.index', compact('courts', 'search'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'surface_type' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

                // --- NUEVO: ASIGNAR CLIENTE DEL USUARIO LOGUEADO ---
        if (auth()->check() && auth()->user()->client_id) {
            $data['client_id'] = auth()->user()->client_id;
        }
        // --------------------------------------------------

        // Manejo de la subida de la imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courts', 'public');
            $data['image_path'] = $imagePath;
        }

        Court::create($data);

        // Si la petición es de AJAX (desde JavaScript), devuelve JSON
        if ($request->ajax()) {
            return response()->json(['message' => 'Cancha creada exitosamente.']);
        }

        // Si es una petición normal, redirige como siempre
        return redirect()->route('courts.index')->with('message', 'Cancha creada exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Court $court)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'surface_type' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

                // --- NUEVO: ASIGNAR CLIENTE AL ACTUALIZAR (Por seguridad) ---
        if (auth()->check() && auth()->user()->client_id) {
            $data['client_id'] = auth()->user()->client_id;
        }
        // ----------------------------------------------------------

        // Manejo de la subida de la imagen al editar
        if ($request->hasFile('image')) {
            // Opcional: Eliminar la imagen anterior si existe
            // if ($court->image_path) {
            //     Storage::disk('public')->delete($court->image_path);
            // }
            $imagePath = $request->file('image')->store('courts', 'public');
            $data['image_path'] = $imagePath;
        }

        $court->update($data);

        // Si la petición es de AJAX (desde JavaScript), devuelve JSON
        if ($request->ajax()) {
            return response()->json(['message' => 'Cancha actualizada exitosamente.']);
        }

        // Si es una petición normal, redirige como siempre
        return redirect()->route('courts.index')->with('message', 'Cancha actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Court $court)
    {
        $court->delete();

        return redirect()->route('courts.index')->with('message', 'Cancha eliminada exitosamente.');
    }

    public function updateSchedules(Request $request, Court $court)
    {
        // --- CORRECCIÓN: Si viene un string vacío "", lo convertimos a array vacío [] ---
        // Esto evita el error de validación de Laravel cuando borras los datos
        if ($request->has('schedules') && $request->input('schedules') === "") {
            $request->merge(['schedules' => []]);
        }

        $request->validate([
            // Usamos 'sometimes' para que pase si la llave no existe o es null
            'schedules' => 'sometimes|array', 
            'schedules.*.day' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        // Eliminamos los horarios anteriores de esta cancha
        // (Si schedules está vacío, esto simplemente limpia todo)
        $court->schedules()->delete();

        // Creamos los nuevos horarios solo si el array no está vacío
        if (!empty($request->schedules)) {
            foreach ($request->schedules as $schedule) {
                $court->schedules()->create([
                    'day_of_week' => $schedule['day'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            }
        }

        return response()->json(['message' => 'Horarios actualizados exitosamente.']);
    }

    /**
    * Obtiene los horarios de una cancha en formato JSON.
    */
    public function getSchedules(Court $court)
    {
        // Devolvemos la colección de horarios
        return response()->json($court->schedules);
    }
}