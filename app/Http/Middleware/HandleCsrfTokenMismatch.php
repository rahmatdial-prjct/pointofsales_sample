<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleCsrfTokenMismatch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // If it's a logout request and CSRF token is expired, just redirect to login
            if ($request->is('logout') || $request->route()->getName() === 'logout') {
                return redirect()->route('login')->with('info', 'Session telah berakhir. Silakan login kembali.');
            }
            
            // For other requests, redirect back with error
            return redirect()->back()->with('error', 'Session telah berakhir. Silakan refresh halaman dan coba lagi.');
        }
    }
}
