<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        ]);

     
        \App\Models\User::create([
            'name' => $validated['name'],
            'user' => $validated['user'],
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('users.create')->with('success', 'Usuario creado correctamente.');

    }
}
