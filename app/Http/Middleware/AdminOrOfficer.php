<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOrOfficer
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOfficer())) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses tidak diizinkan');
    }
}