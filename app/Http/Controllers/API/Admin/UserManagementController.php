<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class UserManagementController extends Controller
{
    public function getUserAdmin(){
            if(auth()->user()->role == "admin"){
                $user = User::all();
            }else{
                return response() -> json(['message' => "You don't have access"]);
            }

        return response() -> json(['data' => $user], 200);
    }

    public function getUserGuru(){
        if(auth()->user()->role == "admin"){
            $user = DB::table('gurus')
            ->select('users.email', 'users.role', 'gurus.nama_lengkap', 'gurus.nuptk')
            ->rightJoin('users', 'gurus.user_id', '=', 'users.id')
            ->where('users.role', 'guru')
            ->get();
        }else{
            return response() -> json(['message' => "You don't have access"]);
        }

        return response() -> json(['data' => $user], 200);
    }

    public function getUserSiswa(){
        if(auth()->user()->role == "admin"){
            $user = DB::table('siswas')
            ->select('users.email', 'users.role', 'siswas.nama_lengkap', 'siswas.nisn')
            ->rightJoin('users', 'siswas.user_id', '=', 'users.id')
            ->where('users.role', 'siswa')
            ->get();
        }else{
            return response() -> json(['message' => "You don't have access"]);
        }

        return response() -> json(['data' => $user], 200);
    }
}
