<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_permen_17 extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'golongan', 'bidang', 'kelompok', 'sub_kelompok', 'sub_sub_kelompok', 'kode', 'uraian'
    ];

    protected $primaryKey = 'kode';
    public $incrementing = false;

}
