<?php

namespace App\Http\Controllers\API\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class UserAdminManagementController extends Controller
{
    public function getUserAdmin()
    {
        if (auth()->user()->role == "admin") {
            $user = User::where('role', 'admin')->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $user], 200);
    }

    public function getUserAdminById($id) {
        if (auth()->user()->role == "admin") {
            $user = DB::table('users')
                ->select('users.id', 'users.name', 'users.email', 'users.role', 'users.status')
                ->where('users.id', $id, 'and')
                ->where('users.role', 'admin')
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
