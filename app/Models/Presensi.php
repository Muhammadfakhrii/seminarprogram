<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table ='presensis';
    protected $fillable =[
        'nik',
        'tgl_presensi',
        'jam_masuk',
        'jam_keluar',
        'foto_masuk',
        'foto_keluar'
    ];
}
