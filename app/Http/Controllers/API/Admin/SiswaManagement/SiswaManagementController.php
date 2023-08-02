<?php

namespace App\Http\Controllers\API\Admin\SiswaManagement;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SiswaManagementController extends Controller
{
    public function addSiswa(Request $request)
    {
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(), [
                'nisn' => 'required|string|max:255',
                'nama_lengkap' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $siswa = Siswa::create([
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'user_id' => $request->user_id,
                'rombel_id' => $request->rombel_id,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Sucess",
            'data' => $siswa
        ]);
    }

    public function showSiswas()
    {
        if (auth()->user()->role == "admin") {
            $siswa = DB::table('siswas')
                ->select('siswas.id', 'siswas.nisn', 'siswas.nama_lengkap', 'siswas.rombel_id', 'rombels.nama_rombel', 'siswas.jenis_kelamin', 'rombels.jurusan', 'siswas.created_at', 'siswas.user_id', 'users.name as nama_akun')
                ->leftJoin('users', 'siswas.user_id', '=', 'users.id')
                ->leftJoin('rombels', 'siswas.rombel_id', '=', 'rombels.id')
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $siswa], 200);
    }

    public function getSiswaById($id)
    {
        if (auth()->user()->role == "admin") {
            $siswa = DB::table('siswas')
                ->select('siswas.id', 'siswas.nama_lengkap', 'rombels.nama_rombel', 'rombels.jurusan', 'users.name as nama_akun')
                ->leftJoin('users', 'siswas.user_id', '=', 'users.id')
                ->leftJoin('rombels', 'siswas.rombel_id', '=', 'rombels.id')
                ->where('siswas.id', $id)
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        if ($siswa != null) {
            return response()->json(['data' => $siswa], 200);
        }

        return response()->json(['message' => "Siswa not found"], 404);
    }

    public function getTotalSiswa()
    {
        $total = DB::table('siswas')->count();

        if ($total != null) {
            return response()->json(['data' => $total], 200);
        }

        return response()->json(['data' => "0"], 200);
    }

    public function updateSiswa($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $siswa = Siswa::find($id);
        if (auth()->user()->role == "admin") {
            $siswa->nisn = $request->nisn;
            $siswa->nama_lengkap = $request->nama_lengkap;
            $siswa->jenis_kelamin = $request->jenis_kelamin;
            $siswa->user_id = $request->user_id;
            $siswa->rombel_id = $request->rombel_id;
            if ($siswa->save()) {
                return response()->json(['message' => "Siswa Updated"], 200);
            } else {
                return response()->json(['message' => "Failed to Update"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function setUserSiswa($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|integer', // Add validation rules for user_id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $siswa = Siswa::find($id);
        if (auth()->user()->role == "admin") {
            $siswa->user_id = $request->user_id; // Update only the user_id field
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
