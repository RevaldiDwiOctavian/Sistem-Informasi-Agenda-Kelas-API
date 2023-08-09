<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaAbsen extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'agenda_kelas_id',
        'keterangan_absen',
        'alasan',
    ];
}
