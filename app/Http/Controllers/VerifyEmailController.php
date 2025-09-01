<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Models\User;



class VerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
    $user = User::find($id);
    if(!$user){
        return response()->json([
            'status'=>false,
            'message'=>'user not found in the database'
        ], 404);
    }

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid verification link.',
        ], 400);
    }

    if (! URL::hasValidSignature($request)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid or expired signature.',
        ], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'status' => true,
            'message' => 'Email already verified.',
            'data' => ['verified' => true],
        ], 200);
    }

    $user->markEmailAsVerified();
    event(new Verified($user));

    return response()->json([
        'status' => true,
        'message' => 'Email verified successfully.',
        'data' => [
            'verified' => true,
            'user' => $user,
        ],
    ], 200);
}

}

