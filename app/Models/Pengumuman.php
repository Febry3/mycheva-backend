<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected  $primaryKey = 'id_pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'id_user'
    ];
}
