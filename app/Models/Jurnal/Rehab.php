<?php

namespace App\Models\Jurnal;
use Illuminate\Database\Eloquent\Model;

class Rehab extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rehab_id', 'nama_rehab', 'tahun_rehab', 'nilai_rehab', 'kode_rek_rehab', 'nomor_lokasi', 'aset_induk_id', 'nama_induk', 'tahun_induk', 'nilai_induk', 'kode_rek_induk', 'tambah_manfaat'
    ];

    protected $primaryKey = 'rehab_id';
    public $incrementing = false;
}
