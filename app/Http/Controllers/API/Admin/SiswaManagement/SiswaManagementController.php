<?php

namespace App\Http\Controllers\API\Admin\SiswaManagement;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Validator;

class SiswaManagementController extends Controller
{
    public function addSiswa(Request $request) {
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(),[
                'nama_lengkap' => 'required|string|max:255',
                'nisn' => 'required|string|max:255',
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
    
            $siswa = Siswa::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'user_id' => $request->user_id,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response() -> json([
            'message' => "Sucess",
            'data' => $siswa
        ]);
    }

    public function showSiswas()
    {
        if (auth()->user()->role == "admin") {
            $siswa = Siswa::all();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $siswa], 200);
    }

    public function getSiswaById($id) {
        if (auth()->user()->role == "admin") {
            $siswa = Siswa::find($id);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        if ($siswa != null) {
            return response()->json(['data' => $siswa], 200);
        }
        
        return response()->json(['message' => "Siswa not found"], 404);
    }

    public function updateSiswa($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $siswa = Siswa::find($id);
        if (auth()->user()->role == "admin") {
            $siswa->nama_lengkap = $request->nama_lengkap;
            $siswa->nisn = $request->nisn;
            $siswa->user_id = $request->user_id;
            if ($siswa->save()) {
                return response()->json(['message' => "Siswa Updated"], 200);
            } else {
                return response()->json(['message' => "Failed to Update"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function deleteSiswa($id)
    {
        $siswa = Siswa::find($id);
        if (auth()->user()->role == "admin") {
            if ($siswa->delete()) {
                return response()->json(['message' => "Siswa Deleted"], 200);
            } else {
                return response()->json(['message' => "Failed to Delete"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }
}
