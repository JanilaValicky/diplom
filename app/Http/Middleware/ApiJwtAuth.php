<?php

namespace App\Http\Middleware;

use App\AuthJwtHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiJwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearer_header = $request->header('Authorization', 'Bearer ');
        $token = str_replace('Bearer ', '', $bearer_header);
        if (!AuthJwtHelper::isValidToken($token)) {
            return new Response('Unauthorized', 401);
        }
        $user = AuthJwtHelper::auth($token);
        $request->merge(['user' => $user]);
        Auth::setUser($user);

        return $next($request);
    }
}
