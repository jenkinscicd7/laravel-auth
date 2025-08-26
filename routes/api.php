<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
//displays a message prompting users to verify email
     
//handles verification linkRoute::get('/email/verify',
 Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')
->name('verification.notice');


//handler
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
     $request->fulfill(); return redirect('/home'); })
     ->middleware(['auth', 'signed'])
     ->name('verification.verify');

//Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
     $request->user()->sendEmailVerificationNotification();
      return back()->with('message', 'Verification link sent!'); })
      ->middleware(['auth', 'throttle:6,1'])
      ->name('verification.send');

//use verified middleware to restrict access to verified users

Route::get('/profile', function () {
// Only verified users can access this route
})->middleware(['auth', 'verified']);

