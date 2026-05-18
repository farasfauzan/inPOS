<?php

namespace App\Http\Middleware;

use Closure;

class VerifyCsrfToken
{
    protected $except = [
        'login',
        'logout',
    ];

    public function handle($request, Closure $next)
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Skip CSRF for JSON requests
        if ($request->expectsJson()) {
            return $next($request);
        }

        return $next($request);
    }
}