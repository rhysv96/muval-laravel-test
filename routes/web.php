<?php

use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegistrationController;
use App\Http\Controllers\Web\Auth\VerificationController;
use App\Http\Controllers\Web\Tasks\TaskController;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'])->name('home');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register'])->name('register');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequestResetPasswordForm'])->name('request-reset-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'requestResetPassword'])->name('request-reset-password');
Route::get('/reset-password', [ForgotPasswordController::class, 'showDoPasswordResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'doPasswordReset'])->name('reset-password');

Route::get('/verify-notice', [VerificationController::class, 'showVerifyNotice'])->name('verification.notice');
Route::get('/verify', [VerificationController::class, 'showVerifyForm'])->name('verification.verify');
Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    Route::post('/send-verification', [VerificationController::class, 'sendVerificationEmail'])->name('verification.send');

    Route::group(['middleware' => [EnsureEmailIsVerified::class]], function () {
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit']);
        Route::post('/tasks/update/{task}', [TaskController::class, 'update']);
        Route::get('/tasks/{task}/delete', [TaskController::class, 'destroy']);
    });
});
