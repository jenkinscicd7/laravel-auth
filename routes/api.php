<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Validator;



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
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ]);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    $user = User::where('email', $request->email)->first();
    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified.'], 400);
    }
    $user->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link resent.']);
})->middleware(['throttle:6,1'])
->name('verification.send');



