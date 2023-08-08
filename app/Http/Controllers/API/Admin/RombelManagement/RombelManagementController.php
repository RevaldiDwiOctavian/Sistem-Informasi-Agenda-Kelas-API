<?php

namespace App\Http\Controllers\API\Admin\RombelManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rombel;
use Illuminate\Support\Facades\DB;

class RombelManagementController extends Controller
{
    public function addRombel(Request $request) {
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|integer',
                'nama_rombel' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $rombel = Rombel::create([
                'user_id' => $request->user_id,
                'nama_rombel' => $request->nama_rombel,
                'jurusan' => $request->jurusan,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Success adding rombel",
            'data' => $rombel
        ]);
    }

    public function showRombels()
    {
        if (auth()) {
            $rombel = DB::table('rombels')
            ->select('rombels.id', 'rombels.nama_rombel', 'rombels.jurusan', 'rombels.user_id', 'users.name as wali_kelas')
            ->leftJoin('users', 'rombels.user_id', '=', 'users.id')
            ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $rombel], 200);
    }

    public function getRombelById($id)
    {
        if (auth()->user()->role == "admin") {
            $rombel = DB::table('rombels')
            ->select('rombels.id', 'rombels.nama_rombel', 'rombels.jurusan', 'rombels.user_id', 'users.name as wali_kelas')
            ->leftJoin('users', 'rombels.user_id', '=', 'users.id')
            ->where('rombels.id', $id)
            ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        if ($rombel != null) {
            return response()->json(['data' => $rombel], 200);
        }

        return response()->json(['message' => "Rombel not found"], 404);
    }

    public function getTotalRombel()
    {
        $total = DB::table('rombels')->count();

        if ($total != null) {
            return response()->json(['data' => $total], 200);
        }

        return response()->json(['data' => 0], 200);
    }

    public function updateRombel($id, Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_rombel' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $rombel = Rombel::find($id);
        if (auth()->user()->role == "admin") {
            $rombel->nama_rombel = $request->nama_rombel;
            $rombel->jurusan = $request->jurusan;
            $rombel->user_id = $request->user_id;
            if ($rombel->save()) {
                return response()->json(['message' => "Rombel Updated"], 200);
            } else {
                return response()->json(['message' => "Failed to Update"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function deleteRombel($id)
    {
        $rombel = Rombel::find($id);
        if (auth()->user()->role == "admin") {
            if ($rombel->delete()) {
                return response()->json(['message' => "Rombel Deleted"], 200);
            } else {
                return response()->json(['message' => "Failed to Delete"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }
}
