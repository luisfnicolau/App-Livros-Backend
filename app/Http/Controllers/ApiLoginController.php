<?php

namespace App\Http\Controllers;

use App\Managers\SocialLogin;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use JWTAuth;
use Socialite;

class ApiLoginController extends Controller
{
    public function login(Request $request){
        if(!$request->has('driver') || !$request->has('token')){
            return response()->json([
                        'message' =>
                            'Request must have driver and token parameters',
                    ],400);
        }
        $driver = $request->get('driver');
        $socialToken = $request->get('token');
        try{
            $socialiteUser = Socialite::driver($driver)
                                        ->userFromToken($socialToken);
        } catch(ClientException $ex){
            $facebookResponse = $ex->getResponse();
            $statusCode       = $facebookResponse->getStatusCode();
            $headers          = $facebookResponse->getHeaders();
            
            return response()->json([
                                    'message' => 'Facebook request failed',
                                    'facebook-headers' => $headers,
                                    ], $statusCode);
        }
        $user = SocialLogin::findOrCreateUser($socialiteUser, $driver);
        $appToken = JWTAuth::fromUser($user);
        $user->app_token = $appToken;
        $user->token_expires = Carbon::now()->addMinutes(30);
        if(env('APP_ENV')!=='local')
            $user->token_expires = Carbon::now()->addHours(24);
        $user->save();
        return response()->json([
                                'token' => $appToken
                                ]);
    }
}
