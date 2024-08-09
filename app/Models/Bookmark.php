<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_bookmark';

    protected $fillable = [
        'id_pengumuman',
        'id_user'
    ];
}
