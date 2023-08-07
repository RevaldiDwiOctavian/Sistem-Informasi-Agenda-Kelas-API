<?php

namespace App\Http\Controllers\API\Admin\GuruManagement;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GuruManagementController extends Controller
{
    public function addGuru(Request $request)
    {
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(), [
                'nama_lengkap' => 'required|string|max:255',
                'nuptk' => 'required|string|max:20',
                'jenis_kelamin' => 'required|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $guru = Guru::create([
                'nuptk' => $request->nuptk,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'user_id' => $request->user_id,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Success",
            'data' => $guru
        ]);
    }

    public function showGurus()
    {
        if (auth()->user()->role == "admin") {
            $user = DB::table('gurus')
            ->select('gurus.id', 'gurus.nuptk', 'gurus.nama_lengkap', 'gurus.jenis_kelamin', 'gurus.created_at', 'gurus.user_id', 'users.name as nama_akun')
            ->leftJoin('users', 'gurus.user_id', '=', 'users.id')
            ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $user], 200);
    }

    public function getGuruById($id)
    {
        if (auth()->user()->role == "admin") {
            $guru = Guru::find($id);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        if ($guru != null) {
            return response()->json(['data' => $guru], 200);
        }

        return response()->json(['message' => "Guru not found"], 404);
    }

    public function updateGuru($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nuptk' => 'required|string|max:20',
            'jenis_kelamin' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $guru = Guru::find($id);
        if (auth()->user()->role == "admin") {
            $guru->nuptk = $request->nuptk;
            $guru->nama_lengkap = $request->nama_lengkap;
            $guru->jenis_kelamin = $request->jenis_kelamin;
            $guru->user_id = $request->user_id;
            if ($guru->save()) {
                return response()->json(['message' => "Guru Updated"], 200);
            } else {
                return response()->json(['message' => "Failed to Update"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function setUserGuru($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|integer', // Add validation rules for user_id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $guru = Guru::find($id);
        if (auth()->user()->role == "admin") {
            $guru->user_id = $request->user_id; // Update only the user_id field
            if ($guru->save()) {
                return response()->json(['message' => "Guru Updated"], 200);
            } else {
                return response()->json(['message' => "Failed to Update"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function getTotalGuru()
    {
        $total = DB::table('gurus')->count();

        if ($total != null) {
            return response()->json(['data' => $total], 200);
        }

        return response()->json(['data' => 0], 200);
    }

    public function deleteGuru($id)
    {
        $guru = Guru::find($id);
        if (auth()->user()->role == "admin") {
            if ($guru->delete()) {
                return response()->json(['message' => "Guru Deleted"], 200);
            } else {
                return response()->json(['message' => "Failed to Delete"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }
}