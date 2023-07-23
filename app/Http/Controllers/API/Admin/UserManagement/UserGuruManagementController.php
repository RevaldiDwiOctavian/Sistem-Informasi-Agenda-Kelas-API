<?php

namespace App\Http\Controllers\API\Admin\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserGuruManagementController extends Controller
{
    public function getUserGuru()
    {
        if (auth()->user()->role == "admin") {
            $user = DB::table('gurus')
                ->select('users.id', 'users.name as nama_akun', 'users.email', 'users.role', 'users.status', 'gurus.nama_lengkap', 'gurus.nuptk')
                ->rightJoin('users', 'gurus.user_id', '=', 'users.id')
                ->where('users.role', 'guru')
                ->orWhere('users.role', 'walikelas')
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $user], 200);
    }

    public function getUserGuruById($id) {
        if (auth()->user()->role == "admin") {
            $user = DB::table('gurus')
                ->select('users.id', 'users.name', 'users.email', 'users.role', 'users.status', 'gurus.nama_lengkap', 'gurus.nuptk')
                ->rightJoin('users', 'gurus.user_id', '=', 'users.id')
                ->where('users.id', $id, 'and')
                ->where('users.role', '=', 'guru')
                ->where('users.role', '=', 'walikelas')
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
