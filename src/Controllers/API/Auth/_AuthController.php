<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\API\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\Auth\TokenRefreshRequest;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        //TODO: Write Your Logic
    }

    public function login(LoginRequest $request)
    {
        $token = $this->guard()->attempt($request->only('email', 'password'));

        if(!$token) {
            return API::respond(__('auth.failed'), 400);
        }

        $user = $this->guard()->user();

        $ret = [
            "token_type" => "Bearer",
            "expires_in" => $this->guard()->setToken($token)->getPayload()->get('exp'),
            "access_token" => $token,
            "user" => $user,
        ];


        return API::respond(__("success.generic.ok"), 200, $ret);
    }

    public function user(Request $request){
        $user = $request->user();

        return API::respond(__("success.generic.ok"), 200, [
            'user' => $user
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        return API::respond(__("success.generic.ok"), 200);
    }

    public function refreshToken(TokenRefreshRequest $request)
    {

        try{
            $token = $this->guard()->refresh();
        }catch (TokenBlacklistedException $exception){
            return API::respond(__("error.generic.unauthorized"), 401);
        }

        $ret = [
            "token_type" => "Bearer",
            "expires_in" => $this->guard()->setToken($token)->getPayload()->get('exp'),
            "access_token" => $token,
        ];

        return API::respond(__("success.generic.ok"), 200, $ret);
    }

    protected function guard()
    {
        return \Auth::guard('api');
    }
}
