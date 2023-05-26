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
        'nama_lengkap',
        'nisn'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
