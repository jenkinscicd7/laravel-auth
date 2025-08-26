<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
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
        
        return response()->json(['message' => 'Registered successfully. Check your email for verification link.']);
    }

    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if(!$user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email not verified'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token]);
    }
}


    


