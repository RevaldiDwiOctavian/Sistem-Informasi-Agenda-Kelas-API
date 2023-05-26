<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_rombel',
        'id_guru',
        'mata_pelajaran',
    ];
}
