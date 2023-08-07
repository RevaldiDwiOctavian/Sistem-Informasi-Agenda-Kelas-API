<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid Credentials',
                ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if ($user->status !== 'Aktif') {
            return response()->json([
                'message' => 'Akun tidak aktif',
            ], 401);
        }

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
       if (auth()->user()->role == "admin"){
        $user = Auth::user();
        return response() -> json(['data' => $user]);
       } else if (auth()->user()->role == "guru"){
        $user = DB::table('gurus')
        ->select('users.id', 'users.email', 'users.name', 'users.role', 'gurus.id as guru_id')
        ->rightJoin('users', 'gurus.user_id', '=', 'users.id')
        ->where('users.id', auth()->user()->id)
        ->first();
        return response() -> json(['data' => $user]);
       }
    }
}
