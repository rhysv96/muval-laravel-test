<?php

use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\WhoAmIController;
use App\Http\Controllers\Api\Tasks\TaskController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'requestResetPassword']);
Route::post('/reset-password', [ForgotPasswordController::class, 'doPasswordReset']);
Route::post('/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/whoami',  [ WhoAmIController::class, 'whoAmI' ]);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/send-verification', [VerificationController::class, 'sendVerificationEmail']);
    Route::get('/tasks/{task}', [TaskController::class, 'get']);
    Route::get('/tasks', [TaskController::class, 'index']);

    Route::group(['middleware' => [EnsureEmailIsVerified::class]], function () {
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::patch('/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
        Route::get('/users', [UserController::class, 'index']);
    });
});
