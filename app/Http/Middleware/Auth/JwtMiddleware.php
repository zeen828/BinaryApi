<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use App\Exceptions\Auth\JwtException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
// class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                // 令牌無效
                throw new JwtException('JWT_TOKEN_INVALID');
                // return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // 令牌過期
                throw new JwtException('JWT_TOKEN_EXPIRED');
                // return response()->json(['status' => 'Token is Expired']);
            }else{
                // 找不到另台
                throw new JwtException('JWT_TOKEN_NOT_FOUND');
                // return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
