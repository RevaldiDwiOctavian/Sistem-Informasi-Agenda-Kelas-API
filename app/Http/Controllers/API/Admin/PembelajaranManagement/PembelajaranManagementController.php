<?php

namespace App\Http\Controllers\API\Admin\PembelajaranManagement;

use App\Http\Controllers\Controller;
use App\Models\Pembelajaran;
use DB;
use Illuminate\Http\Request;
use Validator;

class PembelajaranManagementController extends Controller
{
    public function addPembelajaran(Request $request) {
        if (auth()->user()->role == "admin") {
            $validator = Validator::make($request->all(), [
                'mata_pelajaran' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $pembelajaran = Pembelajaran::create([
                'rombel_id' => $request->rombel_id,
                'guru_id' => $request->guru_id,
                'mata_pelajaran' => $request->mata_pelajaran,
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Success adding pembelajaran",
            'data' => $pembelajaran
        ]);
    }

    public function showPembelajarans()
    {
        if (auth()->user()->role == "admin") {
            $pembelajaran = DB::table('pembelajarans')
            ->select('pembelajarans.id', 'pembelajarans.mata_pelajaran', 'rombels.nama_rombel', 'rombels.jurusan', 'gurus.nama_lengkap', 'gurus.nuptk')
            ->leftJoin('rombels', 'pembelajarans.rombel_id', '=', 'rombels.id')
            ->leftJoin('gurus', 'pembelajarans.guru_id', '=', 'gurus.id')
            ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $pembelajaran], 200);
    }

    public function getPembelajaranById($id)
    {
        if (auth()->user()->role == "admin") {
            $pembelajaran = Pembelajaran::find($id);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        if ($pembelajaran != null) {
            return response()->json(['data' => $pembelajaran], 200);
        }

        return response()->json(['message' => "Pembelajaran not found"], 404);
    }
}