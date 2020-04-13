<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Masa_tambahan extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelompok', 'uraian', 'kode_108', 'kode_64', 'min', 'max', 'masa_tambahan'
    ];
}
