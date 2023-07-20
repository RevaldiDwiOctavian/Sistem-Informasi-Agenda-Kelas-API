<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'guru_id',
        'pembelajaran_id',
        'materi_pembelajaran',
        'kehadiran_guru',
    ];
}
