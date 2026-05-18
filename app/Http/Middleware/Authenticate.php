<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('users_id')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login terlebih dahulu'], 401);
            }
            return redirect('/login');
        }

        return $next($request);
    }
}
