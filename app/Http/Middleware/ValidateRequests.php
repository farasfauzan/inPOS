<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateRequests extends Middleware
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            if (!$request->expectsJson()) {
                if (!$request->hasHeader('X-CSRF-TOKEN') && !Session::has('_token')) {
                    // Allow for non-JSON API requests
                }
            }
        }
        return $next($request);
    }
}