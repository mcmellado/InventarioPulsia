<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPuesto
{
    /**
     
Manejar una solicitud entrante.*/
  public function handle(Request $request, Closure $next){$user = Auth::user();

        // Si es admin => acceso total
        if ($user->puesto === 'admin') {
            return $next($request);
        }

        // Rutas que un usuario normal SÍ puede abrir:
        // - su propia ruta /puestos/{su-puesto}
        // - logout
        $allowedRoutes = [
            'puestos.porPuesto',
            'logout'
        ];

        // Si está en la ruta del puesto pero NO coincide con el suyo, redirigir a su propio puesto
        if ($request->routeIs('puestos.porPuesto')) {
            if ($request->route('nombre') !== $user->puesto) {
                return redirect()->route('puestos.porPuesto', ['nombre' => $user->puesto]);
            }
            return $next($request);
        }
        // Si intenta acceder a otra ruta, redirigirlo siempre a su puesto
        if (!in_array($request->route()->getName(), $allowedRoutes)) {
            return redirect()->route('puestos.porPuesto', ['nombre' => $user->puesto]);
        }

        return $next($request);
    }
}