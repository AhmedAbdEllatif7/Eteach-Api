<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\ApiTrait;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException as ExceptionsJWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    use ApiTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            $user = FacadesJWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->ApiResponse('user not found', 404, '');
            }
        } catch (ExceptionsJWTException $e) {
            return $this->ApiResponse($e->getMessage(), 500, '');
//            return response()->json(['message' => $e->getMessage()], 500);
        }
        return $next($request);
    }
}
