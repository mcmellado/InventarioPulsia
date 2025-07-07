<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mostrar formulario para crear usuario
    public function create()
    {
        return view('users.create');  // Asegúrate que la vista exista en resources/views/users/create.blade.php
    }

    // Guardar usuario nuevo
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user' => 'required|string|unique:users,user|max:255',
            'password' => 'required|string|confirmed|min:6',
        ]);

        // Crear usuario (asegúrate de importar el modelo User)
        \App\Models\User::create([
            'name' => $validated['name'],
            'user' => $validated['user'],
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('users.create')->with('success', 'Usuario creado correctamente.');

    }
}
