<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\API\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\Auth\TokenRefreshRequest;

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


        return API::respond("OK", 200, $ret);
    }

    public function logout()
    {
        $this->guard()->logout();

        return API::respond("OK", 200);
    }

    public function refreshToken(TokenRefreshRequest $request)
    {

        $token = $this->guard()->refresh();

        $ret = [
            "token_type" => "Bearer",
            "expires_in" => $this->guard()->setToken($token)->getPayload()->get('exp'),
            "access_token" => $token,
        ];

        return API::respond("OK", 200, $ret);
    }

    protected function guard()
    {
        return \Auth::guard('api');
    }
}
