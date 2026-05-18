<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    /**
     * Pages & features accessible by role.
     */
    protected $permissions = [
        'admin' => ['dashboard', 'products', 'pos', 'inventory', 'reports', 'users'],
        'kasir' => ['pos'],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        // Check if user is logged in
        if (!Session::has('users_id')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu'], 401);
            }
            return redirect('/login');
        }

        // Check role if specified
        if ($role && Session::get('role') !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke fitur ini'], 403);
            }
            return redirect('/pos')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
