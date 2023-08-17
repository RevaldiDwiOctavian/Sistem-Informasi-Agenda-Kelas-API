<?php

namespace App\Http\Controllers\API;

use App\Exports\ExportExcel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportAgendaKelas(Request $request, $id)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namaBulan = DateTime::createFromFormat('!m', $bulan)->format('F');
        $agendaKelas = $this->getAgendaKelasDataByMonth($bulan, $tahun, $id);

        return Excel::download(new ExportExcel($agendaKelas), 'agenda_kelas_'.$namaBulan.'_'.$tahun.'.xlsx');
        // return view('excel-template.agendaKelas', ['data' => $agendaKelas]);
    }

    public function getAgendaKelasDataByMonth($bulan, $tahun, $id) {
        $agendaKelas = DB::table('agenda_kelas')
                ->select(
                    'agenda_kelas.id',
                    'agenda_kelas.created_at as tanggal_agenda_kelas',
                    'rombels.nama_rombel',
                    'rombels.jurusan',
                    'gurus.nama_lengkap as nama_guru',
                    'gurus.nuptk',
                    'pembelajarans.mata_pelajaran',
                    'agenda_kelas.materi_pembelajaran'
                )
                ->leftJoin('rombels', 'agenda_kelas.rombel_id', '=', 'rombels.id')
                ->leftJoin('gurus', 'agenda_kelas.guru_id', '=', 'gurus.id')
                ->leftJoin('siswa_absens', 'agenda_kelas.id', '=', 'siswa_absens.agenda_kelas_id')
                ->leftJoin('pembelajarans', 'agenda_kelas.pembelajaran_id', '=', 'pembelajarans.id')
                ->where('agenda_kelas.rombel_id', $id)
                ->whereYear('agenda_kelas.created_at', $tahun)
                ->whereMonth('agenda_kelas.created_at', $bulan)
                ->groupBy(
                    'agenda_kelas.created_at',
                    'agenda_kelas.id',
                    'rombels.nama_rombel',
                    'rombels.jurusan',
                    'gurus.nama_lengkap',
                    'gurus.nuptk',
                    'pembelajarans.mata_pelajaran',
                    'agenda_kelas.materi_pembelajaran'
                )
                ->get();

            $agendaKelas = $agendaKelas->map(function ($item) {
                $siswaAbsens = DB::table('siswa_absens')
                    ->select(
                        'siswas.nama_lengkap',
                        'siswas.nisn',
                        'jenis_kelamin',
                        'siswa_absens.keterangan_absen',
                        'siswa_absens.alasan',
                    )
                    ->leftJoin('siswas', 'siswa_absens.siswa_id', 'siswas.id')
                    ->where('siswa_absens.agenda_kelas_id', $item->id)
                    ->get();

                $item->siswa_absens = $siswaAbsens;

                return $item;
            });
            
            return $agendaKelas;
    }
}
