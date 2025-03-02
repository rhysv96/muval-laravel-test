<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\DoPasswordResetRequest;
use App\Http\Requests\Api\Auth\RequestResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordController extends Controller
{
    /**
     * Send password reset link
     */
    public function requestResetPassword(RequestResetPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['status' => $status], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Do the password reset
     */
    public function doPasswordReset(DoPasswordResetRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->fill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['status' => $status], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['status' => $status]);
    }
}
