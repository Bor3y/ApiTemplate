<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\API\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\ResetPasswordRequest;
use App\Http\Requests\API\User\ChangePasswordRequest;

class PasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (\Hash::check($request->old_password, $user->password)) {
            $user->forceFill([
                'password' => $request->new_password,
            ])->save();
        } else {
            return API::respond("incorrect old password", 400);
        }

        return API::respond("OK", 200);
    }


    public function sendResetLinkEmail(ResetPasswordRequest $request)
    {
        if(count($this->users->findByField('email', $request->email)) != 0){
            $response = $this->broker()->sendResetLink($request->only('email'));
            return $response == \Password::RESET_LINK_SENT
                ? API::respond(__('passwords.sent'), 200)
                : API::respond(__($response), 400);
        }
        else {
            return API::respond(__('passwords.user'), 403);
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return \Password::broker('users');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard('api');
    }
}
