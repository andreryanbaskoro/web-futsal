<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->peran;
        \Log::info('User role: ' . $userRole);

        if (!in_array(
            strtolower($userRole),
            array_map('strtolower', $roles)
        )) {
            \Log::warning('Akses ditolak untuk role: ' . $userRole);
            abort(403, 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
