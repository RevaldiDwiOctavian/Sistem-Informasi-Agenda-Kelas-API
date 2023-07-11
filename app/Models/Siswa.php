<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_rombel',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
