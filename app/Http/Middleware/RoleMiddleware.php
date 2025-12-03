<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Check if user has the required role
        // Adjust this based on your User model structure
        if (!$this->userHasRole($user, $role)) {
            // If user doesn't have the role, redirect to appropriate dashboard or abort
            if ($user->role) {
                return redirect($user->getDashboardRoute())->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
            }
            abort(403, 'Unauthorized. Required role: ' . $role);
        }

        return $next($request);
    }

    /**
     * Check if user has the specified role
     */
    private function userHasRole($user, string $role): bool
    {
        // Option 1: If you have a 'role' column in users table
        return $user->role === $role;
        
        // Option 2: If you have a different role structure, uncomment and modify:
        // return $user->hasRole($role);
    }
}