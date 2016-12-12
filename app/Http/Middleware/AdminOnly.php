<?php

namespace App\Http\Middleware;

use Closure;
use Gate;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        if (Gate::denies('adminOnly')) {
            return response()->json([
                'error' => 'Unauthorized'
                ], 401);
        }

        return $next($request);
    }
}
