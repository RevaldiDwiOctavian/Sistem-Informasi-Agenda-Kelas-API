<?php

namespace App\Http\Controllers\API\Guru;

use App\Http\Controllers\Controller;
use App\Models\AgendaKelas;
use App\Models\SiswaAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgendaKelasGuruController extends Controller
{
    public function isiAgendaKelas(Request $request)
    {
        if (auth()->user()->role == "guru" || auth()->user()->role == "walikelas") {
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
                'kehadiran_guru' => 'Belum Dikonfirmasi',
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Success adding agenda kelas",
            'data' => $agendaKelas
        ]);
    }

    public function isiSiswaAbsen(Request $request)
    {
        if (auth()->user()->role == "guru" || auth()->user()->role == "walikelas") {
            $validator = Validator::make($request->all(), [
                'siswa_id' => 'required',
                'agenda_kelas_id' => 'required',
                'keterangan_absen' => 'required|string|max:255',
                'alasan' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $siswaAbsen = SiswaAbsen::create([
                'siswa_id' => request('siswa_id'),
                'agenda_kelas_id' => request('agenda_kelas_id'),
                'keterangan_absen' => request('keterangan_absen'),
                'alasan' => request('alasan'),
            ]);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json([
            'message' => "Success adding siswa absen",
            'data' => $siswaAbsen
        ]);
    }

    public function showSiswaAbsenByCurrentAgendaKelas($id){
        if (auth()->user()->role == "guru" || auth()->user()->role == "walikelas") {
            $siswaAbsen = DB::table('siswa_absens')
                ->select('siswas.id', 'siswas.nisn', 'siswas.nama_lengkap', 'siswas.rombel_id', 'siswa_absens.*')
                ->leftJoin('siswas', 'siswas.id', '=', 'siswa_absens.siswa_id')
                ->where('siswa_absens.agenda_kelas_id', $id)
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $siswaAbsen], 200);
    }

    public function showSiswaByCurrentAgenda($id)
    {
        if (auth()->user()->role == "guru" || auth()->user()->role == "walikelas") {
            $siswa = DB::table('siswas')
                ->select('siswas.id', 'siswas.nisn', 'siswas.nama_lengkap', 'siswas.rombel_id', 'rombels.nama_rombel', 'siswas.jenis_kelamin', 'rombels.jurusan', 'siswas.created_at', 'siswas.user_id', 'users.name as nama_akun')
                ->leftJoin('users', 'siswas.user_id', '=', 'users.id')
                ->leftJoin('rombels', 'siswas.rombel_id', '=', 'rombels.id')
                ->where('siswas.rombel_id', $id)
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $siswa], 200);
    }

    public function deleteSiswaAbsen($id)
    {
        $siswaAbsen = SiswaAbsen::find($id);
        if (auth()) {
            if ($siswaAbsen->delete()) {
                return response()->json(['message' => "Siswa Absen Deleted"], 200);
            } else {
                return response()->json(['message' => "Failed to Delete"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function showPembelajaranGuru($id)
    {
        if (auth()) {
            $pembelajaran = DB::table('pembelajarans')
                ->select('pembelajarans.*', 'rombels.nama_rombel', 'rombels.jurusan', 'gurus.nama_lengkap as guru_pengampu', 'gurus.nuptk')
                ->leftJoin('rombels', 'pembelajarans.rombel_id', '=', 'rombels.id')
                ->leftJoin('gurus', 'pembelajarans.guru_id', '=', 'gurus.id')
                ->where('pembelajarans.guru_id', $id)
                ->get();
        } else {
            return response()->json(['message' => "You don't have access"]);
        }

        return response()->json(['data' => $pembelajaran], 200);
    }
}
