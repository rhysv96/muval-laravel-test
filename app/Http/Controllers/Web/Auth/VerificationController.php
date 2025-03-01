<?php

namespace App\Http\Controllers\Web\Auth;

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

        return back()->with(['status' => 'Verification link sent to your email.']);
    }

    /**
     * Show email validation error message and redirect to the tasks index or home page if not authenticated
     * Used by Laravel's EnsureEmailIsVerified middleware
     */
    public function showVerifyNotice()
    {
        return redirect()
            ->route(is_null(auth()->user()) ? 'home' : 'tasks.index')
            ->with(['status' => 'Your email has not been verified.']);
    }

    /**
     * Show verify email form. This is the page that emailed to the user
     */
    public function showVerifyForm(VerifyEmailFormRequest $request)
    {
        return view('verification.verify', $request->only(
            'id',
            'hash',
            'signature',
            'expires',
        ));
    }

    /**
     * Do email verification
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('tasks.index');
    }
}
