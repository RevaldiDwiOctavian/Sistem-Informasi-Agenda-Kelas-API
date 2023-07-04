<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Hash;
use Illuminate\Http\Request;
use Validator;

class GuruManagementController extends Controller
{
    public function addGuru(Request $request) {
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(),[
                'nama_lengkap' => 'required|string|max:255',
                'nuptk' => 'required|string|max:255',
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
    
            $guru = Guru::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nuptk' => $request->nuptk,
                'user_id' => $request->user_id,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response() -> json([
            'message' => "Sucess",
            'data' => $guru
        ]);
    }

    public function showGurus()
    {
        if (auth()->user()->role == "admin") {
            $user = Guru::all();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $user], 200);
    }
}
