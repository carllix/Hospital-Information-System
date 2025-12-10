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
     * @param  string|null  $bagian
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role, string $bagian = null): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Check if user has the required role
        if (!$this->userHasRole($user, $role, $bagian)) {
            // If user doesn't have the role, redirect to appropriate dashboard or abort
            if ($user->role) {
                return redirect($user->getDashboardRoute())->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
            }
            abort(403, 'Unauthorized. Required role: ' . $role);
        }

        return $next($request);
    }

    /**
     * Check if user has the specified role and bagian (for staf)
     */
    private function userHasRole($user, string $role, ?string $bagian = null): bool
    {
        // Check basic role
        if ($user->role !== $role) {
            return false;
        }

        // If role is 'staf' and bagian is specified, check the bagian
        if ($role === 'staf' && $bagian !== null) {
            // Get staf data related to this user
            $staf = $user->staf; // Assuming User has staf relationship

            if (!$staf || $staf->bagian !== $bagian) {
                return false;
            }
        }

        return true;

        // Option 2: If you have a different role structure, uncomment and modify:
        // return $user->hasRole($role);
    }
}
