<?php

namespace App\Http\Controllers;

use App\Models\Strength;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StrengthController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $strengthsQuery = Strength::query();

        // Filtrar por cliente actual
        if (auth()->check() && auth()->user()->client_id) {
            $strengthsQuery->where('client_id', auth()->user()->client_id);
        }

        // Búsqueda
        if ($search) {
            $strengthsQuery->where('name', 'like', "%{$search}%");
        }

        $strengths = $strengthsQuery->orderBy('name')->paginate(10)->appends(['search' => $search]);

        return view('strengths.index', compact('strengths', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('strengths')->where(function ($query) {
                    return $query->where('client_id', auth()->user()->client_id);
                })
            ]
        ], [
            'name.unique' => 'Ya existe una fuerza con ese nombre para tu cuenta.'
        ]);

        Strength::create([
            'name' => $request->name,
            'client_id' => auth()->user()->client_id,
        ]);

        return redirect()->route('strengths.index')->with('message', 'Fuerza creada exitosamente.');
    }

    public function update(Request $request, Strength $strength)
    {
        // Verificar permiso
        if ($strength->client_id != auth()->user()->client_id) {
            abort(403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('strengths')->where(function ($query) {
                    return $query->where('client_id', auth()->user()->client_id);
                })->ignore($strength->id)
            ]
        ], [
            'name.unique' => 'Ya existe una fuerza con ese nombre para tu cuenta.'
        ]);

        $strength->update(['name' => $request->name]);

        return redirect()->route('strengths.index')->with('message', 'Fuerza actualizada exitosamente.');
    }

    public function destroy(Strength $strength)
    {
        // Verificar permiso
        if ($strength->client_id != auth()->user()->client_id) {
            abort(403);
        }

        // Verificar si hay equipos usando esta fuerza
        if ($strength->teams()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar esta fuerza porque hay equipos asignados a ella.']);
        }

        $strength->delete();

        return redirect()->route('strengths.index')->with('message', 'Fuerza eliminada exitosamente.');
    }
}