<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si hay alguien logueado
        if (Auth::check()) {
            
            // 2. Verificamos si su rol es 'admin'
            if (Auth::user()->role === 'admin') {
                return $next($request); // Lo dejamos pasar
            }

            // Si está logueado pero NO es admin, lo pateamos al inicio
            return redirect('/index')->with('error', 'Acceso denegado. Área exclusiva para administradores.');
        }

        // Si ni siquiera está logueado, lo mandamos a la pantalla de Login
        return redirect('/login')->with('error', 'Debes iniciar sesión para acceder.');
    }
}