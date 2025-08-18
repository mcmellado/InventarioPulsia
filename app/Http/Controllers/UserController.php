<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
 
    public function create()
    {
        return view('users.create');  
    }

 
    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user' => 'required|string|unique:users,user|max:255',
            'password' => 'required|string|confirmed|min:6',
            'puesto' => 'required|string|max:255',
        ]);

     
        \App\Models\User::create([
            'name' => $validated['name'],
            'user' => $validated['user'],
            'password' => bcrypt($validated['password']),
            'puesto' => $validated['puesto'],

        ]);

        return redirect()->route('users.create')->with('success', 'Usuario creado correctamente.');

    }

    public function index() {

        $user = Auth::user();

        $equipos = Equipo::with('puestoActual')->get;

        if ($user->puesto !== 'admin') {
            $equipos = $equipos->filter(function($equipo) use ($user) {
                return strtolower($equipo->puestoActual->nombre ?? '') === strtolower($user->puesto);     
            });
        }

        $equipoPorModelo =  $equipos->groupBy('modelo');
        
        return view('equipo.index', compact('equiposPorModelo'));
    }
}
