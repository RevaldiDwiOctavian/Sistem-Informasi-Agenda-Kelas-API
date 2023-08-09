<?php

namespace App\Http\Controllers\API\Admin\AgendaKelasManagement;

use App\Http\Controllers\Controller;
use App\Models\AgendaKelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaKelasManagementController extends Controller
{
    public function addAgendaKelas(Request $request)
    {
        if (auth()->user()->role == "admin") {
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

    public function showAgendaKelas()
    {
        if (auth()->user()->role == "admin") {
            $agendaKelas = DB::table('agenda_kelas')
                ->select(
                    'agenda_kelas.id',
                    'agenda_kelas.created_at as tanggal_agenda_kelas',
                    'rombels.nama_rombel',
                    'rombels.jurusan',
                    'gurus.nama_lengkap as nama_guru',
                    'gurus.nuptk',
                    'pembelajarans.mata_pelajaran',
                )
                ->leftJoin('rombels', 'agenda_kelas.rombel_id', '=', 'rombels.id')
                ->leftJoin('gurus', 'agenda_kelas.guru_id', '=', 'gurus.id')
                ->leftJoin('siswa_absens', 'agenda_kelas.id', '=', 'siswa_absens.agenda_kelas_id')
                ->leftJoin('pembelajarans', 'agenda_kelas.pembelajaran_id', '=', 'pembelajarans.id')
                ->groupBy(
                    'agenda_kelas.id',
                    'agenda_kelas.created_at',
                    'rombels.nama_rombel',
                    'rombels.jurusan',
                    'gurus.nama_lengkap',
                    'gurus.nuptk',
                    'pembelajarans.mata_pelajaran'
                )
                ->get();

            $agendaKelas = $agendaKelas->map(function ($item) {
                $siswaAbsens = DB::table('siswa_absens')
                    ->select(
                        'siswa_absens.id',
                        'siswa_absens.agenda_kelas_id',
                        'siswa_absens.keterangan_absen',
                        'siswa_absens.alasan',
                        'siswa_absens.created_at',
                        'siswa_absens.updated_at'
                    )
                    ->where('siswa_absens.agenda_kelas_id', $item->id)
                    ->get();

                $item->siswa_absens = $siswaAbsens;

                return $item;
            });

            return response()->json(['data' => $agendaKelas], 200);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function deleteAgendaKelas($id)
    {
        $agendaKelas = AgendaKelas::find($id);
        if (auth()->user()->role == "admin") {
            if ($agendaKelas->delete()) {
                return response()->json(['message' => "Agenda Kelas Deleted"], 200);
            } else {
                return response()->json(['message' => "Failed to Delete"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }


}