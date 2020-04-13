<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_permen_108 extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'akun', 'kelompok', 'jenis', 'objek', 'rincian_objek', 'kode', 'uraian'
    ];

    protected $primaryKey = 'kode';
    public $incrementing = false;

}
