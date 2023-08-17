<?php

namespace App\Http\Controllers\API\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AgendaKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaKelasSiswaController extends Controller
{
    public function getAgendeKelasUnconfirmedByRombel($id){
        if (auth()->user()->role == "siswa") {
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
                ->where('agenda_kelas.rombel_id', $id)
                ->where('agenda_kelas.kehadiran_guru', 'Belum Dikonfirmasi')
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

    public function jumlahAgendaKelasBelumKonfirmasi($id) {
        if (auth()->user()->role == "siswa") { 
            $agendaKelas = DB::table('agenda_kelas')
            ->where('rombel_id', $id)
            ->where('kehadiran_guru', 'Belum Dikonfirmasi')
            ->count();;
            return response()->json(['data' => $agendaKelas], 200);
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function konfirmasiHadirAgendaKelas($id){
        $agenda = AgendaKelas::find($id);
        if (auth()->user()->role == "siswa") {
            $agenda->kehadiran_guru = "Hadir";
            if ($agenda->save()) {
                return response()->json(['message' => "Agenda confirmed"], 200);
            } else {
                return response()->json(['message' => "Failed to confirm agenda"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }

    public function konfirmasiTidakHadirAgendaKelas($id){
        $agenda = AgendaKelas::find($id);
        if (auth()->user()->role == "siswa") {
            $agenda->kehadiran_guru = "Tidak Hadir";
            if ($agenda->save()) {
                return response()->json(['message' => "Agenda confirmed"], 200);
            } else {
                return response()->json(['message' => "Failed to confirm agenda"]);
            }
        } else {
            return response()->json(['message' => "You don't have access"]);
        }
    }
}
