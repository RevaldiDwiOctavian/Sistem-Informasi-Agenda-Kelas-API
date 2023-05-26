<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaAbsen extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_rombel',
        'id_siswa',
        'keterangan_absen',
        'alasan',
        'kelas'
    ];
}
