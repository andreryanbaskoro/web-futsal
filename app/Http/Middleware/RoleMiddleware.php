<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Menangani request dan memastikan role user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;
        \Log::info('User role: ' . $userRole);

        if (!in_array(strtolower(auth()->user()->role), array_map('strtolower', $roles))) {
            \Log::info('Akses ditolak untuk role: ' . auth()->user()->role);
            return abort(403, 'Anda tidak memiliki akses');
        }


        return $next($request);
    }
}
