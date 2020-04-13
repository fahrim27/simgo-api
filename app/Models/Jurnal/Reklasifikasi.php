<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Reklasifikasi extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nomor_lokasi', 'id_aset', 'tahun_reklas', 'jenis_reklas', 'asal', 'tujuan'
    ];
}
