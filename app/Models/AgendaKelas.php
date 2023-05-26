<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_rombel',
        'id_guru',
        'id_pembelajaran',
        'id_siswa_absen',
        'materi_pembelajaran',
        'tgl_agenda',
        'kehadiran_guru',
    ];
}
