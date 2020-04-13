<?php

namespace App\Models\Kamus;
use Illuminate\Database\Eloquent\Model;

class Kamus_lokasi extends Model

{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_sub_unit', 'nomor_lokasi', 'nama_lokasi', 'nip_kepala', 'nip_bendahara_barang', 'nip_pengurus_barang', 'alamat', 'telepon'
    ];

    protected $primaryKey = 'nomor_lokasi';
    public $incrementing = false;

}
