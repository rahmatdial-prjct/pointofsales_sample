<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Director can access all branches
        if ($user->isDirector()) {
            return $next($request);
        }

        // For manager and employee, check if they're accessing their own branch
        $branchId = $request->route('branch') ?? $request->input('branch_id');
        
        if ($branchId && $user->branch_id != $branchId) {
            abort(403, 'Anda hanya dapat mengakses data dari cabang yang ditugaskan.');
        }

        return $next($request);
    }
} 