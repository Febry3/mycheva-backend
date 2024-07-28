<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected  $primaryKey = 'id_jadwal';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_berakhir',
        'ruangan',
        'jenis_kegiatan',
        'divisi',
        'id_user'
    ];
}
