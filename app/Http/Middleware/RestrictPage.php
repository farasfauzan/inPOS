<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class RestrictPage
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('users_id')) {
            return redirect('/login');
        }

        $role = Session::get('role');
        $currentPath = $request->path();

        // Kasir can only access POS page
        if ($role === 'kasir') {
            $allowedPages = ['pos', 'api/pos', 'api/transactions', 'api/auth/session', 'logout', 'api/products'];

            $isAllowed = false;
            foreach ($allowedPages as $page) {
                if (strpos($currentPath, $page) === 0) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                // Redirect kasir to POS if trying to access restricted pages
                if (strpos($currentPath, 'dashboard') === 0) {
                    return redirect('/pos');
                }
                return redirect('/pos')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }

        return $next($request);
    }
}