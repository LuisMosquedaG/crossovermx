<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsController extends Controller
{
    public function accept(Request $request)
    {
        $user = Auth::user();
        
        // Guardamos la fecha actual y la IP del cliente
        $user->terms_accepted_at = now();
        $user->terms_accepted_ip = $request->ip();
        $user->save();

        return response()->json(['success' => true]);
    }
}