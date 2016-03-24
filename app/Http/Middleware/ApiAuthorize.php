<?php 

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiAuthorize
{
    public function handle($request, Closure $next)
    {
		$user = User::where('email','=',$request->only('email'))->first();
		
		if (!$user->can('apiAdmin')) {
			return response()->json([
				'error' => 'Unauthorized, the user with which you are trying to authenticate doesn\'t have the necessary rights to access this route'
			], 401);
		}

		return $next($request); 
    }
}