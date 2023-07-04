<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid Credentials',
                ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response() -> json([
            'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'
            ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        //return
        return response()->json([
            'message' => 'Successfully logged out'
            ]);
    }

    public function profile(){
        $user = Auth::user();
        return response() -> json(['data' => $user]);
    }
}
