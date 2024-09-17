<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GerantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et s'il est de type 'Gérant'
        if (Auth::check() && Auth::user()->type_client === 'Gérant') {
            return $next($request);
        }

        // Rediriger si l'utilisateur n'est pas un gérant
        return redirect('/')->with('error', 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.');
    }
}
