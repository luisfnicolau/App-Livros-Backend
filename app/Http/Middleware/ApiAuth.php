<?php

namespace App\Http\Middleware;

use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use JWTAuth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            $token = JWTAuth::getToken();
            $user = User::whereNotNull('app_token')
                        ->where('app_token', $token)->first();
            if (!$user || !$user->exists){
                return response()->json(['user_not_found'], 404);
            }
            $response = response()->json([
                                    'token_readed'=> $token,
                                    'user_found'=> $user->toArray(),
                                    ]);
            $now = Carbon::now();
            if($now->gt($user->token_expires)){
                throw new TokenExpiredException;
            }
            Auth::login($user);
            $response = $next($request);
            Auth::logout();
        } else{
            $response = response()->json(['not_allowed'], 403);
        }
        Auth::logout();
        return $response;
    }
}
