<?php

namespace App\Http\Controllers\API\Guru;

use App\Http\Controllers\Controller;
use App\Models\AgendaKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaKelasGuruController extends Controller
{
    public function isiAgendaKelas(Request $request) {
        if (auth()->user()->role == "guru") {
            $validator = Validator::make($request->all(), [
                'rombel_id' => 'required',
                'guru_id' => 'required',
                'pembelajaran_id' => 'required',
                'materi_pembelajaran' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $agendaKelas = AgendaKelas::create([
                'rombel_id' => $request->rombel_id,
                'guru_id' => $request->guru_id,
                'pembelajaran_id' => $request->pembelajaran_id,
                'materi_pembelajaran' => $request->materi_pembelajaran,
                'kehadiran_guru' => 'unconfirmed',
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Success adding agenda kelas",
            'data' => $agendaKelas
        ]);
    }

    public function isiSiswaAbsen(Request $request) {
        if (auth()->user()->role == "guru") {
            $validator  = Validator::make($request->all(), [

            ]);
        }
    }
}
