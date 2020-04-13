<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_pegawai extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_lokasi', 'nip', 'nama', 'jabatan', 'pangkat_gol', 'unit_kerja', 'keterangan'
    ];

    protected $primaryKey = 'nip';
    public $incrementing = false;

}
