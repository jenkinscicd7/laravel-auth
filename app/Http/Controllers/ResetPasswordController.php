<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    
    public function sendOtp(Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'Email not found'], 404);
    }

    $otp = rand(100000, 999999);

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $otp, 'created_at' => Carbon::now()]
    );

    Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
    $message->to($request->email)
            ->subject('Your OTP Code');
    });

    return response()->json(['message' => 'OTP sent to email']);
}

public function verifyOtp(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|digits:6'
    ]);

    $record = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->where('token', $request->otp)
        ->first();

    if (!$record) {
        return response()->json(['message' => 'Invalid OTP'], 400);
    }

    if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
        return response()->json(['message' => 'OTP expired'], 400);
    }

    return response()->json(['message' => 'OTP verified']);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Password reset successful']);
}

}