<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register'])
->middleware('api')
->name('register');

Route::post('/login', [AuthController::class, 'login'])
->middleware('api')
->name('login');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
     ->middleware(['signed', 'throttle:6,1'])
     ->name('verification.verify');
     
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
})->middleware(['auth:sanctum', 'throttle:6,1'])
->name('verification.send');



