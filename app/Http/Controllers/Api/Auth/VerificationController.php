<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\VerifyEmailFormRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /**
     * Send verification email to the user. This endpoint is used for re-sending.
     */
    public function sendVerificationEmail()
    {
        auth()->user()->sendEmailVerificationNotification();

        return response()->json([ 'success' => true ]);
    }

    /**
     * Do email verification
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return response()->json([ 'success' => true ]);
    }
}
