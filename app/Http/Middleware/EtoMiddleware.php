<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EtoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        dd("Hiii !");
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login first.');
        }
        

        if (! Auth::user()->roles()->where('role_name', 'eto')->exists()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
