<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\DoPasswordResetFormRequest;
use App\Http\Requests\Api\Auth\DoPasswordResetRequest;
use App\Http\Requests\Api\Auth\RequestResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show form for requesting a password reset
     */
    public function showRequestResetPasswordForm()
    {
        return view('request-reset-password');
    }

    /**
     * Send password reset link
     */
    public function requestResetPassword(RequestResetPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['state' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show password reset form. This is the one sent in the email in requestResetPassword
     */
    public function showDoPasswordResetForm(DoPasswordResetFormRequest $request)
    {
        return view('reset-password', $request->only(['email', 'token']));
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

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
