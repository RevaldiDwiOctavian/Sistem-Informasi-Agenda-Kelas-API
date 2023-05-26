<?php

namespace App\Http\Controllers\API;

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
        if(auth()->user()->role != null){
            if(auth()->user()->role == "siswa"){
                $user = Auth::user()->siswa;
            }elseif(auth()->user()->role == "guru"){
                $user = Auth::user()->guru;
            }elseif(auth()->user()->role == "admin"){
                $user = Auth::user();
            }
        }
        return response() -> json(['data' => $user]);
    }
}
