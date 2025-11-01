<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || !$request->user()->isActive()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;
        
        if ($role === 'director' && !$request->user()->isDirector()) {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan. Diperlukan role Direktur.');
        }
        
        if ($role === 'manager' && !$request->user()->isManager()) {
            return redirect()->route('login')->with('error', 'Akses tidak diizinkan. Diperlukan role Manajer.');
        }
        
        if ($role === 'employee' && !$request->user()->isEmployee()) {
            return redirect()->route('login')->with('error', 'Unauthorized access. Employee role required.');
        }

        return $next($request);
    }
} 