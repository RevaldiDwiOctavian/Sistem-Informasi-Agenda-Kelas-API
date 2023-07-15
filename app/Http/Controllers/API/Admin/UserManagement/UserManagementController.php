<?php

namespace App\Http\Controllers\API\Admin\UserManagement;

use App\Http\Controllers\Controller;
use DB;
use Hash;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Validator;

class UserManagementController extends Controller
{
    public function addUser(Request $request){
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response() -> json([
            'data' => $user
        ]);
    }
    public function updateUsers($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($id);
        if (auth()->user()->role == "admin") {
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            if ($user->save()) {
                return response()->json(['message' => "User Updated"], 200);
            } else {
                return response()->json(['message' => "Failed to Update"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

    }

    public function deactivateUser($id) {
        $user = User::find($id);
        if (auth()->user()->role == "admin") {
            if($user->status == "nonaktif"){
                return response()->json(['message' => "User already inactive"]);
            }
            $user->status = "nonaktif";
            if ($user->save()) {
                return response()->json(['message' => "User deactivated"], 200);
            } else {
                return response()->json(['message' => "Failed to deactivate user"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function activateUser($id) {
        $user = User::find($id);
        if (auth()->user()->role == "admin") {
            if($user->status == "aktif"){
                return response()->json(['message' => "User already active"]);
            }
            $user->status = "aktif";
            if ($user->save()) {
                return response()->json(['message' => "User activated"], 200);
            } else {
                return response()->json(['message' => "Failed to activate user"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (auth()->user()->role == "admin") {
            if ($user->delete()) {
                return response()->json(['message' => "User Deleted"], 200);
            } else {
                return response()->json(['message' => "Failed to Delete"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }
}
