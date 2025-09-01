<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $user = User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'username' => $request->username,
            'mobile'=>$request->mobile,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
    
         event(new Registered($user));
        
        return response()->json(['message' => 'Registered successfully.']);

    }

    public function login(Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email not verified'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'token'   => $token,
        'user'    => $user
    ]);
}


}


    


