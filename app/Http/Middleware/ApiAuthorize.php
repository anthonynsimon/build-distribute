<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiAuthorize
{
    public function handle($request, Closure $next)
    {
        $user = User::where('email', '=', $request->only('email'))->first();
        
        if (!$user->hasRole('superAdmin')) {
            return response()->json([
                'error' => 'Unauthorized, you don\'t have the necessary rights to access this route'
            ], 401);
        }

        return $next($request);
    }
}
