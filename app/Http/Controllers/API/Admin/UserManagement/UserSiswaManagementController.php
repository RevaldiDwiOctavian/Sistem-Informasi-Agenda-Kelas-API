<?php

namespace App\Http\Controllers\API\Admin\UserManagement;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class UserSiswaManagementController extends Controller
{
    public function getUserSiswa()
    {
        if (auth()->user()->role == "admin") {
            $user = DB::table('siswas')
                ->select('users.id', 'users.name', 'users.email', 'users.role', 'siswas.nama_lengkap', 'siswas.nisn')
                ->rightJoin('users', 'siswas.user_id', '=', 'users.id')
                ->where('users.role', 'siswa')
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $user], 200);
    }

    public function getUserSiswaById($id) {
        if (auth()->user()->role == "admin") {
            $user = DB::table('siswas')
                ->select('users.id', 'users.name', 'users.email', 'users.role', 'siswas.nama_lengkap', 'siswas.nisn')
                ->rightJoin('users', 'siswas.user_id', '=', 'users.id')
                ->where('users.id', $id, 'and')
                ->where('users.role', 'siswa')
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        if ($user != null) {
            return response()->json(['data' => $user], 200);
        }
        
        return response()->json(['message' => "Users not found"], 404);
    }
}
