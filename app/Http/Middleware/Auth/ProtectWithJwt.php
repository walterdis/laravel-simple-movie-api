<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

/**
 * Class ProtectWithJwt
 *
 * @package App\Http\Middleware\Auth
 */
class ProtectWithJwt extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token is Invalid']);
            } else {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json(['status' => 'Token is Expired']);
                } else {
                    return response()->json(['status' => 'Authorization Token not found']);
                }
            }
        }

        return $next($request);
    }
}
