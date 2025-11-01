<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // If it's a logout request and CSRF token is expired, redirect to login
            if ($request->is('logout') || $request->route()->getName() === 'logout') {
                return redirect()->route('login')->with('info', 'Session telah berakhir. Silakan login kembali.');
            }
            
            // For other requests, throw the exception normally
            throw $e;
        }
    }
}
