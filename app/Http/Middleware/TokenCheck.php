<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->headers->has('X-Auth-Token')) {
            $token = $request->headers->get('X-Auth-Token');
            // return response()->json(Admin::verifyToken($token));
            if (Admin::verifyToken($token)) {
                return $next($request);
            }
            return response()->json(['error' => 'Unauthorized Access'], 401);
        }
        return response()->json(['error' => 'Missing Token'], 401);
    }
}
